<?php

	class DbModel
	{
		/**
		 * 检查指定memberid是否有对应的班级
		 *
		 * @param int $uid 用户唯一id
		 * @return array
		 */
		static function hasClass($uid)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchRow('SELECT `class_id` 
			                      FROM `tbl_class_member` 
			                      WHERE `class_member_id` = ?', (int)$uid);
		}
	}