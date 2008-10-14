<?php

class GroupTopicModel
{
	# 是否有重复的标题
	static function isposted($title)
	{
		$db = Zend_Registry::get('dbGroup');
		$row = $db->fetchRow('SELECT `topic_id` FROM `tbl_group_topic` WHERE `title` = ?', $title);
		if($row['topic_id'] > 0)
		return true;
		else return false;
	}
	
	# 获取某用户所有发表数量
	static function count($uid)
	{
		$db = Zend_Registry::get('dbGroup');
		$row = $db->fetchRow('SELECT COUNT(`topic_id`) AS `numrows` FROM `tbl_group_topic` WHERE `pub_user` = ?', $uid);
		return $row['numrows'];
	}
	
	# 流量排行 - 根据点击数
	static function click($limit)
	{
		$db = Zend_Registry::get('dbGroup');
		return $db->fetchAll('SELECT `topic_id`,`group_id`,`title` 
					  FROM `tbl_group_topic` WHERE `pub_time` > '.(time()-3600*24*8).' 
					  ORDER BY `click_num` DESC LIMIT '.(int)$limit);
	}
	
	# 热帖排行 - 根据回复数
	static function hot($limit)
	{
		$db = Zend_Registry::get('dbGroup');
		return $db->fetchAll('SELECT `topic_id`,`group_id`,`title` 
					  FROM `tbl_group_topic` WHERE `pub_time` > '.(time()-3600*24*8).' 
					  ORDER BY `reply_num` DESC LIMIT '.(int)$limit);
	}
	
	static function update($data, $tid)
	{
		$db = Zend_Registry::get('dbGroup');
		return $db->update('tbl_group_topic', $data, 'topic_id = '.$tid);
	}
	
	# 获取话题信息
	static function fetch($tid, $col = '*')
	{
		$db = Zend_Registry::get('dbGroup');
		$row = $db->fetchRow('SELECT '.$col.' FROM `vi_group_topic` WHERE `topic_id` = ?', $tid);
		if($col != '*') return $row[$col];
		else return $row;
	}
	
	/**
	 * 删除主题以及连带
	 */
	static function delete($gid, $tid)
	{
		$db = Zend_Registry::get('dbGroup');
		$db->delete('tbl_group_topic','topic_id = '.$tid);
		// 更新群组话题数
		GroupModel::update(array(
			'topic_num'=>new Zend_Db_Expr('topic_num - 1')
		), $gid);
	}
	
	# 增加新的话题
	static function add($uid, $gid, $data)
	{
		$db = Zend_Registry::get('dbGroup');
		$row = $db->fetchRow('SELECT `topic_id` FROM `tbl_group_topic`
							 WHERE `title` = ?', $data['title']);
		//避免重复数据
		if($row != false) return false;
		else
		{
			$db->insert('tbl_group_topic', $data);
			$topic_id = $db->lastInsertId();
			// 更新群组话题数
			GroupModel::update(array(
				'topic_num'=>new Zend_Db_Expr('topic_num + 1')
			), $gid);
			// 更新个人群组积分
			UserModel::coinMod($uid, '+1');
			// 增加成员在群组的活跃度
			GroupMemberModel::update($uid, array(
				'active' => new Zend_Db_Expr('active + 1')
			), $gid); 
			// 更新最新事件
			GroupEventModel::insert(array(
				'group_id' => $gid,
				'user_id' => $uid,
				'time' => time(),
				'type' => 1, //类型1为话题
				'url' => '/group/topic/show?gid='.$gid.'&tid='.$topic_id, // 转到事件地址
				'name' => $data['title']
			));
			return $topic_id;
		}
	}
	
	# 群组内部话题索引
    static function index($gid, $pagesize, $page , $elite = null)
    {
        $db = Zend_Registry::get('dbGroup');
		
		// 获取话题总数
		$row = $db->fetchRow('SELECT COUNT(`topic_id`) AS `numrows`
                             FROM `tbl_group_topic`
                             WHERE `group_id` = ?',$gid);
		
		$result['numrows'] = $row['numrows'];
		$offset = ($page-1)*$pagesize;
		
		$order = 'is_top';
		if($elite != null)
		$order = 'is_elite';
		
		$rows = $db->fetchAll('SELECT * FROM `vi_group_topic` 
					  WHERE `group_id` = '.$gid.' 
                      ORDER BY '.$order.' DESC, `reply_time` DESC
                      LIMIT '.$offset.','.$pagesize);
		$result['rows'] = $rows;
		
		return $result;
    }
}

?>