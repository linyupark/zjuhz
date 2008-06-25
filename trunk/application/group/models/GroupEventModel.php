<?php

/* 该表应做定时清理 */

class GroupEventModel
{
    # 获取群组多少小时内的事件
    static function num($hour, $gid, $type)
    {
        $time = time() - $hour*3600
        $db = Zend_Registry::get('dbGroup');
        $row = $db->fetchRow('SELECT COUNT(`event_id`) AS `numrows`
                      FROM `tbl_group_event`
                      WHERE `group_id` = ? AND `time` > ?',array($gid, $time));
        return $row['numrows'];
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