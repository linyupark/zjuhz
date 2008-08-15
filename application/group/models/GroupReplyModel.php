<?php

class GroupReplyModel
{
	# 获取某用户所有回复数量
	static function count($uid)
	{
		$db = Zend_Registry::get('dbGroup');
		$rows = $db->fetchAll('SELECT DISTINCT `topic_id` FROM `tbl_group_reply` WHERE `user_id` = ?', $uid);
		return count($rows);
	}
	
	static function update($data, $rid)
	{
		$db = Zend_Registry::get('dbGroup');
		return $db->update('tbl_group_reply', $data, 'reply_id = '.(int)$rid);
	}
	
	static function insert($data)
	{
		$db = Zend_Registry::get('dbGroup');
		$db->insert('tbl_group_reply', $data);
		return $db->lastInsertId();
	}
	
	# 话题回复索引
    static function topicIndex($tid, $pagesize, $page)
    {
        $db = Zend_Registry::get('dbGroup');
		
		// 获取话题总数
		$row = $db->fetchRow('SELECT COUNT(`reply_id`) AS `numrows`
                             FROM `tbl_group_reply`
                             WHERE `topic_id` = ?',$tid);
		
		$result['numrows'] = $row['numrows'];
		$offset = ($page-1)*$pagesize;
		
		$rows = $db->fetchAll('SELECT * FROM `vi_group_reply` 
					  WHERE `topic_id` = '.$tid.' 
                      ORDER BY `reply_time` ASC
                      LIMIT '.$offset.','.$pagesize);
		$result['rows'] = $rows;
		
		return $result;
    }
	
	static function fetch($rid, $col = '*')
	{
		$db = Zend_Registry::get('dbGroup');
		return $db->fetchRow('SELECT '.$col.' FROM `tbl_group_reply` WHERE `reply_id` = ?', $rid);
	}
}

?>