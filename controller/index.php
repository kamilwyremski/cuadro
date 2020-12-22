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

$page_header = '';
$subpage_data = [];

if(!empty($controller_subpage)){
	if($controller_subpage=='waiting_room'){
		$page_header = lang('Waiting Room');
		$settings['title'] = $page_header.' - '.$settings['title'];
		$settings['description'] = $page_header.' - '.$settings['description'];
	}elseif($controller_subpage=='top'){
		$page_header = lang('Top');
		$settings['title'] = $page_header.' - '.$settings['title'];
		$settings['description'] = $page_header.' - '.$settings['description'];
	}elseif($controller_subpage=='random'){
		$page_header = lang('Random');
		$settings['title'] = $page_header.' - '.$settings['title'];
		$settings['description'] = $page_header.' - '.$settings['description'];
	}elseif($controller_subpage=='search' and !empty($_GET['q'])){
		$page_header = lang('Search').': '.strip_tags($_GET['q']);
		$settings['title'] = $page_header.' - '.$settings['title'];
		$settings['description'] = $page_header.' - '.$settings['description'];
	}elseif($controller_subpage=='profile_files' and !empty($_GET['slug'])){
		$page_header = lang('Files of user').': '.strip_tags($_GET['slug']);
		$settings['title'] = $page_header.' - '.$settings['title'];
		$settings['description'] = $page_header.' - '.$settings['description'];
		$subpage_data['username'] = strip_tags($_GET['slug']);
	}elseif($controller_subpage=='category'){
		$category = categories::showBySlug($_GET['slug']);
		if($category){
			$page_header = lang('Category').': '.$category['name'];
			$settings['title'] = $page_header.' - '.$settings['title'];
			$settings['description'] = $page_header.' - '.$settings['description'];
			$subpage_data['category_id'] = $category['id'];
		}else{
			include('controller/404.php');
		}
	}elseif($controller_subpage=='tag'){
		$tag = getTagBySlug($_GET['slug']);
		if($tag){
			$page_header = lang('Tag').': '.$tag['name'];
			$settings['title'] = $page_header.' - '.$settings['title'];
			$settings['description'] = $page_header.' - '.$settings['description'];
			$subpage_data['tag_id'] = $tag['id'];
		}else{
			include('controller/404.php');
		}
	}else{
		$controller_subpage = 'main_page';
	}
}else{
	$controller_subpage = 'main_page';
}

if($settings['ads_between_files']){
	$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'ads ORDER BY rand()');
	foreach($sth as $row){$ads_database[] = $row;}
	if(!empty($ads_database)){
		for($i=0;$i<$settings['limit_page'];$i++){
			if($i%$settings['ads_amount_files']==0){
				$ads[$i] = array_pop($ads_database);
			}
		}

		$render_variables['ads'] = $ads;
	}
}

$render_variables['boxes'] = boxes::getBoxes();

$render_variables['controller_subpage'] = $controller_subpage;

$render_variables['page_header'] = $page_header;

$render_variables['files'] = files::getFiles($settings['limit_page'],$controller_subpage,$subpage_data);
