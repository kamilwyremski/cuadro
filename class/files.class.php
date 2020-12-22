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

class files {

  public static function getFiles(int $limit = 10, $subpage = '', $subpage_data = []){
		global $db, $user;
		$files = [];
        $where_statement = ' true ';
        $sort = ' f.main_page_date desc ';
        if($subpage=='main_page'){
            $where_statement .= ' AND f.waiting_room=0 ';
        }elseif($subpage=='waiting_room'){
            $where_statement .= ' AND f.waiting_room=1 ';
        }elseif($subpage=='top'){
            $sort = ' voices_plus desc, f.main_page_date desc ';
        }elseif($subpage=='random'){
            $sort = ' rand() ';
        }elseif($subpage=='category' and !empty($subpage_data['category_id'])){
            $where_statement .= ' AND f.category_id='.filter($subpage_data['category_id']).' ';
        }elseif($subpage=='search' and !empty($_GET['q'])){
            $where_statement .= ' AND (f.slug LIKE "%'.slug(filter($_GET['q'])).'%" OR f.id IN (SELECT tf.file_id FROM '._DB_PREFIX_.'tags t, '._DB_PREFIX_.'tags_files tf WHERE t.slug LIKE "%'.slug(filter($_GET['q'])).'%" AND t.id = tf.tag_id)) ';
        }elseif($subpage=='profile_files' and !empty($subpage_data['username'])){
            $where_statement .= ' AND f.user_id=(SELECT id FROM '._DB_PREFIX_.'users u WHERE u.id=f.user_id LIMIT 1) ';
        }elseif($subpage=='tag' and !empty($subpage_data['tag_id'])){
            $where_statement .= ' AND (SELECT count(1) FROM '._DB_PREFIX_.'tags_files tf, '._DB_PREFIX_.'tags t WHERE tf.tag_id=t.id AND t.id="'.filter($subpage_data['tag_id']).'" AND tf.file_id=f.id LIMIT 1) > 0 ';
        }elseif($subpage=='my_files'){
            $where_statement .= ' AND f.user_id='.filter($user->getId()).' ';
        }elseif($subpage=='admin'){
            $sort = sortBy();
            if(isset($_GET['search'])){
                if(isset($_GET['id']) and $_GET['id']>0){
                    $where_statement .= ' AND f.id = "'.filter($_GET['id']).'" ';
                }
                if(!empty($_GET['title'])){
                    $where_statement .= ' AND f.slug LIKE "%'.filter(slug($_GET['title'])).'%" ';
                }
                if(isset($_GET['user_id']) and $_GET['user_id']>0){
                    $where_statement .= ' AND f.user_id = "'.filter($_GET['user_id']).'" ';
                }
                if(!empty($_GET['main_page'])){
                    if($_GET['main_page']=='yes'){
                        $where_statement .= ' AND f.waiting_room="0" ';
                    }elseif($_GET['main_page']=='no'){
                        $where_statement .= ' AND f.waiting_room="1" ';
                    }
                }
                if(!empty($_GET['date_from'])){
                    $where_statement .= ' AND f.date >= "'.filter($_GET['date_from']).'" ';
                }
                if(!empty($_GET['date_to'])){
                    $where_statement .= ' AND f.date <= "'.filter($_GET['date_to']).' 23:59:59" ';
                }
                if(!empty($_GET['ip'])){
                    $where_statement .= ' AND f.ip like "%'.filter($_GET['ip']).'%" ';
                }
            }
        }

        $sth = $db->prepare('SELECT SQL_CALC_FOUND_ROWS *, 
        (SELECT username FROM '._DB_PREFIX_.'users WHERE id=f.user_id) AS username, 
        (SELECT count(1) FROM '._DB_PREFIX_.'voices v WHERE v.voice="1" AND v.file_id=f.id) AS voices_plus, (SELECT count(1) FROM '._DB_PREFIX_.'voices v WHERE v.voice="-1" AND v.file_id=f.id) AS voices_minus,
        (SELECT count(1) FROM '._DB_PREFIX_.'logs_files lf WHERE lf.file_id=f.id) AS view_all,
        (SELECT count(distinct lf.ip) FROM '._DB_PREFIX_.'logs_files lf WHERE lf.file_id=f.id) AS view_unique 
        FROM '._DB_PREFIX_.'files f WHERE '.$where_statement.' ORDER BY '.$sort.' LIMIT :limit_from, :limit_to');
		$sth->bindValue(':limit_from', paginationPageFrom($limit), PDO::PARAM_INT);
		$sth->bindValue(':limit_to', $limit, PDO::PARAM_INT);
		$sth->execute();
		generatePagination($limit);
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
            if($row['category_id']>0){
                $row['category'] = categories::show($row['category_id']);
            }
            $files[] = $row;
        }
		return $files;
  }

