<?php

class UserModel
{
	
	/**
	 * 改变用户状态
	 * */
	static function stateMod($uid, $change)
	{
		$db = Zend_Registry::get('dbGroup');
		return $db->update('tbl_group_user',
					array('group_state' => $change),
					'uid='.(int)$uid);
	}
	
	/**
	 * 改变用户积分
	 * */
	static function coinMod($uid, $change)
	{
		$db = Zend_Registry::get('dbGroup');
		return $db->update('tbl_group_user',
					array('group_coin' => new Zend_Db_Expr('group_coin'.$change)),
					'uid='.(int)$uid);
	}
	
	/**
	 * 获取群组用户信息
	 **/
	static function fetch($uid, $col = '*')
	{
		$db = Zend_Registry::get('dbGroup');
		$row = $db->fetchRow('SELECT `'.$col.'` FROM `tbl_group_user` WHERE `uid`=?', $uid);
		if($col == '*') return $row;
		else return $row[$col];
	}
	
	/**
	 *将uid设置为有群组用户
	 */
	static function haveGroup($uid)
	{
		$db = Zend_Registry::get('dbGroup');
		return $db->update('tbl_group_user', array('no_group'=>0), 'uid = '.(int)$uid);
	}
	
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