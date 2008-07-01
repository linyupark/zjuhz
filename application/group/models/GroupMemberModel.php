<?php

class GroupMemberModel
{
    # 更新
    static function update($uid, $data)
    {
        $db = Zend_Registry::get('dbGroup');
        return $db->update('tbl_group_member', $data, 'user_id = '.$uid);
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
    
    # 踢出群组
    static function kickout($uid, $gid)
    {
        $db = Zend_Registry::get('dbGroup');
        $db->delete('tbl_group_member', 'user_id = '.$uid.' AND group_id = '.$gid);
        GroupModel::update(array('member_num'=>new Zend_Db_Expr('member_num - 1')), $gid);
    }
    
    # 是否已经加入gid群组
    static function isJoin($uid, $gid)
    {
        $db = Zend_Registry::get('dbGroup');
        return $db->fetchRow('SELECT `group_id` FROM `tbl_group_member`
                             WHERE `user_id`=? AND `group_id`=?', array($uid,$gid));
    }
    
    # 罗列群组所有成员列表(新加入在前)
    static function fetchAll($gid, $pagesize, $page)
    {
        $db = Zend_Registry::get('dbGroup');
        // 成员总数
        $row = $db->fetchRow('SELECT COUNT(`user_id`) AS `numrows`
                                FROM `tbl_group_member`
                                WHERE `group_id` = ?',$gid);
        
        $result['numrows'] = $row['numrows'];
        $offset = ($page-1)*$pagesize;
        
        $rows = $db->fetchAll('SELECT `active`,`role`,`last_access`,`user_id`,`realName`,`sex`,`join_time` 
                               FROM `vi_group_member`WHERE `group_id` = '.$gid.' ORDER BY `join_time` DESC 
                               LIMIT '.$offset.','.$pagesize);
        
		$result['rows'] = $rows;
		return $result;
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