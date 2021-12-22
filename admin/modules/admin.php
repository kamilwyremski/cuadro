<?php
/************************************************************************
 * The script of website with pictures and movies CUADRO
 * Copyright (c) 2018 - 2022 by IT Works Better https://itworksbetter.net
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

	if(isset($_POST['action'])){

		if(!_ADMIN_TEST_MODE_ and $_POST['action'] == 'admin_change_user' and !empty($_POST['new_username']) and !empty($_POST['new_password']) and !empty($_POST['repeat_new_password'])){

			$result = $admin->changeUser($_POST);

		}elseif($_POST['action'] == 'admin_remove_logs'){

			$result = $admin->removeLogs();

		}elseif($_POST['action'] == 'admin_add_user' and !empty($_POST['username']) and !empty($_POST['password']) and !empty($_POST['repeat_password'])){

			$result = $admin->addUser($_POST);

		}elseif($_POST['action'] == 'admin_remove_user' and isset($_POST['id']) and $_POST['id']>0){

			$result = $admin->removeUser($_POST['id']);

		}elseif($_POST['action'] == 'admin_logout_all'){

			$result = $admin->logOutAll();

		}

	}

	if(isset($result['status'])){
		if(!empty($result['info'])){
			$render_variables['alert_success'][] = $result['info'];
		}elseif(!empty($result['error'])){
			$render_variables['alert_danger'][] = $result['error'];
		}
	}

	$render_variables['admin_users'] = $admin->getUsers();
	$render_variables['admin_logs'] = $admin->getLogs();
	
	$title = lang('Admin Panel Settings').' - '.$title_default;

}
