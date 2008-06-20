<?php

class Cmd
{
	# 通过sort_id返回其名称
	static function sortName($sort_id)
	{
		return Zend_Registry::get('iniGroup')->sort->name->$sort_id;
	}
	
	# 判断是否在多少小时内
	static function in($time, $hour)
	{
		if(time() - $time < 3600*$hour)
		return true;
		else return false;
	}
	
	# 获取指定gid的图标
	static function groupIcon($gid)
	{
		$file = $_SERVER['DOCUMENT_ROOT'].'static/groups/'.$gid.'/icon.gif';
		if(!file_exists($file)) return '<img src="/static/images/group/default_60_60.gif" />';
		else return '<img src="/static/groups/'.$gid.'/icon.gif" />';
	}
	
	# 创建群组gid相应文件夹
	static function createFolder($gid, $sub = '')
	{
		$path_root = $_SERVER['DOCUMENT_ROOT'].'static/groups/';
		if(!file_exists($path_root)){ @mkdir($path_root, 0777); }
		else
		{
			if(!file_exists($path_root.$gid.'/'))
			@mkdir($path_root.$gid, 0777);
			else
			{
				if($sub != '')
				@mkdir($path_root.$gid.$sub, 0777);
			}
		}
	}
	
	# 获取自己所管理的群组数
	static function managerNum()
	{
		$my_group = Zend_Registry::get('sessGroup')->my;
		if($my_group != null)
		{
			$num = 0;
			foreach($my_group as $k => $v)
			{
				if($v['is_manager'] == 1)
				$num ++ ;
			}
			return $num;
		}
		return 0;
	}
	
	# 获取自己加入群组数
	static function joinNum()
	{
		$my_group = Zend_Registry::get('sessGroup')->my;
		return count($my_group);
	}
	
	# 重新分配自己的群组session
	static function flushGroupSession()
	{
		$user_id = Zend_Registry::get('sessCommon')->login['uid'];
		$groups = GroupMemberModel::fetchByUid($user_id);
		foreach($groups as $k => $v)
		{
			$gid = $v['group_id'];
			Zend_Registry::get('sessGroup')->my[$gid] = $v;
		}
	}
	
	static function icon($name)
	{
		return '<img src="/static/images/group/icons/'.$name.'" />';
	}
}

?>