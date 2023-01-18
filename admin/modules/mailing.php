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
		if($_POST['action']=='sendMailing' and !empty($_POST['subject']) and isset($_POST['message'])){

			$sth = $db->query('SELECT email FROM '._DB_PREFIX_.'users WHERE active=1');
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
				mailsQueueAdd('mailing',$row['email'],['subject'=>$_POST['subject'], 'message'=>$_POST['message']]);
			}
			header('Location: '.$settings['base_url'].'/'.basename(dirname($_SERVER['REQUEST_URI'])).'/?module=mailing');
			die('redirect');
		}elseif($_POST['action']=='cancel_mailing'){
			$db->query('TRUNCATE '._DB_PREFIX_.'mails_queue');
		}
	}

	$sth = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'mails_queue');
	$mails_queue = $sth->fetchColumn();
	if($mails_queue){
		$render_variables['alert_danger'][] = lang('Warning! Mailing is in progress').'. '.lang('Mails in queue').': '.$mails_queue;
		$render_variables['mails_queue'] = $mails_queue;
	}
}
