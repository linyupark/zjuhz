<?php

	class DbModel
	{	
		private $_db;
		
		function __construct()
		{
			$this->_db = Zend_Registry::get('dbInfo');
		}
		
		# 返回所有未发布的文章
		function getEntityNoPub()
		{
			return $this->_db->fetchAll('SELECT `e`.`entity_id`,`e`.`entity_title`,`e`.`entity_pub_time`,`u`.`user_name`,`c`.`category_name`  
			                      		FROM `tbl_entity` AS `e`,`tbl_user` AS `u`,`tbl_category` AS `c` 
			                      		WHERE `e`.`user_id` = `u`.`user_id` 
			                      		AND `e`.`category_id` = `c`.`category_id` 
			                      		AND `e`.`entity_pub` = 0');
		}
		
		# 返回总文章数(根据user_id,role)
		function getEntityNum($category_id)
		{
			$where = '';
			if($category_id != 0) $where = 'WHERE `category_id` = '.(int)$category_id;
			
			if(Zend_Registry::get('sessCommon')->role == 'admin')
			{
				$row = $this->_db->fetchRow('SELECT COUNT(`entity_id`) AS `numrows` FROM `tbl_entity`'.$where);
			}
			else 
			{
				if($category_id != 0) $where = ' AND `category_id` = '.(int)$category_id;
				$row = $this->_db->fetchRow('SELECT COUNT(`entity_id`) AS `numrows` FROM `tbl_entity` WHERE `user_id` = '.Zend_Registry::get('sessInfo')->user_id.$where);
			}
			return $row['numrows'];
		}
		
		# 根据指定的id获取所有相关的信息
		function getDetailInfo($id)
		{
			$row = $this->_db->fetchRow('SELECT `tbl_entity`.*,`tbl_user`.`user_name`,`tbl_category`.`category_name` 
								         FROM `tbl_entity`,`tbl_user`,`tbl_category` 
			                             WHERE `tbl_entity`.`user_id` = `tbl_user`.`user_id` 
			                             AND `tbl_entity`.`entity_id` = ? 
			                             AND `tbl_category`.`category_id` = `tbl_entity`.`category_id`', array($id));
			// 调用增加阅读数函数
			$this->increaseViewNum($id, $row['entity_view_num']);
			
			return $row;
		}
		
		# 根据发布时间显示同分类上一篇和下一篇必要信息
		function getSibling($pubTime, $categoryId)
		{
			$rows = array();
			
			$rows['previous'] = $this->_db->fetchRow('SELECT `entity_id`,`entity_title` 
			                                          FROM `tbl_entity` 
			                                          WHERE `entity_pub_time` < ? 
			                                          AND `category_id` = ?
			                                          LIMIT 1', array($pubTime, $categoryId));
			
			$rows['next'] = $this->_db->fetchRow('SELECT `entity_id`,`entity_title` 
			                                      FROM `tbl_entity` 
			                                      WHERE `entity_pub_time` > ? 
			                                      AND `category_id` = ?
			                                      LIMIT 1', array($pubTime, $categoryId));
			return $rows;
		}
		
		# 根据 string $tag 获取相关信息, 分隔号','
		function getSimilarity($id, $tag = null)
		{
			if(!$tag) return false;
			$tagArr = explode(',', $tag);
			$likeStr = ''; // LIKE语句
			foreach ($tagArr as $k => $v)
			{
				$likeStr .= " CONCAT(`entity_title`,`entity_tag`) LIKE '%{$v}%' OR";
			}
			$likeStr = substr($likeStr, 0, -2);
			
			//开始进行数据匹配
			return $this->_db->fetchAll('SELECT `entity_id`,`entity_title`,`entity_pub_time` 
			                      		 FROM `tbl_entity` 
			                      		 WHERE '.$likeStr.' 
			                      		 HAVING `entity_id` != ? 
			                      		 ORDER BY rand() 
			                      		 LIMIT 10', array($id));
		}
		
		# 根据entity id 增加查看次数
		private function increaseViewNum($id, $num)
		{
			$this->_db->update('tbl_entity', array('entity_view_num' => $num+1), '`entity_id` = '.$id);
		}
		
		# 获得一些数据库处理信息
		static function getProfile()
		{
			$profiler = Zend_Registry::get('dbInfo')->getProfiler();
			echo $totalTime = substr($profiler->getTotalElapsedSecs(), 0, 6).'<br />';
			echo $query = $profiler->getLastQueryProfile()->getQuery();
		}
	}