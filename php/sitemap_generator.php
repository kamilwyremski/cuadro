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

function sitemap_generator(){
	global $settings, $db, $links;

	$memory = '64M';
	$sitemapFile = dirname(__FILE__)."/../sitemap.xml";

	chmod($sitemapFile, 0777);

	$sitemap_links = [];

	$sitemap_links[] = ['priority'=>'1','url'=>$settings['base_url']];

	foreach($links as $link => $url){
		if(!($link=='edit' or $link=='my_files' or $link=='category' or $link=='tag' or $link=='settings' or $link=='profile' or $link=='search')){
			$sitemap_links[] = ['priority'=>'0.5','url'=>$settings['base_url'].'/'.$url];
		}
	}

	$files = files::getFiles(10000);
	if(!empty($files)){
		foreach($files as $key=>$value){
			$sitemap_links[] = ['priority'=>'0.8','url'=>path('file',$value['id'],$value['slug'])];
		}
	}

	ini_set('memory_limit', $memory);

	$fh = fopen($sitemapFile, 'w');

	$html = '<?xml version="1.0" encoding="UTF-8"?>
	<urlset xmlns="http://www.google.com/schemas/sitemap/0.84"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">';
	fwrite($fh, $html);

	foreach($sitemap_links as $row){
		$entry = "\n";
		$entry .= '<url>';
		$entry .= "\n";
		$entry .= '  <loc>'.$row['url'].'</loc>';
		$entry .= "\n";
		$entry .= '  <changefreq>daily</changefreq>';
		$entry .= "\n";
		$entry .= '  <priority>'.$row['priority'].'</priority>';
		$entry .= "\n";
		$entry .= '</url>';
		fwrite($fh, $entry);
	}

	$html = '
	</urlset>';
	fwrite($fh, $html);
	fclose($fh);

	chmod($sitemapFile, 0644);
}
