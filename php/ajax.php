<?php
/************************************************************************
 * The script of website with pictures and movies CUADRO
 * Copyright (c) 2018 - 2021 by IT Works Better https://itworksbetter.net
 * Project by Kamil Wyremski https://wyremski.pl
 * 
 * All right reserved
 *
 * *********************************************************************
 * THIS SOFTWARE IS LICENSED - YOU CAN MODIFY THESE FILES BUT YOU CAN NOT REMOVE OF ORIGINAL COMMENTS!
 * ACCORDING TO THE LICENSE YOU CAN USE THE SCRIPT ON ONE DOMAIN.
 * *********************************************************************/

session_start();

require_once('../config/config.php');

$user = new user();

if(isset($_POST['action'])){
	if($_POST['action']=='setVoice' and isset($_POST['file_id']) and $_POST['file_id']>0 and !empty($_POST['voice']) and ($_POST['voice']=='-1' or $_POST['voice']=='1') and (!$settings['voice_only_logged'] or $user->getId())){
		files::setVoice($_POST['file_id'], $_POST['voice']);
		echo json_encode(files::getVoice($_POST['file_id']));
	}
}else{
	die('Access denied!');
}
