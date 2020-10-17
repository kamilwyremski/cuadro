<?php
/************************************************************************
 * The script of website with pictures and movies CUADRO
 * Copyright (c) 2018 - 2020 by IT Works Better https://itworksbetter.net
 * Project by Kamil Wyremski https://wyremski.pl
 * 
 * All right reserved
 *
 * *********************************************************************
 * THIS SOFTWARE IS LICENSED - YOU CAN MODIFY THESE FILES BUT YOU CAN NOT REMOVE OF ORIGINAL COMMENTS!
 * ACCORDING TO THE LICENSE YOU CAN USE THE SCRIPT ON ONE DOMAIN.
 * *********************************************************************/

function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = [];
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}

function getFullUrl($address){
	global $settings;
	if(substr($address, 0, 2) != "//" and substr($address, 0, 7) != "http://" and substr($address, 0, 8) != "https://" and $address !='') {
		if(substr($address, 0, 1)!='/'){
			$address = '/'.$address;
		}
		$address = $settings['base_url'].$address;
	}
	return $address;
}

function checkWordsBlackList($content){
	global $settings;
	$filtered_text = $content;
	if($settings['black_list_words']){
		$filter_terms = array_map('trim', array_filter(explode(',', $settings['black_list_words'])));
		foreach($filter_terms as $word){
			$match_count = preg_match_all('/' . $word . '/i', $content, $matches);
			for($i = 0; $i < $match_count; $i++){
				$bwstr = trim($matches[0][$i]);
				$filtered_text = preg_replace('/\b' . $bwstr . '\b/', str_repeat("*", strlen($bwstr)), $filtered_text);
			}
		}
	}
    return $filtered_text;
}


function addEmailToBlackList($email){
	global $db, $settings;
	if($email){
		$black_list_email = array_map('trim', array_filter(explode(PHP_EOL, $settings['black_list_email'])));
		array_push($black_list_email,$email);
		asort($black_list_email);
		$black_list_email = implode(array_unique($black_list_email),PHP_EOL);
		$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'settings` SET value=:value WHERE name="black_list_email" limit 1');
		$sth->bindValue(':value', $black_list_email, PDO::PARAM_STR);
		$sth->execute();
	}
}

function addIpToBlackList($ip){
	global $db, $settings;
	if($ip){
		$black_list_ip = array_map('trim', array_filter(explode(PHP_EOL, $settings['black_list_ip'])));
		array_push($black_list_ip,$ip);
		asort($black_list_ip);
		$black_list_ip = implode(array_unique($black_list_ip),PHP_EOL);
		$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'settings` SET value=:value WHERE name="black_list_ip" limit 1');
		$sth->bindValue(':value', $black_list_ip, PDO::PARAM_STR);
		$sth->execute();
	}
}

function getTags(int $limit){
	global $db;
	$tags = [];
	$sth = $db->query('SELECT *, (SELECT COUNT(1) FROM '._DB_PREFIX_.'tags_files where tag_id = t.id) as amount FROM '._DB_PREFIX_.'tags t ORDER BY rand() LIMIT '.$limit);
	while($row = $sth->fetch(PDO::FETCH_ASSOC)){$tags[] = $row;}
	return $tags;
}

function getTagBySlug($slug){
	global $db;
	$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'tags WHERE slug=:slug LIMIT 1');
	$sth->bindValue(':slug', $slug, PDO::PARAM_STR);
	$sth->execute();
	return $sth->fetch(PDO::FETCH_ASSOC);
}

function getCategoryBySlug($slug){
	global $db;
	$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'categories WHERE slug=:slug LIMIT 1');
	$sth->bindValue(':slug', $slug, PDO::PARAM_STR);
	$sth->execute();
	return $sth->fetch(PDO::FETCH_ASSOC);
}

function getCategories(){
	global $db;
	$categories = [];
	$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'categories ORDER BY position');
	foreach($sth as $row){$categories[] = $row;}
	return $categories;
}

