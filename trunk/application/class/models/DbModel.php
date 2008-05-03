<?php

	class DbModel
	{	
		/**
		 * 获取话题回复
		 *
		 * @param int $topic_id
		 * @param int $pagesize
		 * @param int $page
		 * @return array
		 */
		static function fetchTopicReply($topic_id, $pagesize, $page = 1)
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
		static function topicReplyNumCut($topic_id)
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
		static function topicReplyNumInc($topic_id, $uid)
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
		static function topicViewNumInc($topic_id)
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
		static function fetchTopic($topic_id)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchRow('SELECT * FROM `vi_class_topic` WHERE `class_topic_id` = ?',$topic_id);
		}
		
		/**
		 * 获取通讯录信息
		 *
		 * @param int $class_id
		 * @param int $pagesize 分页大小
		 * @param int $page 当前分页
		 * @return array[numrows]/array[rows]
		 */
		static function fetchAddress($class_id, $pagesize = 10, $page = 1)
		{
			$db = Zend_Registry::get('dbClass');
			$row = $db->fetchRow('SELECT COUNT(`class_addressbook_id`) AS `numrows` 
								  FROM `tbl_class_addressbook` WHERE `class_id` = ?',$class_id);
			$return['numrows'] = $row['numrows'];
			$offset = ($page-1)*$pagesize;
			$return['rows'] = $db->fetchAll('SELECT * FROM `tbl_class_addressbook` WHERE `class_id` = ? LIMIT '.$offset.','.$pagesize, $class_id);
			return $return;
		}

		/**
		 * 更新班级通讯录信息
		 *
		 * @param array $data 需要更新的数据组
		 * @param array $where 更新的条件，只能累积and
		 * @return int 返回更新的数量 0或 1
		 */
		static function updateAddress($data, $where)
		{
			// 解析条件和数据
			if(false == is_array($data) || false == is_array($where)) return false;
			$set = '';
			foreach ($data as $k => $v)
			{
				if(true == is_string($v)) $v = "'{$v}'";
				$set .= "`{$k}` = {$v},";
			}
			$set = substr($set, 0, -1);
			$where = ' WHERE '.implode(' AND ', $where);
			
			$db = Zend_Registry::get('dbClass');
			$stmt = $db->query('UPDATE `tbl_class_addressbook` SET '.$set.$where.' LIMIT 1');
			return $stmt->rowCount();
		}
		
		/**
		 * 查看是否已经初始化了班级通讯录信息
		 *
		 * @param int $class_id 班级id
		 * @param int $uid 用户ID
		 * @return array/false
		 */
		static function isAddressInit($class_id, $uid)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchRow('SELECT `class_addressbook_id` FROM `tbl_class_addressbook` 
								  WHERE `class_id`=? AND `uid`=?',array($class_id,$uid));
		}
		
		/**
		 * 根据会员在班级的权限来返回不同的话题信息列表
		 *
		 * @param int $class_id
		 * @param string $role
		 * @param string $type up置顶/elite精华/...可根据需要再做添加
		 * @return array 班级话题信息数据列
		 */
		static function getClassTopic($class_id, $role = null, $type = '', $pagesize = 1, $page = 1)
		{
			$where = array();
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
		 * 获取在24内登陆过的班级成员
		 *
		 * @param int $class_id 班级id
		 * @param int $time_range 秒数内登陆
		 * @return array 对应的成员姓名和id
		 */
		static function getActivityMember($class_id, $time_range= 86400)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchAll('SELECT `realName`,`class_member_id` FROM `vi_class_member` 
							      WHERE `class_id` = ? AND (`class_member_last_access`+'.$time_range.')> ?',array($class_id,time()));
		}
		
		/**
		 * 查找符合条件的班级
		 *
		 * @param string $query 关键字查询
		 * @param int $year 入学年份
		 * @param string $college 学院名称
		 * @param int $offset 数据偏移
		 * @param int $pagesize 分页大小
		 * @return array[numrows]/array[fetchrows]
		 */
		static function searchClass($query, $year='', $college='', $offset, $pagesize = 20)
		{
			$query = urldecode($query);
			
			$where = ' WHERE ';
			if((int)$year != 0) $where .= '`class_year` = '.$year.' AND ';
			if(!empty($college)) $where .= '`class_college` = "'.$college.'" AND ';
			if(!empty($query)) // 生成模糊查询
			{
				$keyArr = explode(' ', $query);
				foreach ($keyArr as $k => $v)
				{
					$where .= "CONCAT(`realName`,`class_name`,`class_college`) LIKE '%{$v}%' OR";
				}
				$where = substr($where, 0, -2);
			}
			else $where = substr($where, 0, -4);
			if($where == ' WHERE ') $where = '';
			
			$db = Zend_Registry::get('dbClass');
			
			$row = $db->fetchRow('SELECT COUNT(`class_id`) AS `numrows` FROM `vi_class`'.$where);
			
			$return['numrows'] = $row['numrows'];
			
			if($row['numrows'] > 0)
			{
			//开始进行数据匹配
			$return['rows'] = $db->fetchAll('SELECT `class_id`,`class_charge`,`realName`,`class_year`,`class_name`,`class_college` 
												FROM `vi_class`'.$where.' LIMIT '.(int)$offset.','.$pagesize);
			}
			return $return;
		}
		
		/**
		 * 获取指定会员id的申请记录
		 *
		 * @param int $uid
		 * @return array
		 */
		static function getClassApply($uid)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchAll('SELECT `class_name`,`class_id` FROM `vi_class_apply` 
						   		  WHERE `class_member_id` = ?',$uid);
		}
		
		/**
		 * 将指定班级中的某个成员踢出
		 *
		 * @param int $uid
		 * @param int $class_id
		 */
		static function classMemberOut($uid, $class_id)
		{
			$db = Zend_Registry::get('dbClass');
			if($db->delete('tbl_class_member', array('class_member_id = '.(int)$uid, 'class_id = '.(int)$class_id)) > 0)
			{
				$db->update('tbl_class',array('class_member_num'=>new Zend_Db_Expr('class_member_num-1')),'class_id = '.(int)$class_id);
			}
		}
		
		/**
		 * 入班申请删除
		 *
		 * @param int $apply_id
		 */
		static function applyDel($apply_id)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->delete('tbl_class_apply','class_apply_id = '.(int)$apply_id);
		}
		
		/**
		 * 入班申请批准
		 *
		 * @param int $apply_id
		 * @param int $class_id
		 * @param int $member_id
		 */
		static function applyPass($apply_id, $class_id, $member_id)
		{
			$db = Zend_Registry::get('dbClass');
			if($db->insert('tbl_class_member', 
				array('class_id'=>(int)$class_id,'class_member_id'=>(int)$member_id,'class_member_join_time' => time()))>0)
			{
				if(self::applyDel($apply_id)>0)
				{
					if($db->update('tbl_class',
						array('class_member_num'=>new Zend_Db_Expr('class_member_num+1')),'class_id = '.(int)$class_id))
					return true;
				}
			}
			return false;
		}
		/**
		 * 更新班级成员最后访问班级时间
		 *
		 * @param int $uid
		 * @param int $class_id
		 */
		static function updateLastAccessTime($uid, $class_id)
		{
			$db = Zend_Registry::get('dbClass');
			$db->update('tbl_class_member', array('class_member_last_access'=>time()), 
											array('class_id='.$class_id,'class_member_id='.$uid));
		}
		
		/**
		 * 返回某班级的管理员数据集
		 *
		 * @param int $class_id
		 * @return array
		 */
		static function getManagers($class_id)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchAll('SELECT `class_member_id`,`realName` FROM `vi_class_member` 
						          WHERE `class_id` = ? AND `class_member_charge` = 1',$class_id);
		}
		
		/**
		 * 获取某班级的加入申请数
		 *
		 * @param int $class_id
		 * @return int
		 */
		static function getClassJoinApplyNum($class_id)
		{
			$db = Zend_Registry::get('dbClass');
			$row = $db->fetchRow('SELECT COUNT(`class_apply_id`) AS `numrows` FROM `tbl_class_apply` 
								  WHERE `class_id` = ?',$class_id);
			return $row['numrows'];
		}
		/**
		 * 根据班级ID获取所有必要信息
		 *
		 * @param int $class_id
		 * @return array
		 */
		static function getClassInfo($class_id)
		{
			$db = Zend_Registry::get('dbClass');
			$row = $db->fetchRow('SELECT * FROM `vi_class` WHERE `class_id` = ?',$class_id);
			$row['class_managers'] = self::getManagers($class_id);
			$row['class_activity_member'] = self::getActivityMember($class_id);
			return $row;
		}
		
		/**
		 * 初始化用户在class模块的必要信息
		 *
		 * @param array $data
		 * @return int
		 */
		static function userInit($data)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->insert('tbl_class_user', $data);
		}
		
		/**
		 * 判断是否将指定id的用户数据影射到class库中
		 *
		 * @param int $uid
		 * @return array
		 */
		static function isUserInit($uid)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchRow('SELECT `uid`,`realName` FROM `tbl_class_user` 
								  WHERE `uid` = ?',$uid);
		}
		
		/**
		 * 插入新的班级加入申请
		 *
		 * @param array $data
		 * @return int
		 */
		static function insertApply($data)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->insert('tbl_class_apply', $data);
		}
		
		/**
		 * 查看指定的uid是否已经申请了加入
		 *
		 * @param int $class_id
		 * @param int $uid
		 * @return array
		 */
		static function isApplied($class_id,$uid)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchRow('SELECT `class_id` FROM `tbl_class_apply` 
								  WHERE `class_id` = ? AND `class_member_id` = ?',array($class_id,$uid));
		}
		
		/**
		 * 查看指定的uid是否已经加入了classid班级
		 *
		 * @param int $class_id
		 * @param int $uid
		 * @return array
		 */
		static function isJoined($class_id,$uid)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchRow('SELECT `class_id` FROM `tbl_class_member` 
								  WHERE `class_id` = ? AND `class_member_id` = ?',array($class_id,$uid));
		}
		
		/**
		 * 创建班级,并对其他表进行初始化
		 *
		 * @param array $data 基本班级建立数据
		 * @return boolean
		 */
		static function initClass($data)
		{
			$db = Zend_Registry::get('dbClass');
			try {
				$db->insert('tbl_class', $data);
				$class_id = $db->lastInsertId('tbl_class');
				$db->insert('tbl_class_member', array(
					'class_id' => $class_id,
					'class_member_id' => $data['class_charge'],
					'class_member_join_time' => time(),
					'class_member_last_access' => time()
				));
				$db->insert('tbl_class_privacy',array('class_id' => $class_id));
				return $class_id;
			}
			catch (Exception $e)
			{
				return false;
			}
		}
		
		/**
		 * 检查指定memberid是否有对应的班级
		 *
		 * @param int $uid 用户唯一id
		 * @return array / false
		 */
		static function hasClass($uid)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchAll('SELECT `class_id`,`class_name`,`class_college`,`class_year`,`class_charge`,`class_member_charge` 
			                      FROM `vi_class_member` 
			                      WHERE `class_member_id` = ?', (int)$uid);
		}
		
		/**
		 * 检查是否指定的班级名称已经存在
		 *
		 * @param string $classname 班级名称
		 * @return array / false
		 */
		static function isClassExist($classname)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchRow('SELECT `class_id` 
			                      FROM `tbl_class` 
			                      WHERE `class_name` = ?', $classname);
		}
	}