    public static function getFilesToBox($type, int $limit){
		global $db;
		$files = [];
		$select = '';
        $where_statement = ' true ';
        $sort = ' id desc ';
        if($type=='waiting_room'){
            $where_statement .= ' AND waiting_room=1 ';
        }elseif($type=='random_files'){
            $sort = ' rand() ';
        }elseif($type=='top_files'){
			$select .= ', (SELECT count(1) FROM '._DB_PREFIX_.'voices v WHERE v.voice="1" AND v.file_id='._DB_PREFIX_.'files.id) AS voices_plus ';
            $where_statement .= ' AND waiting_room=0 ';
			$sort = ' voices_plus desc, main_page_date desc '; 
        }
        $sth = $db->prepare('SELECT * '.$select.' FROM '._DB_PREFIX_.'files WHERE '.$where_statement.' ORDER BY '.$sort.' LIMIT :limit');
        $sth->bindValue(':limit', $limit, PDO::PARAM_INT);
        $sth->execute();
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)){$files[] = $row;}
        return $files;
    }

     public static function getFile(int $id,$type=''){
		global $db, $user;
		$sth = $db->prepare('SELECT *, 
        (SELECT username FROM '._DB_PREFIX_.'users WHERE id=f.user_id) AS username, 
        (SELECT count(1) FROM '._DB_PREFIX_.'voices v WHERE v.voice="1" AND v.file_id=f.id) AS voices_plus, (SELECT count(1) FROM '._DB_PREFIX_.'voices v WHERE v.voice="-1" AND v.file_id=f.id) AS voices_minus,
        (SELECT count(1) FROM '._DB_PREFIX_.'logs_files lf WHERE lf.file_id=f.id) AS view_all,
        (SELECT count(distinct lf.ip) FROM '._DB_PREFIX_.'logs_files lf WHERE lf.file_id=f.id) AS view_unique
        FROM '._DB_PREFIX_.'files f WHERE id=:id LIMIT 1');
		$sth->bindValue(':id', $id, PDO::PARAM_INT);
		$sth->execute();
        $file = $sth->fetch(PDO::FETCH_ASSOC);
        if($file){
            if($type!='admin'){
                if($file['category_id']>0){
                    $file['category'] = categories::show($file['category_id']);
                }

                $sth = $db->prepare('SELECT name, slug FROM '._DB_PREFIX_.'tags, '._DB_PREFIX_.'tags_files WHERE id=tag_id AND file_id=:file_id');
                $sth->bindValue(':file_id', $id, PDO::PARAM_INT);
                $sth->execute();
                while ($row = $sth->fetch(PDO::FETCH_ASSOC)){$file['tags'][] = $row;}

				if($type!='edit'){
					$sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'logs_files`(`file_id`, `user_id`, `ip`, `date`) VALUES (:file_id,:user_id,:ip,NOW())');
					$sth->bindValue(':file_id', $id, PDO::PARAM_INT);
					$sth->bindValue(':user_id', $user->getId(), PDO::PARAM_INT);
					$sth->bindValue(':ip', getClientIp(), PDO::PARAM_STR);
					$sth->execute();
				}
            }
        }
		return $file;
     }

    public static function add($data){
        global $db, $user, $settings;

        if(checkIpBlackList(getClientIp())){
            return ['status'=>false,'error'=>lang('File cannot be added')];
        }

        if($data['type']=='image_disk'){
            $type = 'image';
            $path_parts = pathinfo($_FILES['image_disk']['name']);
            $path_parts['extension'] = strtolower($path_parts['extension']);

            if(!in_array($path_parts['extension'] , ['jpg','jpeg','png'])){
                return ['status'=>false,'error'=>lang('Invalid file type')];
            }else{

                $url = substr(slug($path_parts['filename']), 0, 200).'.'.$path_parts['extension'];
                $i = 0;
                while(file_exists(_FOLDER_FILES_.$url)) {
                    $url = substr(slug($path_parts['filename']), 0, 200).'_'.$i.'.'.$path_parts['extension'];
                    $i++;
                }

                chmod(_FOLDER_FILES_, 0777);

                if($path_parts['extension']=="jpg" || $path_parts['extension']=="jpeg"){
                    $src = imagecreatefromjpeg($_FILES['image_disk']['tmp_name']);
                }else{
                    $src = imagecreatefrompng($_FILES['image_disk']['tmp_name']);
                    imagesavealpha($src, true);
                    $color = imagecolorallocatealpha($src, 0, 0, 0, 127);
                    imagefill($src, 0, 0, $color);
                }

                if(!empty($settings['watermark_add'])){
					if(substr($settings['watermark'], 0, 7) != "http://" and substr($settings['watermark'], 0, 8) != "https://"){
						$settings['watermark'] = getFullUrl($settings['watermark']);
					}else{
						$settings['watermark'] = realpath(dirname(__FILE__)).'/../'.$settings['watermark'];
					}
                    $stamp_parts = pathinfo($settings['watermark']);
                    $stamp_parts['extension'] = strtolower($stamp_parts['extension']);

                    if(in_array($stamp_parts['extension'] , ['jpg','jpeg','gif','png'])){
                        if($stamp_parts['extension']=="jpg" || $stamp_parts['extension']=="jpeg"){
                            $stamp = imagecreatefromjpeg($settings['watermark']);
                        }elseif($stamp_parts['extension']=="png"){
                            $stamp = imagecreatefrompng($settings['watermark']);
                        }else{
                            $stamp = imagecreatefromgif($settings['watermark']);
                        }
                        imagecopy($src,$stamp,imagesx($src)-imagesx($stamp) - 5, imagesy($src) - imagesy($stamp) - 5, 0, 0, imagesx($stamp), imagesy($stamp));
                    }
                    if($path_parts['extension']=="jpg" || $path_parts['extension']=="jpeg"){
                        imagejpeg($src,_FOLDER_FILES_.$url,$settings['photo_quality']);
                    }else{
                        imagepng($src,_FOLDER_FILES_.$url);
                    }
                }else{
                    move_uploaded_file($_FILES['image_disk']['tmp_name'], _FOLDER_FILES_.$url);
                }

                list($width,$height)=getimagesize(_FOLDER_FILES_.$url);

                if($settings['photo_max_height'] or $settings['photo_max_width']){
                    $newheight = $height;
                    $newwidth = $width;
                    if($settings['photo_max_height'] and $height >= $settings['photo_max_height']){
                        $newheight = $settings['photo_max_height'];
                    }else{
                        $newheight = $height;
                    }
                    $newwidth = round($newheight / $height * $width);
                    if($settings['photo_max_width'] and $newwidth >= $settings['photo_max_width']){
                        $newwidth = $settings['photo_max_width'];
                    }
                    $newheight = round($newwidth / $width * $height);
                    $new_image = imagecreatetruecolor($newwidth,$newheight);
                    if($path_parts['extension']=="png"){
                        imagesavealpha($new_image, true);
                        $color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
                        imagefill($new_image, 0, 0, $color);
                    }
                    imagecopyresampled($new_image,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
                    if($path_parts['extension']=="jpg" || $path_parts['extension']=="jpeg"){
                        imagejpeg($new_image,_FOLDER_FILES_.$url,$settings['photo_quality']);
                    }else{
                        imagepng($new_image,_FOLDER_FILES_.$url);
                    }
                    imagedestroy($new_image);
                }

                if($height >= 300){
                    $newheight = 300;
                }else{
                    $newheight = $height;
                }
                $newwidth = round($newheight / $height * $width);
                $tmp = imagecreatetruecolor($newwidth,$newheight);
                if($path_parts['extension']=="png"){
                    imagesavealpha($tmp, true);
                    $color = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
                    imagefill($tmp, 0, 0, $color);
                }
                imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
                $thumb = explode('.', $url)[0].'_thumb.'.$path_parts['extension'];

                if($path_parts['extension']=="jpg" || $path_parts['extension']=="jpeg"){
                    imagejpeg($tmp,_FOLDER_FILES_.$thumb,$settings['photo_quality']);
                }else{
                    imagepng($tmp,_FOLDER_FILES_.$thumb);
                }
                imagedestroy($src);

                chmod(_FOLDER_FILES_, 0755);

            }
        }elseif($data['type']=='video_youtube'){
            $type = 'iframe';
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $data['video_youtube'], $match);
            if(isset($match[1])){
                $url = '//www.youtube.com/embed/'.$match[1];
                $thumb = '//img.youtube.com/vi/'.$match[1].'/0.jpg';
            }else{
                return ['status'=>false,'error'=>lang('Incorrect video address from Youtube')];
            }
        }elseif($data['type']=='video_vimeo'){
            $type = 'iframe';
            $vimeo_id = '';
            if(preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $data['video_vimeo'], $output_array)) {
                $vimeo_id = $output_array[5];
            }
            if($vimeo_id){
                $url = 'https://player.vimeo.com/video/'.$vimeo_id;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_URL, 'https://vimeo.com/api/oembed.json?url='.$data['video_vimeo']);
                $result = curl_exec($ch);
                curl_close($ch);
                $obj = json_decode($result);
                $thumb = $obj->thumbnail_url;
            }else{
                return ['status'=>false,'error'=>lang('Incorrect video address from Vimeo')];
            }
        }elseif($data['type']=='video_dailymotion'){
            $type = 'iframe';
            if(strrpos($data['video_dailymotion'], 'dailymotion.com')>0){
                $pos = strrpos($data['video_dailymotion'], '/');
                $url = 'https://www.dailymotion.com/embed/video/'.substr($data['video_dailymotion'], $pos+1);
                $thumb = 'https://www.dailymotion.com/thumbnail/video/'.substr($data['video_dailymotion'], $pos+1);
            }else{
                return ['status'=>false,'error'=>lang('Incorrect video address from DailyMotion')];
            }
        }elseif($data['type']=='video_mp4'){
            $type = 'video';
            $path_parts = pathinfo($_FILES['video_mp4']['name']);
            $path_parts['extension'] = strtolower($path_parts['extension']);

            if(!in_array($path_parts['extension'] , ['mp4'])){
                return ['status'=>false,'error'=>lang('Invalid file type')];
            }elseif($settings['video_max_size'] and $_FILES["video_mp4"]["size"] > $settings['video_max_size']*1024){
                return ['status'=>false,'error'=>lang('The file size is too large. The maximum allowed is').' '.$settings['video_max_size'].' kB'];
            }else{

                $url = substr(slug($path_parts['filename']), 0, 200).'.'.$path_parts['extension'];
                $i = 0;
                while(file_exists(_FOLDER_FILES_.$url)) {
                    $url = substr(slug($path_parts['filename']), 0, 200).'_'.$i.'.'.$path_parts['extension'];
                    $i++;
                }

                chmod(_FOLDER_FILES_, 0777);
                move_uploaded_file($_FILES['video_mp4']['tmp_name'], _FOLDER_FILES_.$url);
                chmod(_FOLDER_FILES_, 0755);
                $thumb = '';

            }
        }else{
            return ['status'=>false,'error'=>lang('Error')];
        }

        if($url){

            if(!empty($data['category_id'])){
                $category_id = $data['category_id'];
            }else{
                $category_id = 0;
            }

            $title = checkWordsBlackList(substr(strip_tags($data['title']),0,$settings['number_char_title']));

            $sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'files`(`user_id`, `title`, `slug`, `waiting_room`, `category_id`, `type`, `url`, `thumb`, `description`, `main_page_date`, `ip`, `date`) VALUES (:user_id,:title,:slug,:waiting_room,:category_id,:type,:url,:thumb,:description,NOW(),:ip,NOW())');
			$sth->bindValue(':user_id', $user->getId(), PDO::PARAM_INT);
			$sth->bindValue(':title', $title, PDO::PARAM_STR);
			$sth->bindValue(':slug', slug($title), PDO::PARAM_STR);
            $sth->bindValue(':waiting_room', 1, PDO::PARAM_INT);
            $sth->bindValue(':category_id', $category_id, PDO::PARAM_INT);
            $sth->bindValue(':type', $type, PDO::PARAM_STR);
            $sth->bindValue(':url', $url, PDO::PARAM_STR);
            $sth->bindValue(':thumb', $thumb, PDO::PARAM_STR);
            $sth->bindValue(':description', checkWordsBlackList(substr(strip_tags($data['description']),0,$settings['number_char_description'])), PDO::PARAM_STR);
			$sth->bindValue(':ip', getClientIp(), PDO::PARAM_STR);
            $sth->execute();
            $id = $db->lastInsertId();

            static::saveTags($id,$data['tags']);
                        
            return ['status'=>true,'path'=>path('file',$id,slug($title))];
        }else{
            return ['status'=>false,'error'=>lang('Error')];
        }
    }

    public static function edit($data,int $id){
        global $db,$settings;

        if(empty($data['category_id'])){
            $data['category_id'] = 0;
        }
        $data['title'] = checkWordsBlackList(substr(strip_tags($data['title']),0,$settings['number_char_title']));

        $sth = $db->prepare('UPDATE `'._DB_PREFIX_.'files` SET `title`=:title, `slug`=:slug, `category_id`=:category_id, `description`=:description WHERE id=:id LIMIT 1');
        $sth->bindValue(':title', $data['title'], PDO::PARAM_STR);
        $sth->bindValue(':slug', slug($data['title']), PDO::PARAM_STR);
        $sth->bindValue(':category_id', $data['category_id'], PDO::PARAM_INT);
        $sth->bindValue(':description', checkWordsBlackList(substr(strip_tags($data['description']),0,$settings['number_char_description'])), PDO::PARAM_STR);
        $sth->bindValue(':id', $id, PDO::PARAM_INT);
        $sth->execute();
 
        $sth = $db->prepare('DELETE FROM '._DB_PREFIX_.'tags_files WHERE `file_id`=:file_id');
        $sth->bindValue(':file_id', $id, PDO::PARAM_INT);
        $sth->execute();

        static::saveTags($id,$data['tags']);
                    
        return ['status'=>true,'path'=>path('file',$id,slug($data['title']))];
    }

    public static function saveTags(int $file_id,$tags){
        global $db;
        if($tags){
            $tags_array = explode(',', strip_tags($tags));
            for($i=0; $i <= count($tags_array) - 1; $i++){
                $name = trim($tags_array[$i]);
                $slug = slug($name);
                if($slug){
                    $tag_exists = false;
                    $sth = $db->prepare('SELECT id FROM '._DB_PREFIX_.'tags WHERE slug=:slug LIMIT 1');
                    $sth->bindValue(':slug', $slug, PDO::PARAM_STR);
                    $sth->execute();
                    if($tag_database = $sth->fetch(PDO::FETCH_ASSOC)){
                        $tag_id = $tag_database['id'];
                    }else{
                        $sth = $db->prepare('INSERT INTO '._DB_PREFIX_.'tags (`name`, `slug`) VALUES (:name,:slug)');
                        $sth->bindValue(':name', $name, PDO::PARAM_STR);
                        $sth->bindValue(':slug', $slug, PDO::PARAM_STR);
                        $sth->execute();
                        $tag_id = $db->lastInsertId();
                    }
                    $sth = $db->prepare('INSERT INTO '._DB_PREFIX_.'tags_files (`tag_id`, `file_id`) VALUES (:tag_id,:file_id)');
                    $sth->bindValue(':tag_id', $tag_id, PDO::PARAM_INT);
                    $sth->bindValue(':file_id', $file_id, PDO::PARAM_INT);
                    $sth->execute();
                }
            }
        }
    }

    public static function setVoice(int $file_id, $voice){
        global $db, $user;
        if($user->getId()){
            $sth = $db->prepare('DELETE FROM '._DB_PREFIX_.'voices WHERE user_id=:user_id AND file_id=:file_id');
            $sth->bindValue(':user_id', $user->getId(), PDO::PARAM_INT);
            $sth->bindValue(':file_id', $file_id, PDO::PARAM_INT);
            $sth->execute();
        }else{
            $sth = $db->prepare('DELETE FROM '._DB_PREFIX_.'voices WHERE user_id=0 AND ip=:ip AND file_id=:file_id');
            $sth->bindValue(':ip', getClientIp(), PDO::PARAM_STR);
            $sth->bindValue(':file_id', $file_id, PDO::PARAM_INT);
            $sth->execute();
        }
        $sth = $db->prepare('INSERT INTO `'._DB_PREFIX_.'voices`(`file_id`, `user_id`, `voice`, `ip`, `date`) VALUES (:file_id,:user_id,:voice,:ip,NOW())');
        $sth->bindValue(':file_id', $file_id, PDO::PARAM_INT);
        $sth->bindValue(':user_id', $user->getId(), PDO::PARAM_INT);
        $sth->bindValue(':voice', $voice, PDO::PARAM_INT);
        $sth->bindValue(':ip', getClientIp(), PDO::PARAM_STR);
        $sth->execute();
    }

    public static function getVoice(int $file_id){
        global $db, $user;
        $voices = ['plus'=>0,'minus'=>0];
        $sth = $db->prepare('SELECT voice, count(1) AS voice_count FROM `'._DB_PREFIX_.'voices` WHERE file_id=:file_id GROUP BY voice');
        $sth->bindValue(':file_id', $file_id, PDO::PARAM_INT);
        $sth->execute();
        while($row = $sth->fetch(PDO::FETCH_ASSOC)){
            if($row['voice']=='1'){
                $voices['plus'] = $row['voice_count'];
            }elseif($row['voice']=='-1'){
                $voices['minus'] = $row['voice_count'];
            }
        }
        return $voices;
    }

    public static function checkPermissions(int $id){
		global $user, $db;
		if($user->getId()){
			if($user->moderator){return true;}
			$sth = $db->prepare('SELECT 1 FROM '._DB_PREFIX_.'files WHERE id=:id AND user_id=:user_id LIMIT 1');
            $sth->bindValue(':user_id', $user->getId(), PDO::PARAM_INT);
            $sth->bindValue(':id', $id, PDO::PARAM_INT);
            $sth->execute();
            if($sth->fetchColumn()){return true;}
            
		}
		return false;
    }

    public static function setMainPage(int $id){
        global $db;
        $sth = $db->prepare('UPDATE '._DB_PREFIX_.'files SET waiting_room=0, main_page_date=now() WHERE id=:id LIMIT 1');
        $sth->bindValue(':id', $id, PDO::PARAM_INT);
        $sth->execute();
    }
    
    public static function remove(int $id){
        global $db;
        $file = static::getFile($id,'admin');
        if($file['type']=='image' or $file['type']=='video'){
            chmod(_FOLDER_FILES_, 0777);
            unlink(_FOLDER_FILES_.$file['url']);
            chmod(_FOLDER_FILES_, 0755);
        }
        if($file['type']=='image'){
            chmod(_FOLDER_FILES_, 0777);
            unlink(_FOLDER_FILES_.$file['thumb']);
            chmod(_FOLDER_FILES_, 0755);
        }
        $sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'tags_files` WHERE file_id=:id');
        $sth->bindValue(':id', $id, PDO::PARAM_INT);
        $sth->execute();
        $sth = $db->prepare('DELETE FROM `'._DB_PREFIX_.'files` WHERE id=:id LIMIT 1');
        $sth->bindValue(':id', $id, PDO::PARAM_INT);
        $sth->execute();
    }
}
