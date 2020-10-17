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

header('Content-Type: text/html; charset=utf-8');
header('X-XSS-Protection: 0');
header('X-Frame-Options: SAMEORIGIN');

session_start();

require_once('../vendor/autoload.php');
$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader, [
    'cache' => 'tmp',
]);
$twig->addFilter(new Twig_Filter('lang', 'lang'));
$twig->addFilter(new Twig_Filter('showCurrency', 'showCurrency'));
$twig->addFunction(new Twig_Function('path', 'path'));

require_once('../config/config.php');
require_once('php/admin.class.php');

$admin = new admin();

$title = $title_default = 'Admin Panel created by Kamil Wyremski';

$module = 'index';
if($admin->is_logged()){
	if(isset($_GET['module']) and isSlug($_GET['module'])){
		if(file_exists('modules/'.$_GET['module'].'.php')){
			$module = $_GET['module'];
			$title = ucfirst($module).' - '.$title_default;
		}else{
			$module = '404';
		}
	}
}else{
	$module = 'login';
}

$render_variables = [];

require_once('modules/'.$module.'.php');

echo $twig->render($module.'.html', array_merge($render_variables, ['title' => $title, 'settings' => $settings, 'admin' => $admin->user_data, '_ADMIN_TEST_MODE_' => _ADMIN_TEST_MODE_, 'get' => $_GET, 'module' => $module, 'folder_admin' => basename(dirname($_SERVER['REQUEST_URI']))]));
