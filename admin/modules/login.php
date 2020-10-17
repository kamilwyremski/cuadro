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

if(!isset($settings['base_url'])){
	die('Access denied!');
}

if(isset($_POST['action']) and $_POST['action'] == 'login' and !empty($_POST['session_code']) and !empty($_POST['username']) and !empty($_POST['password'])){
	$result = $admin->login($_POST);
	if($result['status']){
		header('Location: '.$_SERVER['REQUEST_URI']);
		die('redirect');
	}elseif(!empty($result['error'])){
		$render_variables['alert_danger'][] = $result['error'];
	}
}

if(!$admin->is_logged()){
	$render_variables['session_code'] = $admin->newSessionCode();
}
