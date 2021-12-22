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
	
	$user = new user;

	if(!_ADMIN_TEST_MODE_ and isset($_POST['action'])){
		if($_POST['action']=='remove_user' and isset($_POST['id']) and $_POST['id']>0){
			$user->remove($_POST['id']);
			if(isset($_POST['add_email_black_list']) and !empty($_POST['email'])){
				addEmailToBlackList($_POST['email']);
			}
			if(isset($_POST['add_ip_black_list']) and !empty($_POST['register_ip'])){
				addIpToBlackList($_POST['register_ip']);
			}
			if(isset($_POST['add_ip_black_list']) and !empty($_POST['activation_ip'])){
				addIpToBlackList($_POST['activation_ip']);
			}
			$render_variables['alert_danger'][] = lang('User has been deleted');
		}elseif($_POST['action']=='remove_users' and isset($_POST['users']) and is_array($_POST['users'])){
			foreach($_POST['users'] as $key => $value){
				if($value>0){
					$user->remove($value);
				}
			}
			$render_variables['alert_danger'][] = lang('User has been deleted');
		}elseif($_POST['action']=='activate_users' and isset($_POST['users']) and is_array($_POST['users'])){
			foreach($_POST['users'] as $key => $value){
				if($value>0){
					$user->activate($value);
				}
			}
			$render_variables['alert_success'][] = lang('Changes have been saved');
		}elseif($_POST['action']=='set_moderators' and isset($_POST['users']) and is_array($_POST['users'])){
			foreach($_POST['users'] as $key => $value){
				if($value>0){
					$user->setModerator($value);
				}
			}
			$render_variables['alert_success'][] = lang('Changes have been saved');
		}elseif($_POST['action']=='unset_moderators' and isset($_POST['users']) and is_array($_POST['users'])){
			foreach($_POST['users'] as $key => $value){
				if($value>0){
					$user->unSetModerator($value);
				}
			}
			$render_variables['alert_success'][] = lang('Changes have been saved');
		}
	}

	$limit = 50;
	$where_statement = ' true ';
	if(isset($_GET['search'])){
		if(!empty($_GET['username'])){
			$where_statement .= ' and username like "%'.filter($_GET['username']).'%"  ';
		}
		if(!empty($_GET['email'])){
			$where_statement .= ' and email like "%'.filter($_GET['email']).'%"  ';
		}
		if(!empty($_GET['active'])){
			if($_GET['active']=='yes'){
				$where_statement .= ' and active="1" ';
			}elseif($_GET['active']=='no'){
				$where_statement .= ' and (active="0" or active IS NULL) ';
			}
		}
		if(!empty($_GET['moderator'])){
			if($_GET['moderator']=='yes'){
				$where_statement .= ' and moderator="1" ';
			}elseif($_GET['moderator']=='no'){
				$where_statement .= ' and (moderator="0" or moderator IS NULL) ';
			}
		}
		if(!empty($_GET['register_fb'])){
			if($_GET['register_fb']=='yes'){
				$where_statement .= ' and register_fb="1" ';
			}elseif($_GET['register_fb']=='no'){
				$where_statement .= ' and (register_fb="0" or register_fb IS NULL) ';
			}
		}
		if(!empty($_GET['register_google'])){
			if($_GET['register_google']=='yes'){
				$where_statement .= ' and register_google="1" ';
			}elseif($_GET['register_google']=='no'){
				$where_statement .= ' and (register_google="0" or register_google IS NULL) ';
			}
		}
		if(!empty($_GET['date_from'])){
			$where_statement .= ' AND date >= "'.filter($_GET['date_from']).'" ';
		}
		if(!empty($_GET['date_to'])){
			$where_statement .= ' AND date <= "'.filter($_GET['date_to']).' 23:59:59" ';
		}
		if(!empty($_GET['register_ip'])){
			$where_statement .= ' AND register_ip like "%'.filter($_GET['register_ip']).'%" ';
		}
	}

	$sth = $db->prepare('SELECT SQL_CALC_FOUND_ROWS * FROM '._DB_PREFIX_.'users WHERE '.$where_statement.' ORDER BY '.sortBy().' LIMIT :limit_from, :limit_to');
	$sth->bindValue(':limit_from', paginationPageFrom($limit), PDO::PARAM_INT);
	$sth->bindValue(':limit_to', $limit, PDO::PARAM_INT);
	$sth->execute();
	generatePagination($limit);
	while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
		$sth2 = $db->prepare('SELECT COUNT(*) FROM '._DB_PREFIX_.'files WHERE user_id=:user_id');
		$sth2->bindValue(':user_id', $row['id'], PDO::PARAM_INT);
		$sth2->execute();
		$row['amount_files'] = $sth2->fetchColumn();
		
		$sth2 = $db->prepare('SELECT COUNT(*) FROM '._DB_PREFIX_.'files WHERE user_id=:user_id AND waiting_room=0');
		$sth2->bindValue(':user_id', $row['id'], PDO::PARAM_INT);
		$sth2->execute();
		$row['amount_files_main_page'] = $sth2->fetchColumn();
		
		$sth2 = $db->prepare('SELECT date FROM '._DB_PREFIX_.'logs_users WHERE user_id=:user_id ORDER BY date desc LIMIT 1');
		$sth2->bindValue(':user_id', $row['id'], PDO::PARAM_INT);
		$sth2->execute();
		$row['last_login'] = $sth2->fetchColumn();
		
		$sth2 = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'logs_users WHERE user_id=:user_id');
		$sth2->bindValue(':user_id', $row['id'], PDO::PARAM_INT);
		$sth2->execute();
		$rows = $sth2->fetchAll();
		$row['amount_logins'] = count($rows);
		
		$users[] = $row;
	}

	if(isset($users)){$render_variables['users'] = $users;}

	$title = lang('Users').' - '.$title_default;
}

