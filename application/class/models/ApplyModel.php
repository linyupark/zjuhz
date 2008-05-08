<?php

	/**
	 * 处理班级加入申请相关的数据库处理
	 *
	 */
	class ApplyModel
	{
		/**
		 * 获取指定会员id的申请记录
		 *
		 * @param int $uid
		 * @return array
		 */
		static function fetch($uid)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchAll('SELECT `class_name`,`class_id` FROM `vi_class_apply` 
						   		  WHERE `class_member_id` = ?',$uid);
		}
		
		/**
		 * 入班申请删除
		 *
		 * @param int $apply_id
		 */
		static function delete($apply_id)
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
		static function pass($apply_id, $class_id, $member_id)
		{
			$db = Zend_Registry::get('dbClass');
			if($db->insert('tbl_class_member', 
				array('class_id'=>(int)$class_id,'class_member_id'=>(int)$member_id,'class_member_join_time' => time()))>0)
			{
				if(self::delete($apply_id)>0)
				{
					if($db->update('tbl_class',
						array('class_member_num'=>new Zend_Db_Expr('class_member_num+1')),'class_id = '.(int)$class_id))
					return true;
				}
			}
			return false;
		}
		
		/**
		 * 获取某班级的加入申请数
		 *
		 * @param int $class_id
		 * @return int
		 */
		static function fetchNum($class_id)
		{
			$db = Zend_Registry::get('dbClass');
			$row = $db->fetchRow('SELECT COUNT(`class_apply_id`) AS `numrows` FROM `tbl_class_apply` 
								  WHERE `class_id` = ?',$class_id);
			return $row['numrows'];
		}
		
		/**
		 * 插入新的班级加入申请
		 *
		 * @param array $data
		 * @return int
		 */
		static function insert($data)
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
	}

?>