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

require_once('../config/config.php');

if(!$settings['rss']){
	die(lang('RSS feed was switched off'));
}

header("Content-Type: application/xml; charset=utf-8");

$files = files::getFiles(20);

$rssfeed = '<?xml version="1.0" encoding="utf-8"?>';
$rssfeed .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';
$rssfeed .= '<channel>';
$rssfeed .= '<title>'.$settings['title'].'</title>';
$rssfeed .= '<link>'.$settings['base_url'].'</link>';
if($settings['logo']!=''){
	$rssfeed .= ' <image>
		<title>'.$settings['title'].'</title>
		<url>'.$settings['logo'].'</url>
		<link>'.$settings['base_url'].'</link>
	</image>';
}
$rssfeed .= '<description>'.$settings['description'].'</description>';
$rssfeed .= '<language>'.$settings['lang'].'</language>';
$rssfeed .= '<lastBuildDate>'.date("D, d M Y H:i:s O").'</lastBuildDate>';
$rssfeed .= '<atom:link href="'.$settings['base_url'].'/php/rss.php" rel="self" type="application/rss+xml" />';
if(!empty($files)){
	foreach($files as $key=>$value){
		$rssfeed .= '<item>';
		$rssfeed .= '<title>'.$value['title'].'</title>';
		$rssfeed .= '<link>'.path('file',$value['id'],$value['slug']).'</link>';
		$rssfeed .= '<guid>'.path('file',$value['id'],$value['slug']).'</guid>';
		$rssfeed .= '<pubDate>'.date("D, d M Y H:i:s O", strtotime($value['date'])).'</pubDate>';
		$rssfeed .= '<description>';
		$rssfeed .= substr(strip_tags(htmlspecialchars(strip_tags($value['description']), ENT_XML1, 'UTF-8')),0,400).'...';
		if($value['type']=='image' or $value['type']=='iframe'){
			$rssfeed .= '&lt;br&gt;&lt;br&gt;&lt;a href="'.path('file',$value['id'],$value['slug']).'"&gt;&lt;img src="'.$settings['base_url'].'/upload/files/'.$value['thumb'].'" height="80"/&gt;&lt;/&gt;';
		}
		$rssfeed .= '</description>';
		$rssfeed .= '</item>';
	}
}
$rssfeed .= '</channel>';
$rssfeed .= '</rss>';

echo $rssfeed;
