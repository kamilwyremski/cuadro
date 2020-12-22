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

if($admin->is_logged()){

	if(!_ADMIN_TEST_MODE_ and isset($_POST['action']) and $_POST['action']=='save_settings_social_media' and isset($_POST['facebook_lang']) and $_POST['facebook_lang']!=''){
		
		$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'settings` SET value=:value WHERE name=:name limit 1');

		$sth->bindValue(':value', isset($_POST['social_facebook']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'social_facebook', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['social_pinterest']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'social_pinterest', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['social_twitter']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'social_twitter', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['social_wykop']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'social_wykop', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['url_facebook'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'url_facebook', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['facebook_side_panel']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'facebook_side_panel', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['allow_comments_fb_file']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'allow_comments_fb_file', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['allow_comments_fb_profile']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'allow_comments_fb_profile', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['facebook_lang'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'facebook_lang', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['facebook_login']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'facebook_login', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['facebook_api'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'facebook_api', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['facebook_secret'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'facebook_secret', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', isset($_POST['google_login']), PDO::PARAM_INT);
		$sth->bindValue(':name', 'google_login', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['google_id'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'google_id', PDO::PARAM_STR);
		$sth->execute();
		$sth->bindValue(':value', $_POST['google_secret'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'google_secret', PDO::PARAM_STR);
		$sth->execute();
		
		getSettings();
		$render_variables['alert_success'][] = lang('Changes have been saved');
	}
	
	$title = lang('Setting social networks').' - '.$title_default;
}
