<?php

class GroupReplyModel
{
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
}

?>