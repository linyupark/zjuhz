<?php

/* 群组事件 - 该表应做定时清理 */

class GroupEventModel
{
    # 显示所有群组在最近X小时内发生的新事件
    static function fetch($limit)
    {
        $db = Zend_Registry::get('dbGroup');
        return $db->fetchAll('SELECT * FROM `tbl_group_event` 
                                ORDER BY `time` DESC LIMIT '.(int)$limit);
    }
    
    # 在某时间段内群组发生了什么事件
    static function fetchInTime($lastLogin, $gid)
    {
        $db = Zend_Registry::get('dbGroup');
        $rows = $db->fetchAll('SELECT `event_id`,`type` 
                      FROM `tbl_group_event`
                      WHERE `group_id` = ? AND `time` > ?',array($gid, $lastLogin));
        $result['numrows'] = count($rows); // 总事件数
        $result['topic_num'] = 0;
        
        foreach($rows as $row)
        {
            if($row['type'] == 1)
            $result['topic_num'] ++; // 增加主题数
        }
        
        return $result;
    }
    
    # 新群组事件
    static function insert($data)
    {
        $db = Zend_Registry::get('dbGroup');
        $db->insert('tbl_group_event', $data);
        return $db->lastInsertId();
    }
}

?>