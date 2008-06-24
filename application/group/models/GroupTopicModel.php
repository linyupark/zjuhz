<?php

class GroupTopicModel
{
    static function index($gid, $pagesize, $page)
    {
        $db = Zend_Registry::get('dbGroup');
		
		// 获取总游民数
		$row = $db->fetchRow('SELECT COUNT(`topic_id`) AS `numrows`
                             FROM `tbl_group_topic`
                             WHERE `group_id` = ?',$gid);
		
		$result['numrows'] = $row['numrows'];
		$offset = ($page-1)*$pagesize;
		
		$rows = $db->fetchAll('SELECT * FROM `vi_group_topic` 
					  WHERE `group_id` = '.$gid.'
                      ORDER BY `is_top` DESC, `pub_time` DESC
                      LIMIT '.$offset.','.$pagesize);
		$result['rows'] = $rows;
		
		return $result;
    }
}

?>