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
		if($_POST['action']=='remove_tag' and isset($_POST['id']) and $_POST['id']!=''){
			$sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'tags_files` WHERE tag_id=:id');
			$sth->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
			$sth->execute();
			$sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'tags` WHERE id=:id LIMIT 1');
			$sth->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
			$sth->execute();
			$render_variables['alert_danger'][] = lang('Successfully deleted');
		}
	}

	$limit = 100;
	$sth = $db->prepare('SELECT SQL_CALC_FOUND_ROWS *, (SELECT COUNT(1) FROM '._DB_PREFIX_.'tags_files where tag_id = t.id) as amount FROM '._DB_PREFIX_.'tags t ORDER BY '.sortBy().' LIMIT :limit_from, :limit_to');
	$sth->bindValue(':limit_from', paginationPageFrom($limit), PDO::PARAM_INT);
	$sth->bindValue(':limit_to', $limit, PDO::PARAM_INT);
	$sth->execute();
	generatePagination($limit);
	while ($row = $sth->fetch(PDO::FETCH_ASSOC)){$tags[] = $row;}

	if(isset($tags)){$render_variables['tags'] = $tags;}

	$title = lang('Tags').' - '.$title_default;
}
