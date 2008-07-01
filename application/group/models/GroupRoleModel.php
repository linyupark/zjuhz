<?php

class GroupRoleModel
{
    static function update($data, $gid)
    {
        $db = Zend_Registry::get('dbGroup');
        return $db->update('tbl_group_role', $data, 'group_id = '.$gid);
    }
    
    static function insert($data)
    {
        $db = Zend_Registry::get('dbGroup');
        $db->insert('tbl_group_role', $data);
        return $db->lastInsertId();
    }
    
    # 返回不同组的角色名称
    static function name($role, $gid)
    {
        $db = Zend_Registry::get('dbGroup');
        $row = $db->fetchRow('SELECT `'.$role.'` FROM `tbl_group_role` WHERE `group_id`=?',$gid);
        return $row[$role];
    }
    
    # 返回角色图标
    static function icon($role)
    {
        if($role == 'creater')
        return Cmd::icon('medal_gold_1.png');
        if($role == 'manager')
        return Cmd::icon('medal_gold_2.png');
        if($role == 'member')
        return Cmd::icon('medal_gold_3.png');
    }
}

?>