<?php

	class DbModel
	{	
		private $_db;
		
		function __construct()
		{
			$this->_db = Zend_Registry::get('dbInfo');
		}
		
		# 根据指定的id获取所有相关的信息
		function getDetailInfo($id)
		{
			return $this->_db->fetchRow('SELECT `tbl_entity`.*,`tbl_user`.`user_name`,`tbl_category`.`category_name` 
								         FROM `tbl_entity`,`tbl_user`,`tbl_category` 
			                             WHERE `tbl_entity`.`user_id` = `tbl_user`.`user_id` 
			                             AND `tbl_entity`.`entity_id` = ? 
			                             AND `tbl_category`.`category_id` = `tbl_entity`.`category_id`', array($id));
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
			//随机抽一个标签
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
		
		static function getProfile()
		{
			$profiler = Zend_Registry::get('dbInfo')->getProfiler();
			echo $totalTime = substr($profiler->getTotalElapsedSecs(), 0, 6).'<br />';
			echo $query = $profiler->getLastQueryProfile()->getQuery();
		}
	}