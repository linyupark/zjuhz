<?php

/**
 * PmModel 站内短信息
 *  
 * @author zjuhz.com
 * @version 
 */


class GroupPmModel extends Zend_Db_Table_Abstract {
	
	/**
	 * The default table name 
	 */
	protected $_name = 'tbl_group_pm';
	protected $_primary = 'pm_id';
	
	/**
	 * 获取数据库对象
	 *
	 * @param ZF存储db对象的名称 $db
	 * @return object
	 */
	public static function _dao($db = 'dbGroup')
	{
		self::setDefaultAdapter($db);
		return self::getDefaultAdapter();
	}
	
	# 返回收件箱内未读的信息数
	public static function no_read_num($myid)
	{
		$row = self::_dao()->fetchRow('SELECT COUNT(`pm_id`) AS `numrows`
							   FROM `tbl_group_pm`
							   WHERE `to` = '.$myid.' AND `to_del` = 0 AND `is_read` = 0');
		if($row == false) return 0;
		else return $row['numrows'];
	}
	
	# 返回收件箱内的信息数
	public static function receive_num($myid)
	{
		$row = self::_dao()->fetchRow('SELECT COUNT(`pm_id`) AS `numrows`
							   FROM `tbl_group_pm`
							   WHERE `to` = '.$myid.' AND `to_del` = 0');
		if($row == false) return 0;
		else return $row['numrows'];
	}
	
	# 返回发件箱内的信息数
	public static function send_num($myid)
	{
		$row = self::_dao()->fetchRow('SELECT COUNT(`pm_id`) AS `numrows`
							   FROM `tbl_group_pm`
							   WHERE `from` = '.$myid.' AND `from_del` = 0');
		if($row == false) return 0;
		else return $row['numrows'];
	}
}
