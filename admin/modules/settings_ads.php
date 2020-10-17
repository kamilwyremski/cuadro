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
		
		if($_POST['action']=='save_settings_ads'){
			$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'settings` SET value=:value WHERE name=:name limit 1');
			$sth->bindValue(':value', isset($_POST['ads_between_files']), PDO::PARAM_INT);
			$sth->bindValue(':name', 'ads_between_files', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['ads_amount_files'], PDO::PARAM_INT);
			$sth->bindValue(':name', 'ads_amount_files', PDO::PARAM_STR);
			$sth->execute();
			getSettings();
			$render_variables['alert_success'][] = lang('Changes have been saved');
		}if($_POST['action']=='add_ads' and !empty($_POST['code'])){
			$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'ads`(`code`) VALUES (:code)');
			$sth->bindValue(':code', $_POST['code'], PDO::PARAM_STR);
			$sth->execute();
			$render_variables['alert_success'][] = lang('Successfully added new ads');
		}elseif($_POST['action']=='edit_ads' and isset($_POST['id']) and $_POST['id']>0 and !empty($_POST['code'])){
			$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'ads` SET code=:code WHERE id=:id limit 1');
			$sth->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
			$sth->bindValue(':code', $_POST['code'], PDO::PARAM_STR);
			$sth->execute();
			$render_variables['alert_success'][] = lang('Changes have been saved');
		}elseif($_POST['action']=='remove_ads' and isset($_POST['id']) and $_POST['id']!=''){
			$sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'ads` WHERE id=:id LIMIT 1');
			$sth->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
			$sth->execute();
			$render_variables['alert_danger'][] = lang('Successfully deleted');
		}
	}

	$ads = array();
	$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'ads ORDER BY id');
	foreach($sth as $row){$ads[] = $row;}
	$render_variables['ads'] = $ads;
	
	$title = lang('Ads settings').' - '.$title_default;
}

