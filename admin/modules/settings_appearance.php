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

	if(isset($_POST['action']) and $_POST['action']=='save_settings_appearance' and isset($_POST['template']) and $_POST['template']!=''){

		$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'settings` SET value=:value WHERE name=:name limit 1');

		$sth->bindValue(':value', $_POST['template'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'template', PDO::PARAM_STR);
		$sth->execute();

		$sth->bindValue(':value', $_POST['template_color'], PDO::PARAM_STR);
		$sth->bindValue(':name', 'template_color', PDO::PARAM_STR);
		$sth->execute();

		if(!_ADMIN_TEST_MODE_){
			$sth->bindValue(':value', isset($_POST['show_contact_form_profile']), PDO::PARAM_INT);
			$sth->bindValue(':name', 'show_contact_form_profile', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['header'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'header', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['logo'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'logo', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['logo_facebook'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'logo_facebook', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['watermark'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'watermark', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['favicon'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'favicon', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', isset($_POST['rodo_alert']), PDO::PARAM_INT);
			$sth->bindValue(':name', 'rodo_alert', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['rodo_alert_text'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'rodo_alert_text', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['footer_top'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'footer_top', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['footer_bottom'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'footer_bottom', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['code_style'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'code_style', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['code_head'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'code_head', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['code_body'], PDO::PARAM_STR);
			$sth->bindValue(':name', 'code_body', PDO::PARAM_STR);
			$sth->execute();
		}

		$dir = '../tmp/';
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir"){ 
					$objects2 = scandir($dir."/".$object);
					foreach ($objects2 as $object2) {
						if ($object2 != "." && $object2 != "..") {
							unlink($dir."/".$object."/".$object2);
						}
					}
					rmdir($dir."/".$object);
				}else{
					unlink($dir."/".$object);
				}
			}
		}

		getSettings();
		$render_variables['alert_success'][] = lang('Changes have been saved');

	}

	// get list of templates
	$path = '../views/';
	$results = scandir($path);
	$templates = [];
	foreach ($results as $result) {
		if ($result === '.' or $result === '..') continue;
		if (is_dir($path . '/' . $result)) {
		   $templates[] .= $result;
		}
	}
	$render_variables['templates'] = $templates;

	// get list of colors
	$template_colors = ['default','purple darken-1','indigo darken-1','blue lighten-4','blue darken-4','teal lighten-1','green darken-3','lime',' light-green accent-4','orange','brown darken-1','grey darken-4','black','blue-grey','blue-grey darken-4'];
	$render_variables['template_colors'] = $template_colors;

	$title = lang('Appearance settings').' - '.$title_default;
}
