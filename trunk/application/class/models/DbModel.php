<?php

	class DbModel
	{	
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
				foreach ($keyArr as $v)
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
												FROM `vi_class`'.$where.' ORDER BY `class_id` DESC LIMIT '.(int)$offset.','.$pagesize);
			}
			return $return;
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
			$row['class_managers'] = MemberModel::getManagers($class_id);
			$row['class_activity_member'] = MemberModel::getActivity($class_id);
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
		 * 创建班级,并对其他表进行初始化
		 *
		 * @param array $data 基本班级建立数据
		 * @return boolean
		 */
		static function initClass($data)
		{
			$db = Zend_Registry::get('dbClass');

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