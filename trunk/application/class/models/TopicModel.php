<?php

	/**
	 * 话题相关的数据库操作
	 *
	 */
	class TopicModel
	{
		/**
		 * 根据会员在班级的权限来返回不同的话题信息列表
		 *
		 * @param int $class_id
		 * @param string $role
		 * @param string $type up置顶/elite精华/...可根据需要再做添加
		 * @return array 班级话题信息数据列
		 */
		static function fetchList($class_id, $role = null, $type = '', $pagesize = 1, $page = 1)
		{
			$where = array();
			$where[] = '`class_id` = '.(int)$class_id;
			if($role == 'visitor') $where[] = '`class_topic_public` = 1';
			if($type == 'hot') $where[] = '`class_topic_reply_num` > 10';
			if($type == 'elite') $where[] = '`class_topic_elite` = 1';
			if(count($where) > 0) $where = ' WHERE '.implode(' AND ', $where);
			else $where = '';
			
			$db = Zend_Registry::get('dbClass');
			$row = $db->fetchRow('SELECT COUNT(`class_topic_id`) AS `numrows` 
								  FROM `tbl_class_topic`'.$where);
			$return['numrows'] = $row['numrows'];
			$offset = ($page-1)*$pagesize;
			$return['rows'] = $db->fetchAll('SELECT `class_topic_elite`,`class_topic_id`,`class_topic_author`,`topicAuthor`,`replyAuthor`,
										 `class_topic_title`,`class_topic_pub_time`,`class_topic_reply_num`,`class_topic_up`,
										 `class_topic_view_num`,`class_topic_last_reply_time` 
						   		  FROM `vi_class_topic`'.$where.' 
						   		  ORDER BY `class_topic_up` DESC, `class_topic_pub_time` DESC, `class_topic_reply_num` DESC 
						   		  LIMIT '.$offset.','.$pagesize);
			return $return;
		}
		
		/**
		 * 获取话题回复
		 *
		 * @param int $topic_id
		 * @param int $pagesize
		 * @param int $page
		 * @return array
		 */
		static function fetchReply($topic_id, $pagesize, $page = 1)
		{
			$db = Zend_Registry::get('dbClass');
			$row = $db->fetchRow('SELECT COUNT(`class_reply_id`) AS `numrows` 
												FROM `tbl_class_reply` WHERE `class_topic_id` = ?',$topic_id);

			$return['numrows'] = $row['numrows'];
			$return['rows'] = null;
			
			if($return['numrows'] >0)
			{
				$offset = ($page-1)*$pagesize;
				$return['rows'] = $db->fetchAll('SELECT * FROM `vi_class_reply` 
											 	 WHERE `class_topic_id` = '.$topic_id.' LIMIT '.$offset.','.$pagesize);
			}
			return $return;
		}
		
		/**
		 * 减少话题的回复次数
		 *
		 * @param int $topic_id
		 * @return int
		 */
		static function replyNumCut($topic_id)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->update('tbl_class_topic',
								array('class_topic_reply_num'=>new Zend_Db_Expr('class_topic_reply_num-1')),
								'`class_topic_id` ='.(int)$topic_id);
		}
		
		/**
		 * 增加话题的回复次数
		 *
		 * @param int $topic_id
		 * @return int
		 */
		static function replyNumInc($topic_id, $uid)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->update('tbl_class_topic',
								array('class_topic_reply_num'=>new Zend_Db_Expr('class_topic_reply_num+1'),
									  'class_topic_last_reply_time'=>time(),
									  'class_topic_last_reply_author'=>$uid),
								'`class_topic_id` ='.(int)$topic_id);
		}
		
		/**
		 * 增加话题的阅读次数
		 *
		 * @param int $topic_id
		 * @return int
		 */
		static function viewNumInc($topic_id)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->update('tbl_class_topic',
								array('class_topic_view_num'=>new Zend_Db_Expr('class_topic_view_num+1')),
								'`class_topic_id` ='.(int)$topic_id);
		}
		
		/**
		 * 返回指定id的话题详细信息
		 *
		 * @param int $topic_id
		 * @return array
		 */
		static function fetchDetail($topic_id)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchRow('SELECT * FROM `vi_class_topic` WHERE `class_topic_id` = ?',$topic_id);
		}
	}

?>