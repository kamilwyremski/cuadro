<?php
/************************************************************************
 * The script of website with pictures and movies CUADRO
 * Copyright (c) Kamil Wyremski
 * Project by Kamil Wyremski https://wyremski.pl
 * 
 * All right reserved
 *
 * *********************************************************************
 * THIS SOFTWARE IS LICENSED - YOU CAN MODIFY THESE FILES BUT YOU CAN NOT REMOVE OF ORIGINAL COMMENTS!
 * ACCORDING TO THE LICENSE YOU CAN USE THE SCRIPT ON ONE DOMAIN.
 * *********************************************************************/

require_once(realpath(dirname(__FILE__)).'/config/config.php');

function cron_hourly(){
	global $settings, $db;

	if($settings['automation_randomly'] and $settings['automation_randomly_files']){
		$sth = $db->query('SELECT id FROM `'._DB_PREFIX_.'files` WHERE waiting_room=1 ORDER BY rand() LIMIT '.$settings['automation_randomly_files']);
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
			files::setMainPage($row['id']);
		}
	}

	if($settings['automation_added'] and $settings['automation_added_files'] and $settings['automation_added_days']){
		$sth = $db->query('SELECT id FROM `'._DB_PREFIX_.'files` WHERE waiting_room=1 AND date<=(CURDATE() - INTERVAL '.$settings['automation_added_days'].' DAY) ORDER BY rand() LIMIT '.$settings['automation_added_files']);
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
			files::setMainPage($row['id']);
		}
	}

	if($settings['automation_votes_plus'] and $settings['automation_votes_plus_files'] and $settings['automation_votes_plus_votes']){
		$sth = $db->query('SELECT f.id FROM `'._DB_PREFIX_.'files` f WHERE f.waiting_room=1 AND (SELECT count(1) FROM '._DB_PREFIX_.'voices v WHERE v.voice="1" AND v.file_id=f.id)>='.$settings['automation_votes_plus_votes'].' ORDER BY rand() LIMIT '.$settings['automation_votes_plus_files']);
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
			files::setMainPage($row['id']);
		}
	}

	if($settings['automation_votes_minus'] and $settings['automation_votes_minus_files'] and $settings['automation_votes_minus_votes']){
		$sth = $db->query('SELECT f.id FROM `'._DB_PREFIX_.'files` f WHERE f.waiting_room=1 AND (SELECT count(1) FROM '._DB_PREFIX_.'voices v WHERE v.voice="-1" AND v.file_id=f.id)>='.$settings['automation_votes_minus_votes'].' ORDER BY rand() LIMIT '.$settings['automation_votes_minus_files']);
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
			files::setMainPage($row['id']);
		}
	}

}
cron_hourly();

