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

	if(!_ADMIN_TEST_MODE_ and isset($_POST['action'])){
		if($_POST['action']=='save_settings_automation'){

			$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'settings` SET value=:value WHERE name=:name LIMIT 1');

			$sth->bindValue(':value', isset($_POST['automation_randomly']), PDO::PARAM_INT);
			$sth->bindValue(':name', 'automation_randomly', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['automation_randomly_files'], PDO::PARAM_INT);
			$sth->bindValue(':name', 'automation_randomly_files', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', isset($_POST['automation_added']), PDO::PARAM_INT);
			$sth->bindValue(':name', 'automation_added', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['automation_added_files'], PDO::PARAM_INT);
			$sth->bindValue(':name', 'automation_added_files', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['automation_added_days'], PDO::PARAM_INT);
			$sth->bindValue(':name', 'automation_added_days', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', isset($_POST['automation_votes_plus']), PDO::PARAM_INT);
			$sth->bindValue(':name', 'automation_votes_plus', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['automation_votes_plus_files'], PDO::PARAM_INT);
			$sth->bindValue(':name', 'automation_votes_plus_files', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['automation_votes_plus_votes'], PDO::PARAM_INT);
			$sth->bindValue(':name', 'automation_votes_plus_votes', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', isset($_POST['automation_votes_minus']), PDO::PARAM_INT);
			$sth->bindValue(':name', 'automation_votes_minus', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['automation_votes_minus_files'], PDO::PARAM_INT);
			$sth->bindValue(':name', 'automation_votes_minus_files', PDO::PARAM_STR);
			$sth->execute();
			$sth->bindValue(':value', $_POST['automation_votes_minus_votes'], PDO::PARAM_INT);
			$sth->bindValue(':name', 'automation_votes_minus_votes', PDO::PARAM_STR);
			$sth->execute();

			getSettings();
			$render_variables['alert_success'][] = lang('Changes have been saved');

		}
	}

	$title = lang('Automation').' - '.$title_default;

}
