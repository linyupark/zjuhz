<?php
	
	/**
	 * 处理班级通讯录的所有数据库操作
	 *
	 */
	class AddressModel
	{
		/**
		 * 获取通讯录信息
		 *
		 * @param int $class_id
		 * @param int $pagesize 分页大小
		 * @param int $page 当前分页
		 * @return array[numrows]/array[rows]
		 */
		static function fetch($class_id, $pagesize = 10, $page = 1)
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
		static function update($data, $where)
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
		static function isInit($class_id, $uid)
		{
			$db = Zend_Registry::get('dbClass');
			return $db->fetchRow('SELECT `class_addressbook_id` FROM `tbl_class_addressbook` 
								  WHERE `class_id`=? AND `uid`=?',array($class_id,$uid));
		}
	}

?>