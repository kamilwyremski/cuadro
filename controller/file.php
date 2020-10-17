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

if(isset($_GET['id']) and $_GET['id']>0 and !empty($_GET['slug'])){

	if(isset($_POST['action'])){
		if($_POST['action']=='remove_file' and isset($_POST['id']) and $_POST['id']>0){
			if(files::checkPermissions($_POST['id'])){
				files::remove($_POST['id']);
				$_SESSION['flash'] = 'file_removed';
				header('Location: '.$settings['base_url']);
          		die('redirect');
			}
		}elseif($_POST['action']=='set_main_page' and isset($_POST['id']) and $_POST['id']>=0 and $user->moderator){
			if(files::checkPermissions($_POST['id'])){
				files::setMainPage($_POST['id']);
				$_SESSION['flash'] = 'file_main_page';
			}
		}
	}

	$file = files::getFile($_GET['id']);

	if($file){
		if($file['slug']!=$_GET['slug']){
			header('Location: '.path('file',$file['id'],$file['slug']));
            die('redirect');
		}

		$file['view_all']++;
		if(!$file['view_unique']){
			$file['view_unique'] = 1;
		}

		$render_variables['file'] = $file;

		$render_variables['boxes'] = boxes::getBoxes();

		$settings['title'] = $file['title'].' - '.$settings['title'];
		$settings['description'] = $file['description'];
		
		if($file['type']=='image'){
			$settings['logo_facebook'] = $settings['base_url'].'/upload/files/'.$file['url'];
        }elseif($file['type']=='iframe'){
            $settings['logo_facebook'] = $file['thumb'];
        }		
	}else{
		include('controller/404.php');
	}
}else{
	include('controller/404.php');
}

