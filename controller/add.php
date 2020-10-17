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

if(!$settings['add_file_not_logged'] and !$user->logged_in){
	$_SESSION['flash'] = 'login_to_add';
	header('Location: '.path('login').'?redirect='.path('add'));
	die('redirect');
}

if(isset($_POST['action']) and $_POST['action']=='add_new_file' and !empty($_POST['title']) and !empty($_POST['type'])){
	if(($_POST['type']=='image_disk' and !empty($_FILES['image_disk']['name'])) or 
	   ($_POST['type']=='video_youtube' and !empty($_POST['video_youtube'])) or 
	   ($_POST['type']=='video_vimeo' and !empty($_POST['video_vimeo'])) or 
	   ($_POST['type']=='video_dailymotion' and !empty($_POST['video_dailymotion'])) or 
	   ($_POST['type']=='video_mp4' and !empty($_FILES['video_mp4']['name']))
	){
		$result = files::add($_POST);
		if(!empty($result['error'])){
			$render_variables['alert_danger'][] = $result['error'];
			$render_variables['file'] = $_POST;
		}elseif($result['status'] and !empty($result['path'])){
			$_SESSION['flash'] = 'file_added';
            header('Location: '.$result['path']);
            die('redirect');
		}
	}
}

if(!$user->getId()){
	$render_variables['alert_danger'][] = lang('You are not logged in - you will not be able to edit the file');
}

$settings['title'] = lang('Add new file').' - '.$settings['title'];
$settings['description'] = lang('Add new file').' - '.$settings['description'];