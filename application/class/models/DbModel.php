<?php

	class DbModel
	{
		static function getClassInfo($class_id)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchRow('SELECT * FROM `vi_class_base` WHERE `class_id` = ?',$class_id);
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
		 * 根据不同的条件获取每20条记录
		 *
		 * @param int $year
		 * @param string $college
		 * @param int $offset
		 * @return int['numrows']/array['rows']
		 */
		static function getClasses($year = '', $college = '', $offset = 0, $pagesize = 20)
		{
			$db = Zend_Registry::get('dbClass');
			$select = $db->select();
			$select->from('vi_class_charge',array('class_id','class_year','class_charge','class_college','class_name','realName'));
			if((int)$year != 0) $select->where('class_year = ?',$year);
			if(!empty($college)) $select->where('class_college = ?',$college);
			$stmt = $db->query($select);
			$rows = $stmt->fetchAll();
			$return['numrows'] = count($rows);
			$return['rows'] = array_slice($rows, $offset, $pagesize);
			return $return;
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