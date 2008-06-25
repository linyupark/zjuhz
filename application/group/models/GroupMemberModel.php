<?php

class GroupMemberModel
{
    # 更新
    static function update($uid, $data)
    {
        $db = Zend_Registry::get('dbGroup');
        return $db->update('tbl_group_member', $data, 'WHERE `user_id` = '.$uid);
    }
    
    # 加入群组
    static function join($uid, $gid, $data)
    {
        if(false == self::isJoin($uid, $gid))
        {
            return self::insert($data);
        }
        return false;
    }
    
    # 是否已经加入gid群组
    static function isJoin($uid, $gid)
    {
        $db = Zend_Registry::get('dbGroup');
        return $db->fetchRow('SELECT `group_id` FROM `tbl_group_member`
                             WHERE `user_id`=? AND `group_id`=?', array($uid,$gid));
    }
    
    # 罗列群组成员列表
    static function fetchAll($gid)
    {
        $db = Zend_Registry::get('dbGroup');
        return $db->fetchAll('SELECT `active`,`role`,`last_access`,`user_id`,`realName`,`sex`,`join_time` 
                             FROM `vi_group_member` WHERE `group_id` = ?', $gid);
    }
    
    # 判断角色
    static function role($uid, $group_id)
    {
        $db = Zend_Registry::get('dbGroup');
        $row = $db->fetchRow('SELECT `role` FROM `tbl_group_member`
                      WHERE `user_id`=? AND `group_id`=?', array($uid, $group_id));
        return $row['role'];
    }
    
    # 加入新的群组成员信息
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