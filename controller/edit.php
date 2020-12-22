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

$controller = 'add';
include_once('controller/'.$controller.'.php');
	
if(isset($_GET['id']) and $_GET['id']>0 and files::checkPermissions($_GET['id'])){

	if(isset($_POST['action']) and $_POST['action']=='edit_file' and !empty($_POST['title'])){
	
		$result = files::edit($_POST,$_GET['id']);
		if(!empty($result['error'])){
			$render_variables['alert_danger'][] = $result['error'];
			$render_variables['file'] = $_POST;
		}elseif($result['status'] and !empty($result['path'])){
			$_SESSION['flash'] = 'file_saved';
			header('Location: '.$result['path']);
			die('redirect');
		}
	}

	$file = files::getFile($_GET['id'], 'edit');

	if(!empty($file['tags'])){
		$file['tags'] = implode(', ', array_map(function ($entry) {
			return $entry['name'];
		}, $file['tags']));
	}

	$render_variables['file'] = $file;
	$render_variables['edit_file'] = true;	

	$settings['title'] = lang('Edit file').' - '.$settings['title'];
	$settings['description'] = lang('Edit file').' - '.$settings['description'];
}else{
	header("Location: ".path('add'));
	die('redirect');
}

