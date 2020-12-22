<?php
/************************************************************************
 * The script of website with pictures and movies CUADRO
 * Copyright (c) 2018 - 2021 by IT Works Better https://itworksbetter.net
 * Project by Kamil Wyremski https://wyremski.pl
 * 
 * All right reserved
 *
 * *********************************************************************
 * THIS SOFTWARE IS LICENSED - YOU CAN MODIFY THESE FILES BUT YOU CAN NOT REMOVE OF ORIGINAL COMMENTS!
 * ACCORDING TO THE LICENSE YOU CAN USE THE SCRIPT ON ONE DOMAIN.
 * *********************************************************************/

if(!isset($settings['base_url'])){
	die('Access denied!');
}

$sth = $db->prepare('select * from '._DB_PREFIX_.'info where page="contact" limit 1');
$sth->execute();
$render_variables['contact_page'] = $sth->fetch(PDO::FETCH_ASSOC);

if(!empty($_POST['action']) and $_POST['action']=='send_message' and !empty($_POST['name']) and !empty($_POST['email']) and filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) and !empty($_POST['message']) and !empty($_POST['captcha']) and (isset($_POST['rules']) or $user->getId())){

	if($_POST['captcha']!=$_SESSION['captcha']){
		$render_variables['alert_danger'][] = lang('The message was not sent');
		$render_variables['alert_danger'][] = lang('Invalid captcha code.');
		$render_variables['input'] = ['name'=>$_POST['name'], 'email'=>$_POST['email'], 'message'=>$_POST['message']];
	}else{
		if(sendMail('contact_form',$settings['email'],['name'=>$_POST['name'], 'email'=>$_POST['email'], 'message'=>strip_tags($_POST['message']), 'user_id'=>$user->getId()])){
			$render_variables['alert_success'][] = lang('The message was correctly sent');
		}else{
			$render_variables['alert_danger'][] = lang('The message was not sent');
		}
	}
}

$settings['title'] = lang('Contact us').' - '.$settings['title'];
$settings['description'] = lang('Contact us').' - '.$settings['description'];
