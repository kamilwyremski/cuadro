<?php
/************************************************************************
 * The script of website with pictures and movies CUADRO
 * Copyright (c) 2018 - 2023 by IT Works Better https://itworksbetter.net
 * Project by Kamil Wyremski https://wyremski.pl
 * 
 * All right reserved
 *
 * *********************************************************************
 * THIS SOFTWARE IS LICENSED - YOU CAN MODIFY THESE FILES BUT YOU CAN NOT REMOVE OF ORIGINAL COMMENTS!
 * ACCORDING TO THE LICENSE YOU CAN USE THE SCRIPT ON ONE DOMAIN.
 * *********************************************************************/

class user {

	public function __construct () {
		global $db, $settings;
		$this->logged_in = false;

		if(isset($_GET['logOut'])){

			$_SESSION['flash'] = 'logout';
			$this->logOut();
			header("Location: ".$settings['base_url']);
			die('redirect');

		}elseif(!empty($_SESSION['user']['id']) and !empty($_SESSION['user']['session_code'])){
			$this->loginFromSession();
		}elseif(!empty($_COOKIE['user_id']) and !empty($_COOKIE['user_code'])){
			$_SESSION['user']['id'] = $_COOKIE['user_id'];
			$_SESSION['user']['session_code'] = $_COOKIE['user_code'];
			$this->loginFromSession();
		}
	}

	public function __get($value){
		if(isset($this->user_data[$value])){
			return $this->user_data[$value];
		}
		return false;
	}

	public function loginFromSession(){
		global $db;
		$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'session_user WHERE user_id=:user_id AND code=:code LIMIT 1');
		$sth->bindValue(':user_id', $_SESSION['user']['id'], PDO::PARAM_INT);
		$sth->bindValue(':code', $_SESSION['user']['session_code'], PDO::PARAM_STR);
		$sth->execute();