function mailsQueueAdd($action,$receiver,$data='',int $priority=0){
	global $db;
	if($action && $receiver){
		$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'mails_queue`(`receiver`, `action`, `data`, `priority`) VALUES (:receiver,:action,:data,:priority)');
		$sth->bindValue(':receiver', $receiver, PDO::PARAM_STR);
		$sth->bindValue(':action', $action, PDO::PARAM_STR);
		$sth->bindValue(':data', serialize($data), PDO::PARAM_STR);
		$sth->bindValue(':priority', $priority, PDO::PARAM_INT);
		$sth->execute();
		return true;
	}
	return false;
}

function checkEmailBlackList($email){
	global $settings;
	if($email and $settings['black_list_email'] and in_array($email,explode(PHP_EOL, $settings['black_list_email']))){
		return true;
	}else{
		return false;
	}
}

function checkIpBlackList($ip){
	global $settings;
	if($ip and $settings['black_list_ip'] and in_array($ip,explode(PHP_EOL, $settings['black_list_ip']))){
		return true;
	}else{
		return false;
	}
}

function showInfo($info){
	global $render_variables;
	switch ($info) {
		case 'new_account':
			$render_variables['alert_success'][] = lang('The account has been established. To your e mail was sent message with an activation code');
			break;
		case 'no_active':
			$render_variables['alert_danger'][] = lang('The account has not been activated yet.');
			break;
		case 'data_incorrect':
			$render_variables['alert_danger'][] = lang('Username or password are incorrect');
			break;
		case 'session_error':
			$render_variables['alert_danger'][] = lang('Session error');
			break;
		case 'login_to_add':
			$render_variables['alert_danger'][] = lang('You must log in to add a new file');
			break;
		case 'file_added':
			$render_variables['alert_success'][] = lang('The file has been correctly added!');
			break;
		case 'file_saved':
			$render_variables['alert_success'][] = lang('The file has been correctly saved!');
			break;
		case 'file_main_page':
			$render_variables['alert_success'][] = lang('The file has been added to main page!');
			break;
		case 'file_removed':
			$render_variables['alert_danger'][] = lang('The file has been deleted');
			break;
		case 'login':
			$render_variables['alert_success'][] = lang('You have successfully logged in');
			break;
		case 'logout':
			$render_variables['alert_danger'][] = lang('You have successfully logged out');
			break;
	}
}

function checkInfo(){
	if(!empty($_SESSION['flash'])){
		showInfo($_SESSION['flash']);
		unset($_SESSION['flash']);
	}
}

function path($controller,int $id=0,$slug=''){
	global $links, $settings;
	if($controller=='file'){
		return $settings['base_url'].'/'.$id.','.$slug;
	}elseif(isset($links[$controller])){
		if($controller=='edit' or ($controller=='info' and $id and $slug)){
			return $settings['base_url'].'/'.$links[$controller].'/'.$id.','.$slug;
		}elseif($controller=='profile' or $controller=='category' or $controller=='tag' or $controller=='profile_files'){
			if(!$slug and !empty($_GET['slug'])){
				return $settings['base_url'].'/'.$links[$controller].'/'.$_GET['slug'];
			}
			return $settings['base_url'].'/'.$links[$controller].'/'.$slug;
		}
		return $settings['base_url'].'/'.$links[$controller];
	}elseif($controller=='rules'){
		return $settings['base_url'].'/'.$links['info'].'/2,'.$settings['url_rules'];
	}elseif($controller=='privacy_policy'){
		return $settings['base_url'].'/'.$links['info'].'/1,'.$settings['url_privacy_policy'];
	}else{
		return $settings['base_url'];
	}
}

function lang($text){
	global $translate;
	if(isset($translate[$text])){
		return ($translate[$text]);
	}else{
		return ($text);
	}
}

