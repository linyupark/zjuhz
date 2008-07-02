<?php

class UserModel
{
	# 最后活动时间更新
	static function lastActive($uid)
	{
		$db = Zend_Registry::get('dbGroup');
		$db->update('tbl_group_user', array('last_active'=>time()), 'uid='.$uid);
	}
	
	#
	static function comefrom($uid, $gid)
	{
		$db = Zend_Registry::get('dbGroup');
		return $db->update('tbl_group_user', array('come_from'=>$gid), 'uid='.$uid);
	}
	
	# 拒绝邀请
	static function delInvite($uid, $gid)
	{
		$invites = self::fetch($uid, 'group_invite');
		if($invites == null) return false;
		else
		{
			$invite_arr = explode(',', $invites);
			if(!in_array($gid, $invite_arr)) return false;
			else
			{
				$k = array_search($gid, $invite_arr);
				unset($invite_arr[$k]);
				array_values($invite_arr);
				if(count($invite_arr)>0)
				$invites = implode(',', $invite_arr);
				else
				$invites = null;
			}
			$db = Zend_Registry::get('dbGroup');
			$db->update('tbl_group_user', array('group_invite'=>$invites),'uid='.$uid);
			return true;
		}
	}
	
	# 被邀请数
	static function inviteNum($uid)
	{
		$invites = self::fetch($uid, 'group_invite');
		if($invites == null) return 0;
		else
		{
			$invite_arr = explode(',', $invites);
			return count($invite_arr);
		}
	}
	
	# 邀请
	static function invite($gid, $uid)
	{
		$db = Zend_Registry::get('dbGroup');
		$invites = self::fetch($uid, 'group_invite');
		if($invites == null)
		return $db->update('tbl_group_user', array('group_invite'=>$gid), 'uid='.$uid);
		else
		{
			$invite_arr = explode(',', $invites);
			if(!in_array($gid, $invite_arr) || $invite_arr != $gid)
			{
				$invites .= ','.$gid;
				return $db->update('tbl_group_user', array('group_invite'=>$invites), 'uid='.$uid);
			}
		}
	}
	
	/**
	 * 是否已经被该gid群组邀请过
	 * */
	static function isInvited($gid, $uid)
	{
		$invites = self::fetch($uid, 'group_invite');
		if($invites != null)
		{
			$invite_arr = explode(',', $invites);
			if($invite_arr == $gid) return true;
			else return in_array($gid, $invite_arr);
		}
	}
	
	/**
	 * 没加入任何群组的用户显示
	 * */
	static function vag($pagesize, $page)
	{
		$db = Zend_Registry::get('dbGroup');
		
		// 获取总游民数
		$row = $db->fetchRow('SELECT COUNT(`uid`) AS `numrows` FROM `tbl_group_user`
					  WHERE `no_group` = 1');
		
		$result['numrows'] = $row['numrows'];
		$offset = ($page-1)*$pagesize;
		
		$rows = $db->fetchAll('SELECT * FROM `tbl_group_user`
					  WHERE `no_group` = 1 LIMIT '.$offset.','.$pagesize);
		$result['rows'] = $rows;
		
		return $result;
	}
	
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