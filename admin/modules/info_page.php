<?php
/************************************************************************
 * The script of website with pictures and movies CUADRO
 * Copyright (c) 2018 - 2023 by IT Works Better https://itworksbetter.net
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
		if($_POST['action']=='add_info' and !empty($_POST['name'])){
			$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'info`(`name`, `slug`, `content`, `keywords`, `description`) VALUES (:name, :slug, :content, :keywords, :description)');
			$sth->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
			$sth->bindValue(':slug', slug($_POST['name']), PDO::PARAM_STR);
			$sth->bindValue(':content', $_POST['content'], PDO::PARAM_STR);
			$sth->bindValue(':keywords', $_POST['keywords'], PDO::PARAM_STR);
			$sth->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
			$sth->execute();
			$id = $db->lastInsertId();
			header('Location: ?module=info_page&id='.$id);
			die('redirect');
		}elseif($_POST['action']=='edit_info' and isset($_POST['id']) and $_POST['id']>0 and !empty($_POST['name'])){
			$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'info` SET `name`=:name, `slug`=:slug, `content`=:content, `keywords`=:keywords, `description`=:description WHERE id=:id limit 1');
			$sth->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
			$sth->bindValue(':slug', slug($_POST['name']), PDO::PARAM_STR);
			$sth->bindValue(':content', $_POST['content'], PDO::PARAM_STR);
			$sth->bindValue(':keywords', $_POST['keywords'], PDO::PARAM_STR);
			$sth->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
			$sth->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
			$sth->execute();
			if($_POST['id']==1){
				$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'settings` SET `value`=:value WHERE name="url_privacy_policy" LIMIT 1');
				$sth->bindValue(':value', slug($_POST['name']), PDO::PARAM_STR);
				$sth->execute();
			}elseif($_POST['id']==2){
				$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'settings` SET `value`=:value WHERE name="url_rules" LIMIT 1');
				$sth->bindValue(':value', slug($_POST['name']), PDO::PARAM_STR);
				$sth->execute();
			}
			$render_variables['alert_success'][] = lang('Changes have been saved');
		}
	}

	if(isset($_GET['id']) and $_GET['id']>0){
		$sth = $db->prepare('select * from '._DB_PREFIX_.'info where id=:id LIMIT 1');
		$sth->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
		$sth->execute();
		$info_page = $sth->fetch(PDO::FETCH_ASSOC);
		if($info_page!=''){
			$title = $info_page['name'].' - '.lang('Info');
			$render_variables['info_page'] = $info_page;
		}
	}

	$title = lang('Info').' - '.$title_default;
}
