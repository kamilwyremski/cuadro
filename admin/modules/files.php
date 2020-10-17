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

if($admin->is_logged()){

	if(!_ADMIN_TEST_MODE_ and isset($_POST['action'])){
		if($_POST['action']=='remove_file' and isset($_POST['id']) and $_POST['id']>0){
			if(isset($_POST['add_ip_black_list']) and !empty($_POST['ip'])){
				addIpToBlackList($_POST['ip']);
			}
			files::remove($_POST['id']);
			$render_variables['alert_danger'][] = lang('The file has been deleted');
		}elseif($_POST['action']=='set_main_page' and isset($_POST['id']) and $_POST['id']>0){
			files::setMainPage($_POST['id']);
			$render_variables['alert_success'][] = lang('Changes have been saved');
		}elseif($_POST['action']=='remove_files' and isset($_POST['files']) and is_array($_POST['files'])){
			foreach($_POST['files'] as $key => $value){
				if($value>0){
					files::remove($value);
				}
			}
			$render_variables['alert_danger'][] = lang('The file has been deleted');
		}elseif($_POST['action']=='set_main_page_files' and isset($_POST['files']) and is_array($_POST['files'])){
			foreach($_POST['files'] as $key => $value){
				if($value>0){
					files::setMainPage($value);
				}
			}
			$render_variables['alert_success'][] = lang('Changes have been saved');
		}
	}

	$render_variables['files'] = files::getFiles(50,'admin');

	$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'users where active = 1 order by username');
	foreach($sth as $row){$users[] = $row;}
	if(isset($users)){$render_variables['users'] = $users;}
	
	$title = lang('Files').' - '.$title_default;

}