function langList(){
	$files = array_diff(scandir(realpath(dirname(__FILE__)).'/../config/langs/'), ['.', '..']);
	foreach($files as $key=>$file){
		$path_parts = pathinfo($file);
		if($path_parts['extension']=='php'){
			$langList[] = $path_parts['filename'];
		}
	}
	return($langList);
}

function langLoad($lang='en'){
	global $translate, $links;
	if(!in_array($lang, langList())){$lang = 'en';}
	require_once(realpath(dirname(__FILE__)).'/../config/langs/'.$lang.'.php');
	return $lang;
}

function paginationPageFrom(int $limit){
	$limit_start = 0;
	if(isset($_GET['page']) and is_numeric($_GET['page']) and $_GET['page']>0){
		$limit_start = ($_GET['page']-1)*$limit;
	}
	return $limit_start;
}

function generatePagination(int $limit){
	global $render_variables, $db;
	$limit_start = paginationPageFrom($limit);
	$page_number = 1;
	if(isset($_GET['page']) and is_numeric($_GET['page']) and $_GET['page']>0){
		$page_number = $_GET['page'];
	}

	$sth = $db->query('SELECT FOUND_ROWS()');
	$page_count = ceil($sth->fetch(PDO::FETCH_ASSOC)['FOUND_ROWS()']/$limit);

	if($page_number<6){
		$pagination['page_start'] = 1;
	}else{
		$pagination['page_start'] =  $page_number-4;
	}

	$gets_admin = $gets = $_GET;
	unset($gets['page'],$gets['controller'],$gets['slug'],$gets['id']);
	$gets_admin = $gets;
	$page_url['page_admin'] = http_build_query($gets);
	unset($gets_admin['sort'], $gets_admin['sort_desc']);
	$page_url['sort_admin'] = http_build_query($gets_admin);
	$page_url['page'] = http_build_query($gets);
	unset($gets['sort']);
	$page_url['sort'] = $gets;

	$pagination['page_url'] = $page_url;
	$pagination['page_count'] = $page_count;
	$pagination['page_number'] = $page_number;
	$pagination['limit_start'] = $limit_start;

	$render_variables['pagination'] = $pagination;
}

function sortBy($sort=' id desc '){
	if(!empty($_GET['sort'])){
		$sort = filter($_GET['sort']);
		if(isset($_GET['sort_desc'])){
			$sort .= ' desc ';
		}
	}
	return $sort;
}

function webAddress($address){
	if(substr($address, 0, 7) != "http://" and substr($address, 0, 8) != "https://" and $address !='') {
		$address = 'http://'.$address;
	}
	if(substr($address, -1)=='/'){
		$address = substr($address,0,-1);
	}
	return $address;
}

function filter($string){
  return trim(stripslashes($string));
}

function slugWithUpper($string){
	$string = str_replace(array(' ','%','$',':','–',',','/','=','?','Ę','Ó','Ą','Ś','Ł','Ż','Ź','Ć','Ń','ę','ó','ą','ś','ł','ż','ź','ć','ń'), array('-','-','','','','','','','','E','O','A','S','L','Z','Z','C','N','e','o','a','s','l','z','z','c','n'), $string);
	$string = iconv('UTF-8', 'ASCII//IGNORE//TRANSLIT', $string);
	$string = preg_replace('/[^a-zA-Z0-9-_]+/', '', $string);
	return $string;
}

function slug($string){
	$string = strtolower(slugWithUpper($string));
	return $string;
}

function isSlug($string){
	if($string == slug($string)){
		return true;
	}
	return false;
}

function getClientIp() {
	if(!empty($_SERVER['HTTP_CLIENT_IP'])){
		return $_SERVER['HTTP_CLIENT_IP'];
	}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	}elseif(!empty($_SERVER['REMOTE_ADDR'])){
		return $_SERVER['REMOTE_ADDR'];
	}else{
		return 'SERVER';
	}
}

