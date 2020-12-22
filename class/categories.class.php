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

class categories {

	public static function show(int $id){
		global $db;
		$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'categories WHERE id=:id LIMIT 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
		return $sth->fetch(PDO::FETCH_ASSOC);
	}

	public static function showBySlug(string $slug){
		global $db;
		$sth = $db->prepare('SELECT * FROM '._DB_PREFIX_.'categories WHERE slug=:slug LIMIT 1');
		$sth->bindValue(':slug', $slug, PDO::PARAM_STR);
		$sth->execute();
		return $sth->fetch(PDO::FETCH_ASSOC);
	}
	
	public static function list(){
		global $db;
		$categories = [];
		$sth = $db->query('SELECT * FROM '._DB_PREFIX_.'categories ORDER BY position');
		foreach($sth as $row){$categories[] = $row;}
		return $categories;
	}

	public static function add(string $name){
		global $db;
		$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'categories`(`slug`, `name`, `position`) VALUES (:slug,:name,:position)');
		$sth->bindValue(':slug', slug($name), PDO::PARAM_STR);
		$sth->bindValue(':name', $name, PDO::PARAM_STR);
		$sth->bindValue(':position', getPosition('categories'), PDO::PARAM_INT);
		$sth->execute();
	}

	public static function edit(int $id, string $name){
		global $db;
		$sth = $db->prepare('UPDATE `'._DB_PREFIX_.'categories` SET slug=:slug, name=:name WHERE id=:id limit 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->bindValue(':slug', slug($name), PDO::PARAM_STR);
		$sth->bindValue(':name', $name, PDO::PARAM_STR);
		$sth->execute();
	}

	public static function remove(int $id){
		global $db;
		$sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'categories` WHERE id=:id LIMIT 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
	}

}
