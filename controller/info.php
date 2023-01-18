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

if(isset($_GET['id']) and $_GET['id']>0 and !empty($_GET['slug'])){
	
	$sth = $db->prepare('select * from '._DB_PREFIX_.'info where id=:id limit 1');
	$sth->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
	$sth->execute();
	$info_page = $sth->fetch(PDO::FETCH_ASSOC);
	if($info_page!=''){
		if($_GET['slug']!=$info_page['slug']){
			header("Location: ".path('info', $info_page['id'], $info_page['slug']));
			die('redirect');
		}else{
			$render_variables['info_page'] = $info_page;
			$settings['title'] = $info_page['name'].' - '.$settings['title'];
			if($info_page['description']){
				$settings['description'] = $info_page['description'];
			}else{
				$settings['description'] = $info_page['name'].' - '.$settings['description'];
			}
			if($info_page['keywords']){
				$settings['keywords'] = $info_page['keywords'];
			}
		}
	}else{
		include_once('controller/404.php');
	}
}else{

	if(!empty($slug_parts)){
		include_once('controller/404.php');
	}

	$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'info order by name');
	while($row = $sth->fetch(PDO::FETCH_ASSOC)) {$info[] = $row;}
	$render_variables['info'] =  $info;
	$settings['title'] = lang('Info').' - '.$settings['title'];
	$settings['description'] = lang('Info').' - '.$settings['description'];
}

