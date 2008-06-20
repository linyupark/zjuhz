<?php

class GroupModel
{
    # 返回群组详细信息
    static function info($gid)
    {
        $db = Zend_Registry::get('dbGroup');
        return $db->fetchRow('SELECT * FROM `tbl_group` WHERE `group_id`=?',$gid);
    }
    
    # 返回群组总数
    static function totalNum($sort_id = null)
    {
        $where = '';
        if($sort_id != null) $where = ' WHERE `sort_id`='.(int)$sort_id;
        $db = Zend_Registry::get('dbGroup');
        $row = $db->fetchRow('SELECT COUNT(`group_id`) AS `group_num` FROM `tbl_group`'.$where);
        return $row['group_num'];
    }
    
    static function insert($data)
    {
        $db = Zend_Registry::get('dbGroup');
        $db->insert('tbl_group', $data);
        return $db->lastInsertId();
    }
    
    static function urlExist($url)
    {
        $db = Zend_Registry::get('dbGroup');
        return $db->fetchRow('SELECT `group_id` FROM `tbl_group` WHERE `url` = ?', $url);
    }
    
    # 根据uid获取其群组创建数
    static function createNumByUid($uid)
    {
        $db = Zend_Registry::get('dbGroup');
        $row = $db->fetchRow('SELECT COUNT(`group_id`) AS `create_num` FROM `tbl_group` WHERE `master` = ?', $uid);
        return $row['create_num'];
    }
}

?>