		if($sth->fetchColumn()){
			$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'users WHERE id=:id LIMIT 1');
			$sth->bindValue(':id', $_SESSION['user']['id'], PDO::PARAM_INT);
			$sth->execute();
			$user = $sth->fetch(PDO::FETCH_ASSOC);
			if($user!=''){
				$this->user_data = $user;
				$this->logged_in = true;
			}
		}else{
			$this->logOut();
		}
	}

	public function login($data){
		global $db, $settings;
		if($settings['check_ip_user']){
			$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'session_user WHERE code=:code AND ip=:ip LIMIT 1');
			$sth->bindValue(':ip', getClientIp(), PDO::PARAM_STR);
		}else{
			$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'session_user WHERE code=:code LIMIT 1');
		}
		$sth->bindValue(':code', $data['session_code'], PDO::PARAM_STR);
		$sth->execute();
		if($sth->fetchColumn()){

			$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'users WHERE (username=:username or email=:username) AND password=:password LIMIT 1');
			$sth->bindValue(':username', $data['username'], PDO::PARAM_STR);
			$sth->bindValue(':password', $this->createPassword($data['password']), PDO::PARAM_STR);
			$sth->execute();

			$user = $sth->fetch(PDO::FETCH_ASSOC);

			if($user!=''){
				if($user['active']=='1'){
					if($user['username']==''){
						header("Location: ".path('login')."?complete_data=".$user['activation_code']);
						die('redirect');
					}

					$_SESSION['flash'] = 'login';

					$_SESSION['user']['id'] = $user['id'];
					$_SESSION['user']['session_code'] = $data['session_code'];

					$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'session_user` SET user_id=:user_id WHERE code=:code');
					$sth->bindValue(':user_id', $user['id'], PDO::PARAM_INT);
					$sth->bindValue(':code', $data['session_code'], PDO::PARAM_STR);
					$sth->execute();

					setcookie('user_id', $user['id'], time() + (86400 * 30), "/");
					setcookie('user_code', $data['session_code'], time() + (86400 * 30), "/");

					$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'logs_users`(`user_id`, `ip`, `date`) VALUES (:user_id,:ip,NOW())');
					$sth->bindValue(':user_id', $user['id'], PDO::PARAM_INT);
					$sth->bindValue(':ip', getClientIp(), PDO::PARAM_STR);
					$sth->execute();

					if(!empty($_GET['redirect'])){
						header("Location: ".$_GET['redirect']."");
					}else{
						header("Location: ".$settings['base_url']);
					}
					die('redirect');
				}else{
					showInfo('no_active');
					$this->removeSessionCode($data['session_code']);
				}
			}else{
				showInfo('data_incorrect');
				$this->removeSessionCode($data['session_code']);
			}
		}else{
			showInfo('session_error');
		}
	}

	public function checkCodeAndActivate($activation_code){
		global $db;
		$sth = $db->prepare('SELECT id FROM '._DB_PREFIX_.'users WHERE active=0 AND activation_code=:activation_code LIMIT 1');
		$sth->bindValue(':activation_code', $activation_code, PDO::PARAM_STR);
		$sth->execute();
		$id = $sth->fetch(PDO::FETCH_ASSOC)['id'];
		if($id){
			$this->activate($id);
			return true;
		}else{
			return false;
		}
	}

	public function getIdFromEmail($email){
		global $db;
		$id = 0;
		if($email){
			$sth = $db->prepare('SELECT id FROM '._DB_PREFIX_.'users WHERE email=:email LIMIT 1');
			$sth->bindValue(':email', $email, PDO::PARAM_STR);
			$sth->execute();
			$temp_id = $sth->fetch(PDO::FETCH_ASSOC);
			if($temp_id){$id = $temp_id['id'];}
		}
		return $id;
	}

	public function activate(int $id){
		global $db;
		$sth = $db->prepare('UPDATE '._DB_PREFIX_.'users SET active=1, activation_ip=:activation_ip, activation_date=NOW() WHERE id=:id LIMIT 1');
		$sth->bindValue(':activation_ip', getClientIp(), PDO::PARAM_STR);
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
	}

	public function setModerator(int $id){
		global $db;
		$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'users` SET moderator=1 WHERE id=:id limit 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
	}

	public function unSetModerator(int $id){
		global $db;
		$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'users` SET moderator=0 WHERE id=:id limit 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
	}

	public function newSessionCode(){
		global $db;
		$this->logOut();
		$session_code = md5(uniqid(rand(), true));
		$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'session_user`(`code`, `ip`, `date`) VALUES (:code,:ip,NOW())');
		$sth->bindValue(':code', $session_code, PDO::PARAM_STR);
		$sth->bindValue(':ip', getClientIp(), PDO::PARAM_STR);
		$sth->execute();
		return $session_code;
	}

	public function removeSessionCode($session_code){
		global $db;
		$sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'session_user` WHERE code=:code');
		$sth->bindValue(':code', $session_code, PDO::PARAM_STR);
		$sth->execute();
	}

	public function logOut(){
		global $db;
		$this->logged_in = false;
		if(!empty($_SESSION['user']['session_code'])){
			$sth = $db->prepare('DELETE FROM '._DB_PREFIX_.'session_user WHERE code=:code LIMIT 1');
			$sth->bindValue(':code', $_SESSION['user']['session_code'], PDO::PARAM_STR);
			$sth->execute();
		}
		unset($_SESSION['user']);
		setcookie("user_id", "", time() - 3600);
		setcookie("user_code", "", time() - 3600);
	}

	public function register($data){
		global $db, $settings;

		if($data['captcha']!=$_SESSION['captcha']){
			$error['captcha'] = lang('Invalid captcha code.');
		}else{
			if(checkEmailBlackList($data['email']) or checkIpBlackList(getClientIp())){
				$error['info'] = lang('The account could not be submitted');
			}else{
				if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL) or strlen($data['email'])>64) {
					$error['email'] = lang('Incorrect e-mail address.');
				}else{
					$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'users WHERE email=:email LIMIT 1');
					$sth->bindValue(':email', $data['email'], PDO::PARAM_STR);
					$sth->execute();
					if($sth->fetchColumn()){
						$error['email'] = lang('E-mail already exists in the database.');
					}
				}
				$old_username = $data['username'];
				$data['username'] = slugWithUpper(strip_tags($data['username']));
				if(!$data['username'] or strlen($data['username'])>64 or $old_username!=$data['username']){
					$error['username'] = lang('Invalid username.');
				}else{
					$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'users WHERE username=:username LIMIT 1');
					$sth->bindValue(':username', $data['username'], PDO::PARAM_STR);
					$sth->execute();
					if($sth->fetchColumn()){
						$error['username'] = lang('The selected username is already taken');
					}
				}
				if(!$data['password'] or strlen($data['password'])>32){
					$error['password'] = lang('The password is incorrect.');
				}elseif($data['password']!=$data['password_repeat']){
					$error['password'] = lang('Entered passwords are different');
				}
				if(!isset($data['rules'])){
					$error['rules'] = lang('This field is mandatory.');
				}
			}
		}

		if(isset($error)){
			return ['status'=>false,'error'=>$error];
		}else{

			$activation_code = md5(uniqid(rand(), true));

			if(sendMail('register',$data['email'],['activation_code'=>$activation_code, 'password'=>$data['password'], 'username'=>$data['username'], 'email'=>$data['email']])){
				$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'users`(`username`, `email`, `password`, `active`, `activation_code`, `register_ip`, `date`) VALUES (:username,:email,:password,0,:activation_code,:register_ip,NOW())');
				$sth->bindValue(':username', $data['username'], PDO::PARAM_STR);
				$sth->bindValue(':email', $data['email'], PDO::PARAM_STR);
				$sth->bindValue(':password', $this->createPassword($data['password']), PDO::PARAM_STR);
				$sth->bindValue(':activation_code', $activation_code, PDO::PARAM_STR);
				$sth->bindValue(':register_ip', getClientIp(), PDO::PARAM_STR);
				$sth->execute();
	
				return ['status'=>true];
			}else{
				$error['info'] = lang('The message was not sent');
				return ['status'=>false,'error'=>$error];
			}
		}
	}

	public function resetPassword($data){
		global $db, $settings;

		if($data['captcha']!=$_SESSION['captcha']){
			return ['status'=>false,'error'=>lang('Invalid captcha code.')];
		}else{
			$sth = $db->prepare('SELECT id, email, username FROM '._DB_PREFIX_.'users WHERE (username=:username or email=:username) LIMIT 1');
			$sth->bindValue(':username', strip_tags($data['username']), PDO::PARAM_STR);
			$sth->execute();
			$user_data = $sth->fetch(PDO::FETCH_ASSOC);
			if($user_data!=''){
				$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'reset_password WHERE active=1 AND date>(NOW() - INTERVAL 1 DAY) AND user_id=:user_id LIMIT 1');
				$sth->bindValue(':user_id', $user_data['id'], PDO::PARAM_INT);
				$sth->execute();
				if($sth->fetchColumn()){
					return ['status'=>false,'error'=>lang('Link to change your password has been sent.')];
				}else{
					$code = md5(uniqid(rand(), true));

					if(sendMail('reset_password',$user_data['email'], ['reset_password_code'=>$code, 'username'=>$user_data['username']])){
						$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'reset_password`(`user_id`, `active`, `code`, `date`) VALUES (:user_id,1,:code,NOW())');
						$sth->bindValue(':user_id', $user_data['id'], PDO::PARAM_INT);
						$sth->bindValue(':code', $code, PDO::PARAM_STR);
						$sth->execute();
	
						return ['status'=>true];
					}else{
						return ['status'=>false,'error'=>lang('The message was not sent')];
					}
				}
			}else{
				return ['status'=>false,'error'=>lang('Incorrect user data.')];
			}
		}
	}

	public function resetPasswordNew($code){
		global $db;
		$sth = $db->prepare('SELECT user_id FROM '._DB_PREFIX_.'reset_password WHERE active=1 AND date>(NOW() - INTERVAL 1 DAY) AND code=:code LIMIT 1');
		$sth->bindValue(':code', $code, PDO::PARAM_STR);
		$sth->execute();
		$user_id = $sth->fetch(PDO::FETCH_ASSOC);
		if($user_id!=''){
			return $user_id;
		}
		return false;
	}

	public function resetPasswordNewCheck(int $user_id,$data,$code){
		global $db, $settings;

		if($data['password']!=$data['password_repeat']){
			$error = lang('Entered passwords are different');
		}elseif($data['password']=='' or strlen($data['password'])>32){
			$error = lang('The password is incorrect.');
		}
		if(isset($error)){
			return ['status'=>false,'error'=>$error];
		}else{
			$sth = $db->prepare('UPDATE '._DB_PREFIX_.'reset_password set used=1, active=0 WHERE code=:code LIMIT 1');
			$sth->bindValue(':code', $code, PDO::PARAM_STR);
			$sth->execute();

			$sth = $db->prepare('UPDATE '._DB_PREFIX_.'users set password=:password WHERE id=:id LIMIT 1');
			$sth->bindValue(':password', $this->createPassword($data['password']), PDO::PARAM_STR);
			$sth->bindValue(':id', $user_id, PDO::PARAM_INT);
			$sth->execute();

			return ['status'=>true];
		}
	}

	public function createPassword($text){
		return md5($text._PASS_HASH_);
	}

	public function checkCompleteData($code){
		global $db;
		$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'users WHERE ISNULL(username) AND active=1 AND activation_code=:code LIMIT 1');
		$sth->bindValue(':code', $code, PDO::PARAM_STR);
		$sth->execute();
		if($sth->fetchColumn()){
			return true;
		}
		return false;
	}

	public function completeData($code,$data){
		global $db, $settings;
		$old_username = $data['username'];
		$data['username'] = slugWithUpper(strip_tags($data['username']));
		if(!$data['username'] or strlen($data['username'])>64 or $old_username!=$data['username']){
			$error = lang('Invalid username.');
		}else{
			$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'users WHERE username=:username LIMIT 1');
			$sth->bindValue(':username', $data['username'], PDO::PARAM_STR);
			$sth->execute();
			if($sth->fetchColumn()){
				$error= lang('The selected username is already taken');
			}
		}
		if(isset($error)){
			return ['status'=>false,'error'=>$error];
		}else{
			$sth = $db->prepare('UPDATE '._DB_PREFIX_.'users SET username=:username WHERE active=1 AND activation_code=:code AND ISNULL(username) LIMIT 1');
			$sth->bindValue(':username', $data['username'], PDO::PARAM_STR);
			$sth->bindValue(':code', $code, PDO::PARAM_STR);
			$sth->execute();

			$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'users WHERE username=:username LIMIT 1');
			$sth->bindValue(':username', $data['username'], PDO::PARAM_STR);
			$sth->execute();
			$user = $sth->fetch(PDO::FETCH_ASSOC);

			$session_code = md5(uniqid(rand(), true));

			$_SESSION['user']['id'] = $user['id'];
			$_SESSION['user']['session_code'] = $session_code;

			$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'session_user`(`user_id`, `code`, `ip`, `date`) VALUES (:user_id,:code,:ip,NOW())');
			$sth->bindValue(':user_id', $user['id'], PDO::PARAM_STR);
			$sth->bindValue(':code', $session_code, PDO::PARAM_STR);
			$sth->bindValue(':ip', getClientIp(), PDO::PARAM_STR);
			$sth->execute();

			setcookie('user_id', $user['id'], time() + (86400 * 30), "/");
			setcookie('user_code', $session_code, time() + (86400 * 30), "/");

			$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'logs_users`(`user_id`, `ip`, `date`) VALUES (:user_id,:ip,NOW())');
			$sth->bindValue(':user_id', $user['id'], PDO::PARAM_INT);
			$sth->bindValue(':ip', getClientIp(), PDO::PARAM_STR);
			$sth->execute();

			return ['status'=>true];
		}
	}

	public function getAllData(){
		global $db;

		$sth = $db->prepare('SELECT count(1) FROM '._DB_PREFIX_.'files WHERE user_id=:user_id');
		$sth->bindValue(':user_id', $this->id, PDO::PARAM_INT);
		$sth->execute();
		$this->user_data['number_files'] = $sth->fetchColumn();

		$sth = $db->prepare('SELECT count(1) FROM '._DB_PREFIX_.'files WHERE user_id=:user_id AND waiting_room = 1');
		$sth->bindValue(':user_id', $this->id, PDO::PARAM_INT);
		$sth->execute();
		$this->user_data['number_files_waiting_room'] = $sth->fetchColumn();

		$sth = $db->prepare('SELECT count(1) FROM '._DB_PREFIX_.'logs_users WHERE user_id=:user_id');
		$sth->bindValue(':user_id', $this->id, PDO::PARAM_INT);
		$sth->execute();
		$this->user_data['number_login'] = $sth->fetchColumn();

		$sth = $db->prepare('SELECT date FROM '._DB_PREFIX_.'logs_users WHERE user_id=:user_id order by date desc LIMIT 1,1');
		$sth->bindValue(':user_id', $this->id, PDO::PARAM_INT);
		$sth->execute();
		$this->user_data['last_login'] = $sth->fetch(PDO::FETCH_ASSOC)['date'];

		$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'reset_password WHERE user_id=:user_id AND used=1 order by date desc LIMIT 1');
		$sth->bindValue(':user_id', $this->id, PDO::PARAM_INT);
		$sth->execute();
		$this->user_data['last_reset_password'] = $sth->fetch(PDO::FETCH_ASSOC)['date'];
	}

	public static function getUsernameFromId(int $user_id){
		global $db;
		$username = '';
		if($user_id>0){
			$sth = $db->prepare('SELECT username FROM '._DB_PREFIX_.'users WHERE id=:user_id LIMIT 1');
			$sth->bindValue(':user_id', $user_id, PDO::PARAM_INT);
			$sth->execute();
			$username_temp = $sth->fetch(PDO::FETCH_ASSOC);
			if($username_temp){$username = $username_temp['username'];}
		}
		return $username;
	}

	public function changePassword($data){
		global $db;
		if($data['new_password']==$data['repeat_new_password']){
			$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'users WHERE id=:id AND password=:password LIMIT 1');
			$sth->bindValue(':id', $this->id, PDO::PARAM_INT);
			$sth->bindValue(':password', $this->createPassword($data['old_password']), PDO::PARAM_STR);
			$sth->execute();
			if($sth->fetchColumn()){
				$sth = $db->prepare('UPDATE '._DB_PREFIX_.'users SET password=:password WHERE id=:id LIMIT 1');
				$sth->bindValue(':id', $this->id, PDO::PARAM_INT);
				$sth->bindValue(':password', $this->createPassword($data['new_password']), PDO::PARAM_STR);
				$sth->execute();
				return ['status'=>true];
			}else{
				return ['status'=>false,'error'=>lang('Enter proper old password')];
			}
		}else{
			return ['status'=>false,'error'=>lang('Entered passwords are different')];
		}
	}

	public function getId(){
		if($this->logged_in){
			return $this->id;
		}
		return 0;
	}

	public function loginFB(){
		global $settings;
		$fb_email = '';
		if(!empty($_REQUEST['code'])){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/oauth/access_token?fields=email,name&client_id=".$settings['facebook_api']."&redirect_uri=".urlencode(path('login').'?facebook_login')."&client_secret=".$settings['facebook_secret']."&code=".$_REQUEST['code']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$fb_params = json_decode(curl_exec($ch));
			curl_close($ch);
			if(isset($fb_params->access_token)){
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/me?fields=email,name&access_token=".$fb_params->access_token);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);
				$fb_user = json_decode($output);
				if(isset($fb_user->email)){
					$fb_email = $fb_user->email;
				}
				curl_close($ch);
			}
		}
		if($fb_email){
			$this->loginByEmail($fb_email,'fb');
		}
	}

	public function loginGoogle(){
		global $settings;
		$google_email = '';
		if(!empty($_REQUEST['code'])){
			$url = 'https://accounts.google.com/o/oauth2/token';
			$curlPost = 'client_id='.$settings['google_id'].'&redirect_uri='.urlencode(path('login')).'&client_secret='.$settings['google_secret'].'&code='.$_REQUEST['code'].'&grant_type=authorization_code';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
			$data = json_decode(curl_exec($ch), true);
			if(!empty($data['access_token'])){
				$url = 'https://www.googleapis.com/oauth2/v2/userinfo?fields=email,verified_email';	
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer '.$data['access_token']]);
				$data2 = json_decode(curl_exec($ch), true);
				if(!empty($data2['email']) and !empty($data2['verified_email'])){
					$google_email = $data2['email'];
				}
			}
		}
		if($google_email){
			$this->loginByEmail($google_email,'google');
		}
	}

	public function loginByEmail($email,$source=''){
		global $db, $settings;

		if(checkEmailBlackList($email) or checkIpBlackList(getClientIp())){
			$error['info'] = lang('The account could not be submitted');
		}else{
			$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'users WHERE email=:email LIMIT 1');
			$sth->bindValue(':email', $email, PDO::PARAM_STR);
			$sth->execute();
			$user_data = $sth->fetch(PDO::FETCH_ASSOC);

			if($user_data!=''){

				if($user_data['active']=='0'){
					$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'users` SET active=1 WHERE id=:id');
					$sth->bindValue(':id', $user_data['id'], PDO::PARAM_INT);
					$sth->execute();
				}
				if($user_data['username']==''){
					header("Location: ".path('login')."?complete_data=".$user_data['activation_code']);
					die('redirect');
				}

				$session_code = md5(uniqid(rand(), true));

				$_SESSION['user']['id'] = $user_data['id'];
				$_SESSION['user']['session_code'] = $session_code;

				$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'session_user`(`user_id`, `code`, `ip`, `date`) VALUES (:user_id,:code,:ip,NOW())');
				$sth->bindValue(':user_id', $user_data['id'], PDO::PARAM_STR);
				$sth->bindValue(':code', $session_code, PDO::PARAM_STR);
				$sth->bindValue(':ip', getClientIp(), PDO::PARAM_STR);
				$sth->execute();

				setcookie('user_id', $user_data['id'], time() + (86400 * 30), "/");
				setcookie('user_code', $session_code, time() + (86400 * 30), "/");

				$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'logs_users`(`user_id`, `ip`, `date`) VALUES (:user_id,:ip,NOW())');
				$sth->bindValue(':user_id', $user_data['id'], PDO::PARAM_INT);
				$sth->bindValue(':ip', getClientIp(), PDO::PARAM_STR);
				$sth->execute();

				if(isset($_GET['redirect']) and $_GET['redirect']!=''){
					header("Location: ".$_GET['redirect']."");
				}else{
					header("Location: ".$settings['base_url']);
				}
				die('redirect');
			}else{

				$activation_code = md5(uniqid(rand(), true));
				$password = randomPassword();
				$register_fb = $register_google = 0;

				if($source=='fb'){
					sendMail('register_fb',$email,['activation_code'=>$activation_code, 'password'=>$password, 'email'=>$email]);
					$register_fb = 1;
				}elseif($source=='google'){
					sendMail('register_google',$email,['activation_code'=>$activation_code, 'password'=>$password, 'email'=>$email]);
					$register_google = 1;
				}

				$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'users`(`active`, `email`, `password`, `activation_code`, `register_fb`, `register_google`, `register_ip`, `activation_date`, `activation_ip`, `date`) VALUES (1, :email,:password,:activation_code,:register_fb,:register_google,:register_ip,NOW(), :activation_ip, NOW())');
				$sth->bindValue(':email', strip_tags($email), PDO::PARAM_STR);
				$sth->bindValue(':password', $this->createPassword($password), PDO::PARAM_STR);
				$sth->bindValue(':activation_code', $activation_code, PDO::PARAM_STR);
				$sth->bindValue(':register_fb', $register_fb, PDO::PARAM_INT);
				$sth->bindValue(':register_google', $register_google, PDO::PARAM_INT);
				$sth->bindValue(':register_ip', getClientIp(), PDO::PARAM_STR);
				$sth->bindValue(':activation_ip', getClientIp(), PDO::PARAM_STR);
				$sth->execute();

				header("Location: ".path('login')."?complete_data=".$activation_code);
				die('redirect');
			}
		}
	}

	public function getProfile($username){
		global $db;
		$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'users WHERE active=1 AND username=:username LIMIT 1');
		$sth->bindValue(':username', $username, PDO::PARAM_STR);
		$sth->execute();
		$profile = $sth->fetch(PDO::FETCH_ASSOC);
		if($profile){

			$sth = $db->prepare('SELECT count(1) FROM '._DB_PREFIX_.'files WHERE user_id=:user_id');
			$sth->bindValue(':user_id', $profile['id'], PDO::PARAM_INT);
			$sth->execute();
			$profile['number_files'] = $sth->fetchColumn();

			$sth = $db->prepare('SELECT count(1) FROM '._DB_PREFIX_.'files WHERE user_id=:user_id AND waiting_room = 1');
			$sth->bindValue(':user_id', $profile['id'], PDO::PARAM_INT);
			$sth->execute();
			$profile['number_files_waiting_room'] = $sth->fetchColumn();

			$sth = $db->prepare('SELECT count(1) FROM '._DB_PREFIX_.'logs_users WHERE user_id=:user_id');
			$sth->bindValue(':user_id', $profile['id'], PDO::PARAM_INT);
			$sth->execute();
			$profile['number_login'] = $sth->fetchColumn();

			$sth = $db->prepare('SELECT date FROM '._DB_PREFIX_.'logs_users WHERE user_id=:user_id order by date desc LIMIT 1,1');
			$sth->bindValue(':user_id', $profile['id'], PDO::PARAM_INT);
			$sth->execute();
			$profile['last_login'] = $sth->fetch(PDO::FETCH_ASSOC)['date'];

		}
		return ($profile);
	}

	public function getData(int $id){
		global $db;
		$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'users WHERE id=:id LIMIT 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetch(PDO::FETCH_ASSOC);
	}

	public function remove(int $id){
		global $db;
		$sth = $db->prepare('SELECT id FROM '._DB_PREFIX_.'files WHERE user_id=:user_id');
		$sth->bindValue(':user_id', $id, PDO::PARAM_INT);
		$sth->execute();
		foreach($sth as $row){;
			files::remove($row['id']);
		}
		$sth = $db->prepare('DELETE FROM '._DB_PREFIX_.'reset_password WHERE user_id=:user_id');
		$sth->bindValue(':user_id', $id, PDO::PARAM_INT);
		$sth->execute();
		$sth = $db->prepare('DELETE FROM '._DB_PREFIX_.'session_user WHERE user_id=:user_id');
		$sth->bindValue(':user_id', $id, PDO::PARAM_INT);
		$sth->execute();
		$sth = $db->prepare('DELETE FROM '._DB_PREFIX_.'users WHERE id=:id LIMIT 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
	}
}
