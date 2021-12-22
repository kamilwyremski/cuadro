<?php
/************************************************************************
 * The script of website with pictures and movies CUADRO
 * Copyright (c) 2018 - 2022 by IT Works Better https://itworksbetter.net
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
		if($_POST['action']=='remove_logs' and !empty($_POST['type'])){
			if($_POST['type']=='only_removed'){
				$db->query('DELETE FROM '._DB_PREFIX_.'logs_files WHERE file_id NOT IN (SELECT id FROM '._DB_PREFIX_.'files)');
				$render_variables['alert_danger'][] = lang('Successfully deleted');
			}elseif($_POST['type']=='all'){
				$db->query('TRUNCATE `'._DB_PREFIX_.'logs_files`');
				$render_variables['alert_danger'][] = lang('Successfully deleted');
			}
		}
	}
	
	$limit = 100;
	
	$sth = $db->prepare('SELECT SQL_CALC_FOUND_ROWS * FROM '._DB_PREFIX_.'logs_files ORDER BY '.sortBy().' LIMIT :limit_from, :limit_to');
	$sth->bindValue(':limit_from', paginationPageFrom($limit), PDO::PARAM_INT);
	$sth->bindValue(':limit_to', $limit, PDO::PARAM_INT);
	$sth->execute();
	generatePagination($limit);
	while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
		$sth2 = $db->prepare('SELECT id, slug, title FROM '._DB_PREFIX_.'files where id=:file_id');
		$sth2->bindValue(':file_id', $row['file_id'], PDO::PARAM_INT);
		$sth2->execute();
		$row['file'] = $sth2->fetch(PDO::FETCH_ASSOC);
		if($row['user_id']){
			$sth2 = $db->prepare('SELECT username, email FROM '._DB_PREFIX_.'users where id=:user_id');
			$sth2->bindValue(':user_id', $row['user_id'], PDO::PARAM_INT);
			$sth2->execute();
			$row['user'] = $sth2->fetch(PDO::FETCH_ASSOC);
		}
		$logs_files[] = $row;
	}
	if(isset($logs_files)){$render_variables['logs_files'] = $logs_files;}
	
	$title = lang('Logs files').' - '.$title_default;

}
