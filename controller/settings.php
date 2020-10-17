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

if($user->logged_in){
	
	if(isset($_POST['action'])){
		if($_POST['action']=='change_password' and !empty($_POST['old_password']) and !empty($_POST['new_password']) and !empty($_POST['repeat_new_password'])){
			
			$result = $user->changePassword($_POST);
			if($result['status']){
				$render_variables['alert_success'][] = lang('The password has been correctly updated');
			}elseif(!empty($result['error'])){
				$render_variables['alert_danger'][] = $result['error'];
			}
		}
	}

	$user->getAllData();

	$settings['title'] = lang('Settings').' - '.$settings['title'];
	$settings['description'] = lang('Settings').' - '.$settings['description'];
}else{
	header("Location: ".path('login')."?redirect=".path('settings'));
	die('redirect');
}

