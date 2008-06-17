<?php

class UserModel
{
	/**
	 * 判断用户基础数据是否进行过初始化
	 *
	 * @param int $uid
	 * @return boolean
	 */
	static function isInit($uid)
	{
		$db = Zend_Registry::get('dbGroup');
		return $db->fetchRow('SELECT `uid` FROM `tbl_group_user` WHERE `uid` = ?',$uid);
	}
	
	/**
	 * 进行基础用户数据初始化
	 *
	 * @param array $data
	 */
	static function init($data)
	{
		if(!is_array($data)) return FALSE;
		$db = Zend_Registry::get('dbGroup');
		if(FALSE == self::isInit($data['uid']))
		{
			$db->insert('tbl_group_user',array(
				'uid' => $data['uid'],
				'realName' => $data['realName'],
				'sex' => $data['sex']
			));
		}
		return $db->lastInsertId();
	}
}

?>