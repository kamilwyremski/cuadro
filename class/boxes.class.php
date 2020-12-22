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

class boxes {

  static $types = ['text','statistic','categories','tags','new_files','waiting_room','random_files','top_files','search_box'];

  public static function getBoxes($controller=''){
    global $db;
    $boxes = [];
    $sth = $db->query('SELECT * FROM '._DB_PREFIX_.'boxes ORDER BY position');
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
      if($controller!='admin'){
        if($row['type']=='categories'){
          $row['categories'] = categories::list();
        }elseif($row['type']=='tags'){
          $row['tags'] = getTags($row['amount']);
          $row['max_amount_tag'] = 0;
        	foreach($row['tags'] as $tag){
            if($tag['amount'] > $row['max_amount_tag']){$row['max_amount_tag'] = $tag['amount'];}
          }
        }elseif($row['type']=='new_files' or $row['type']=='waiting_room' or $row['type']=='random_files' or $row['type']=='top_files'){
          $row['files'] = files::getFilesToBox($row['type'],$row['amount']);
        }elseif($row['type']=='statistic'){
          $sth2 = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'files');
          $row['statistic']['files'] = $sth2->fetchColumn();
          $sth2 = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'files WHERE waiting_room=1');
          $row['statistic']['files_waiting_room'] = $sth2->fetchColumn();
          $sth2 = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'categories');
          $row['statistic']['categories'] = $sth2->fetchColumn();
          $sth2 = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'tags');
          $row['statistic']['tags'] = $sth2->fetchColumn();
          $sth2 = $db->query('SELECT COUNT(1) FROM '._DB_PREFIX_.'users WHERE active=1');
          $row['statistic']['users'] = $sth2->fetchColumn();
        }
      }
      $boxes[] = $row;
    }
		return $boxes;
  }

  public static function add($data){
    global $db;
    $sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'boxes`(`position`) VALUES (:position)');
    $sth->bindValue(':position', getPosition('boxes'), PDO::PARAM_INT);
    $sth->execute();
    $id = $db->lastInsertId();
    static::edit($id,$data);
  }

  public static function edit(int $id, $data){
    global $db;
    if(!isset($data['content'])){$data['content']='';}
    if(!isset($data['amount'])){$data['amount']=0;}
    $sth = $db->prepare('UPDATE `'._DB_PREFIX_.'boxes` SET type=:type, content=:content, amount=:amount WHERE id=:id limit 1');
    $sth->bindValue(':id', $id, PDO::PARAM_INT);
    $sth->bindValue(':type', $data['type'], PDO::PARAM_STR);
    $sth->bindValue(':content', $data['content'], PDO::PARAM_STR);
    $sth->bindValue(':amount', $data['amount'], PDO::PARAM_STR);
    $sth->execute();
  }

  public static function remove(int $id){
    global $db;
    $sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'boxes` WHERE id=:id limit 1');
    $sth->bindValue(':id', $id, PDO::PARAM_INT);
    $sth->execute();
  }
}
