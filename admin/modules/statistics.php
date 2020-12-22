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

	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'users');
	$statistics['users'] = $sth->fetchColumn();
	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'users WHERE register_fb=1');
	$statistics['users_register_fb'] = $sth->fetchColumn();
	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'users WHERE register_google=1');
	$statistics['users_register_google'] = $sth->fetchColumn();
	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'files');
	$statistics['files'] = $sth->fetchColumn();
	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'files WHERE waiting_room=0');
	$statistics['files_main_page'] = $sth->fetchColumn();
	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'logs_mails');
	$statistics['logs_mails'] = $sth->fetchColumn();
	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'logs_files');
	$statistics['logs_files'] = $sth->fetchColumn();
	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'logs_users');
	$statistics['logs_users'] = $sth->fetchColumn();
	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'reset_password');
	$statistics['reset_password'] = $sth->fetchColumn();
	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'mails_queue');
	$statistics['mails_queue'] = $sth->fetchColumn();
	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'categories');
	$statistics['categories'] = $sth->fetchColumn();
	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'tags');
	$statistics['tags'] = $sth->fetchColumn();

	$render_variables['statistics'] = $statistics;

	$title = lang('Statistics').' - '.$title_default;
}
