<?php

class GroupMemberModel
{
    # 判断是否为管理员
    static function isManager($uid, $group_id)
    {
        $db = Zend_Registry::get('dbGroup');
        return $db->fetchRow('SELECT `group_id` FROM `tbl_group_member`
                      WHERE `user_id`=? AND `is_manager`=1 AND `group_id`=?', array($uid, $group_id));
    }
    
    # 判断是否为成员
    static function isMember($uid, $group_id)
    {
        $db = Zend_Registry::get('dbGroup');
        return $db->fetchRow('SELECT `group_id` FROM `tbl_group_member`
                             WHERE `user_id`=? AND `group_id`=?', array($uid, $group_id));
    }
    
    static function insert($data)
    {
        $db = Zend_Registry::get('dbGroup');
        $db->insert('tbl_group_member', $data);
        return $db->lastInsertId();
    }
    
    # 获取已经加入群组的信息
    static function fetchByUid($uid)
    {
        $db = Zend_Registry::get('dbGroup');
        return $db->fetchAll('SELECT * FROM `vi_group_member` WHERE `user_id` = ?', $uid);
    }
}

?>