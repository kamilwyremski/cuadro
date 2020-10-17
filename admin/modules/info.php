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

	if(isset($_POST['action'])){
		if(!_ADMIN_TEST_MODE_ and $_POST['action']=='remove_info' and isset($_POST['id']) and $_POST['id']>0){
			$sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'info` WHERE id=:id AND page IS NULL LIMIT 1');
			$sth->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
			$sth->execute();
			$render_variables['alert_danger'][] = lang('Successfully deleted');
		}
	}

	$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'info order by name');
	while($row = $sth->fetch(PDO::FETCH_ASSOC)) {$info[] = $row;}
	if(isset($info)){$render_variables['info'] = $info;}
	
}

