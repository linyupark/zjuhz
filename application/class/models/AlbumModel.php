<?php

	class AlbumModel
	{
		/**
		 * 增加相册的回复次数
		 *
		 * @param int $topic_id
		 * @return int
		 */
		static function replyNumInc($album_id)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->update('tbl_class_album',
								array('class_album_reply_num'=>new Zend_Db_Expr('class_album_reply_num+1')),
								'`class_album_id` ='.(int)$album_id);
		}
		
	 	/* 获取相册回复
		 *
		 * @param int $album_id
		 * @param int $pagesize
		 * @param int $page
		 * @return array
		 */
		static function fetchReply($album_id, $pagesize, $page = 1)
		{
			$db = Zend_Registry::get('dbClass');
			$row = $db->fetchRow('SELECT COUNT(`class_reply_id`) AS `numrows` 
												FROM `tbl_class_reply` WHERE `class_album_id` = ?',$album_id);

			$return['numrows'] = $row['numrows'];
			$return['rows'] = null;
			
			if($return['numrows'] >0)
			{
				$offset = ($page-1)*$pagesize;
				$return['rows'] = $db->fetchAll('SELECT * FROM `vi_class_reply` 
											 	 WHERE `class_album_id` = '.$album_id.' LIMIT '.$offset.','.$pagesize);
			}
			return $return;
		}
		
		/**
		 * 获取某相片的详细信息
		 *
		 * @param int $album_id
		 * @return array
		 */
		static function fetchDetail($album_id)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchRow('SELECT * FROM `vi_class_album` WHERE `class_album_id` = ?',$album_id);
		}
		
		/**
		 * 发布每个班级最新的照片信息
		 *
		 * @param int $class_id
		 * @param int $num
		 * @param boolean $pub 是否只获取公开的照片
		 * @return array
		 */
		static function fetchLast($class_id, $num, $pub = false)
		{
			$public = '';
			if(TRUE == $pub) $public = 'AND `class_album_public` = 1 ';
			$db = Zend_Registry::get('dbClass');
			return $db->fetchAll('
				SELECT `realName`,`class_album_filename`,`class_album_name`,`class_album_description` 
				FROM `vi_class_album` 
				WHERE `class_id` = ? '.$public.'
				ORDER BY `class_album_pub_time` DESC 
				LIMIT '.(int)$num, $class_id);
		}
		
		/**
		 * 修改照片的分类
		 *
		 * @param int $album_id 相册ID
		 * @param string $name 新的分类名称
		 * @return int 成功修改的数量
		 */
		static function modCategory($album_id, $name)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->update('tbl_class_album',array('class_album_category'=>$name),'class_album_id = '.$album_id);
		}
		
		/**
		 * 获取指定班级指定目录的相关照片
		 *
		 * @param int $class_id
		 * @param string $category
		 * @param int $pagesize 分页大小
		 * @param int $page 当前页
		 * @return array
		 */
		static function fetchItem($class_id, $category, $pagesize, $page = 1)
		{
			$db = Zend_Registry::get('dbClass');
			
			// 得到记录总数
			$row = $db->fetchRow('SELECT COUNT(`class_album_id`) AS `numrows` 
						   		  FROM `tbl_class_album` 
						   		  WHERE `class_id` = ? AND `class_album_category` = ?',
						   	      array($class_id,$category));
			
			
			$offset = ($page - 1)*$pagesize;
				
			$rows = $db->fetchAll('SELECT `class_album_filename`,
										  `realName`,
										  `class_album_pub_time`,
										  `class_album_filename`,
										  `class_album_reply_num`,
										  `class_album_name`,
										  `class_album_category`,
										  `class_album_id` 
								  FROM `vi_class_album` 
								  WHERE `class_id` = ? AND `class_album_category` = ? 
								  ORDER BY `class_album_pub_time` DESC 
								  LIMIT '.$offset.','.$pagesize,
								  array($class_id,$category));
								  
			return array('numrows' => $row['numrows'],'rows'=>$rows);
		}
		
		/**
		 * 返回指定班级的相册分类信息
		 *
		 * @param int $class_id
		 * @return array
		 */
		static function fetchCategory($class_id)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchAll('SELECT DISTINCT `class_album_category` 
									FROM `tbl_class_album` WHERE `class_id` = ?',$class_id);
		}
		
		/**
		 * 加入新班级照片数据
		 *
		 * @param array $data
		 * @return int
		 */
		static function insert($data)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->insert('tbl_class_album',$data);
		}
	}

?>