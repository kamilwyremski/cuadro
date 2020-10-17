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

	if(!_ADMIN_TEST_MODE_ and isset($_POST['action'])){
		if($_POST['action']=='add_box' and !empty($_POST['type'])){
			boxes::add($_POST);
			$render_variables['alert_success'][] = lang('Successfully added new box');
		}elseif($_POST['action']=='edit_box' and isset($_POST['id']) and $_POST['id']>0 and !empty($_POST['type'])){
			boxes::edit($_POST['id'],$_POST);
			$render_variables['alert_success'][] = lang('Changes have been saved');
		}elseif($_POST['action']=='remove_box' and isset($_POST['id']) and $_POST['id']!=''){
			boxes::remove($_POST['id']);
			$render_variables['alert_danger'][] = lang('Successfully deleted');
		}
	}

	$render_variables['boxes'] = boxes::getBoxes('admin');
	$render_variables['boxes_types'] = boxes::$types;

	$title = lang('Boxes').' - '.$title_default;
}