function arrangeAlphabetically($table, $condition = 'true'){
	global $db;
	$position = 1;
	$sth = $db->query('SELECT id FROM '._DB_PREFIX_.$table.' WHERE '.$condition.' ORDER BY name');
	foreach($sth as $row){
		$db->query('UPDATE '._DB_PREFIX_.$table.' SET position='.$position.' WHERE id='.$row['id'].' LIMIT 1');
		$position++;
	}
}

function getPosition($table, $condition = 'true'){
	global $db;
	$sth = $db->query('SELECT position FROM '._DB_PREFIX_.$table.' WHERE '.$condition.' ORDER BY position DESC LIMIT 1');
	$pos = $sth->fetch(PDO::FETCH_ASSOC);
	if(!empty($pos)){
		return $pos['position']+1;
	}else{
		return 1;
	}
}

function setPosition($table, int $id, int $position, $plusminus, $additional_condition = 'true'){
	global $db;
	if($table and $id>0 and $position>0){
		if($plusminus=='+'){$condition = '<'; $sort = 'desc';}else{$condition = '>'; $sort = 'asc';}
		$sth = $db->query('SELECT id, position FROM '._DB_PREFIX_.$table.' WHERE position '.$condition.' '.$position.' AND '.$additional_condition.' ORDER BY position '.$sort.' LIMIT 1');
		$pos = $sth->fetch(PDO::FETCH_ASSOC);
		if($pos){
			$sth = $db->query('UPDATE '._DB_PREFIX_.$table.' SET position='.$pos['position'].' WHERE id='.$id.' LIMIT 1');
			$sth = $db->query('UPDATE '._DB_PREFIX_.$table.' SET position='.$position.' WHERE id='.$pos['id'].' LIMIT 1');
		}
	}
}

