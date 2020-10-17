<?php

// Set the demonstration mode Admin Panel true/false
define('_ADMIN_TEST_MODE_', false);

// Debug Mode (show all error) true/false
define('_DEBUG_MODE_', false);

require_once(realpath(dirname(__FILE__)).'/db.php');

try{
  $db = new PDO('mysql:host='.$mysql_server.';dbname='.$mysql_db, $mysql_user, $mysql_pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
}
catch (PDOException $e){
  die ("Error connecting to database!");
}

function getSettings(){
  global $db, $settings;
  $sth = $db->query('SELECT * FROM '._DB_PREFIX_.'settings');
  if(!$sth){die('Error! Incorrect database');}
  foreach($sth as $row){
    $settings[$row['name']] = $row['value'];
  }
}
getSettings();

require_once(realpath(dirname(__FILE__)).'/../vendor/autoload.php');

langLoad($settings['lang']);

if(_DEBUG_MODE_){
  ini_set("display_errors", "1");
  error_reporting(E_ALL);
}else{
  error_reporting(0);
}

define('_FOLDER_FILES_', realpath(dirname(__FILE__)).'/../upload/files/');