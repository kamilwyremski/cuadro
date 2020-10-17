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

function cron(){
	global $db, $settings;

	$db->query('DELETE FROM '._DB_PREFIX_.'users WHERE active=0 and date<(CURDATE() - INTERVAL 1 DAY)');

	$db->query('DELETE FROM '._DB_PREFIX_.'session_user WHERE date<(CURDATE() - INTERVAL 1 DAY)');
	
	$db->query('DELETE FROM '._DB_PREFIX_.'admin_session WHERE date<(CURDATE() - INTERVAL 1 DAY)');
	
	if($settings['generate_sitemap']){
		include(realpath(dirname(__FILE__)).'/php/sitemap_generator.php');
		sitemap_generator();
	}

}
cron();


