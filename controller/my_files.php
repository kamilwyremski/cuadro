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
		if($_POST['action']=='remove_file' and isset($_POST['id']) and $_POST['id']>0){
			if(files::checkPermissions($_POST['id'])){
				files::remove($_POST['id']);
				$_SESSION['flash'] = 'file_removed';
			}
		}
	}

	$render_variables['files'] = files::getFiles($settings['limit_page_profile'],'my_files');

	$render_variables['boxes'] = boxes::getBoxes();

	$settings['title'] = lang('My files').' - '.$settings['title'];
	$settings['description'] = lang('My files').' - '.$settings['description'];
	
}else{
	header("Location: ".path('login')."?redirect=".path('my_files'));
	die('redirect');
}