function sendMail($type,$email,$data=''){
	global $settings, $mail, $db, $user;
	$mail_sent = false;

	if($type!='' and $email!=''){

		if($settings['smtp']){
			require_once(realpath(dirname(__FILE__)).'/../config/smtp.php');
		}

		if($type=='mailing' or $type=='test'){
			$mail_content = ['subject'=>$data['subject'],'message'=>$data['message']];
		}else{
			$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'mails WHERE name=:name limit 1');
			$sth->bindParam(':name', $type, PDO::PARAM_STR);
			$sth->execute();
			$mail_content = $sth->fetch(PDO::FETCH_ASSOC);
		}

		if($mail_content){

			$header = 'Reply-To: <'.$settings['email']."> \r\n";
			$message = '<!doctype html><html lang="'.$settings['lang'].'"><head><meta charset="utf-8">'.$mail_content['message'].'</head><body>';
			$subject = $mail_content['subject'];
			$ip = getClientIp();

			$message = str_replace("{title}",$settings['title'],$message);
			$subject = str_replace("{title}",$settings['title'],$subject);
			$message = str_replace("{base_url}",$settings['base_url'],$message);
			$subject = str_replace("{base_url}",$settings['base_url'],$subject);
			if($settings['logo']){
				$message = str_replace("{link_logo}",'<img src="'.getFullUrl($settings['logo']).'" style="max-width:300px; max-height: 200px">',$message);
				$subject = str_replace("{link_logo}",'<img src="'.getFullUrl($settings['logo']).'" style="max-width:300px; max-height: 200px">',$subject);
			}else{
				$message = str_replace("{link_logo}",'',$message);
				$subject = str_replace("{link_logo}",'',$subject);
			}
			$message = str_replace("{date}",date("Y-m-d"),$message);
			$subject = str_replace("{date}",date("Y-m-d"),$subject);
			if(isset($data['username'])){
				$message = str_replace("{username}",$data['username'],$message);
				$subject = str_replace("{username}",$data['username'],$subject);
			}
			if(isset($data['activation_code'])){
				$message = str_replace("{activation_link}",path('login').'?activation_code='.$data['activation_code'],$message);
				$subject = str_replace("{activation_link}",path('login').'?activation_code='.$data['activation_code'],$subject);
			}
			if(isset($data['password'])){
				$message = str_replace("{password}",$data['password'],$message);
				$subject = str_replace("{password}",$data['password'],$subject);
			}
			if(isset($data['reset_password_code'])){
				$message = str_replace("{reset_password_link}",path('login').'?new_password='.$data['reset_password_code'],$message);
				$subject = str_replace("{reset_password_link}",path('login').'?new_password='.$data['reset_password_code'],$subject);
			}
			if(isset($data['name'])){
				$message = str_replace("{name}",$data['name'],$message);
				$subject = str_replace("{name}",$data['name'],$subject);
			}
			if(isset($data['email'])){
				$header = 'Reply-To: <'.$data['email']."> \r\n";
				if($settings['smtp']){$mail->AddReplyTo($data['email']);}
				$message = str_replace("{email}",$data['email'],$message);
				$subject = str_replace("{email}",$data['email'],$subject);
			}
			if(isset($data['message'])){
				$message = str_replace("{message}",$data['message'],$message);
				$subject = str_replace("{message}",$data['message'],$subject);
			}

			$header .= 'From: '.$settings['email'].' <'.$settings['email'].">\r\n";
			$header .= "MIME-Version: 1.0 \r\n";

			if($settings['mail_attachment'] and isset($_FILES['attachment']) and $_FILES['attachment']['name']!=''){

				$file_tmp_name    = $_FILES['attachment']['tmp_name'];
				$file_name        = $_FILES['attachment']['name'];
				$file_size        = $_FILES['attachment']['size'];
				$file_type        = $_FILES['attachment']['type'];
				$file_error       = $_FILES['attachment']['error'];

				if($file_error>0 or $file_size>5000000){
					die('error - bad attachment');
				}

				$handle = fopen($file_tmp_name, "r");
				$content = fread($handle, $file_size);
				fclose($handle);
				$encoded_content = chunk_split(base64_encode($content));

				$boundary = md5("sanwebe");

				$header .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n";

				$body = "--$boundary\r\n";
				$body .= "Content-Type: text/html; charset=utf-8\r\n";
				$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
				$body .= chunk_split(base64_encode($message));

				//attachment
				$body .= "--$boundary\r\n";
				$body .="Content-Type: $file_type; name=\"$file_name\"\r\n";
				$body .="Content-Disposition: attachment; filename=\"$file_name\"\r\n";
				$body .="Content-Transfer-Encoding: base64\r\n";
				$body .="X-Attachment-Id: ".rand(1000,99999)."\r\n\r\n";
				$body .= $encoded_content;

			}else{
				$header .= "Content-Type: text/html; charset=UTF-8";
				$body = $message;
			}

			$subject = '=?utf-8?B?'.base64_encode($subject).'?=';

			if($settings['smtp']){
				$mail->Subject = $subject;
				$mail->Body = $message;
				if(isset($boundary)){
					$mail->AddAttachment($_FILES['attachment']['tmp_name'],$_FILES['attachment']['name']);
				}
				$mail->ClearAllRecipients();
				$mail->AddAddress($email);

				if($mail->Send()){
					$mail_sent = true;
				}
			}elseif(mail($email, $subject, $body, $header)){
				$mail_sent = true;
			}
		}
	}
	if($mail_sent){
		$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'logs_mails`(`receiver`, `action`, `content`, `ip`, `date`) VALUES (:receiver,:action,:content,:ip,NOW())');
		$sth->bindValue(':receiver', $email, PDO::PARAM_STR);
		$sth->bindValue(':action', $type, PDO::PARAM_STR);
		$sth->bindValue(':content', $body, PDO::PARAM_STR);
		$sth->bindValue(':ip', $ip, PDO::PARAM_STR);
		$sth->execute();
		return true;
	}else{
		return false;
	}
}
