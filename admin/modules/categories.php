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
		if($_POST['action']=='add_category' and !empty($_POST['name'])){
			$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'categories`(`slug`, `name`, `position`) VALUES (:slug,:name,:position)');
			$sth->bindValue(':slug', slug($_POST['name']), PDO::PARAM_STR);
			$sth->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
			$sth->bindValue(':position', getPosition('categories'), PDO::PARAM_INT);
			$sth->execute();
			$render_variables['alert_success'][] = lang('Successfully added new category').' '.filter($_POST['name']);
		}elseif($_POST['action']=='edit_category' and isset($_POST['id']) and $_POST['id']>0 and !empty($_POST['name'])){
			$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'categories` SET slug=:slug, name=:name WHERE id=:id limit 1');
			$sth->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
			$sth->bindValue(':slug', slug($_POST['name']), PDO::PARAM_STR);
			$sth->bindValue(':name', $_POST['name'], PDO::PARAM_STR);
			$sth->execute();
			$render_variables['alert_success'][] = lang('Changes have been saved');
		}elseif($_POST['action']=='remove_category' and isset($_POST['id']) and $_POST['id']!=''){
			$sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'categories` WHERE id=:id LIMIT 1');
			$sth->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
			$sth->execute();
			$render_variables['alert_danger'][] = lang('Successfully deleted');
		}
	}

	$render_variables['categories'] = getCategories();
	
	$title = lang('Categories').' - '.$title_default;
}
