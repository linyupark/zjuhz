<?php

class GroupModel
{
	
	/**
	 * 处理群组最近访问数据
	 */
	static function associate($gid, $cur_gid)
	{
		if($gid == $cur_gid) return false; // 是本群组就忽略
		$gids = self::info($cur_gid, 'associate');
		if($gids == null) $gids = $gid;
		else
		{
			$gid_arr = explode(',', $gids);
			if(in_array($gid, $gid_arr)) return false; // 已经包含就忽略
			else $gid_arr[] = $gid;
			if(count($gid_arr) > 6) array_shift($gid_arr); // 只保留6条数据
			$gids = implode(',', $gid_arr);
		}
		return self::update(array('associate'=>$gids), $cur_gid);
	}
	
    /**
     * 群组列表,从群组分类获取
     * */
    static function getListBySortId($sort_id, $pagesize, $page, $orderby = 'create_time')
    {
        $db = Zend_Registry::get('dbGroup');
        // 获取群组总数
        $row = $db->fetchRow('SELECT COUNT(`group_id`) AS `numrows`
                             FROM `tbl_group` WHERE `sort_id` = ?', $sort_id);
        
        $result['numrows'] = $row['numrows'];
        $offset = ($page-1)*$pagesize;
        
        $result['rows'] = $db->fetchAll('SELECT `name`,`group_id`,`member_num`,`intro`,`tags`
                              FROM `tbl_group` WHERE `sort_id`='.$sort_id.'
                              ORDER BY `'.$orderby.'` DESC LIMIT '.$offset.','.$pagesize);
        return $result;
    }
    
    // 删除邀请
    static function delApply($uid, $gid)
    {
        $db = Zend_Registry::get('dbGroup');
        $apply_str = self::info($gid, 'apply');
        if($apply_str == null) return false;
        if($apply_str == $uid) $data = null;
        else
        {
            $apply_arr = explode(',', $apply_str);
            if(!in_array($gid, $apply_arr)) return false;
			else
			{
				$k = array_search($uid, $apply_arr);
				unset($apply_arr[$k]);
				array_values($apply_arr);
				$data = implode(',', $apply_arr);
			}
        }
        $db->update('tbl_group', array('apply'=>$data), 'group_id ='.$gid);
    }
    
    /**
     * 群组有新的加入申请
     * */
    static function joinApply($uid, $gid)
    {
        if(!GroupMemberModel::isJoin($uid, $gid))
        {
            $db = Zend_Registry::get('dbGroup');
            $apply_list = self::info($gid, 'apply');
            if($apply_list == null)
            $data = array('apply'=>$uid);
            else
            {
                $apply_list = explode(',', $apply_list);
                if(!in_array($uid, $apply_list))
                {
                    $data = array('apply'=>','.$uid);
                }
                else return false;
            }
            return $db->update('tbl_group', $data, 'group_id ='.$gid);
        }
    }
    
    # url获取gid
    static function url2gid($url)
    {
        $db = Zend_Registry::get('dbGroup');
        $row = $db->fetchRow('SELECT `group_id` FROM `tbl_group` WHERE `url` = ?', $url);
        return $row['group_id'];
    }
    
    # 返回群组详细信息
    static function info($gid, $col='*')
    {
        $db = Zend_Registry::get('dbGroup');
        $row = $db->fetchRow('SELECT '.$col.' FROM `tbl_group` WHERE `group_id`=?',$gid);
        if($col == '*') return $row;
        else return $row[$col];
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
    
    static function update($data, $gid)
    {
        $db = Zend_Registry::get('dbGroup');
        return $db->update('tbl_group', $data, 'group_id = '.$gid);
    }
    
    /**
     * 群组的url是否存在
     * */
    static function urlExist($url)
    {
        $db = Zend_Registry::get('dbGroup');
        return $db->fetchRow('SELECT `group_id` FROM `tbl_group` WHERE `url` = ?', $url);
    }
    
    /**
     * 群组名是否存在
     * */
    static function nameExist($name)
    {
        $db = Zend_Registry::get('dbGroup');
        return $db->fetchRow('SELECT `group_id` FROM `tbl_group` WHERE `name` = ?', $name);
    }
    
    /**
     * 群组名是否已经存在（修改时）
     * */
    static function nameExistMod($name, $gid)
    {
        $db = Zend_Registry::get('dbGroup');
        return $db->fetchRow('SELECT `group_id` FROM `tbl_group`
                             WHERE `name` = ? AND `group_id` != ?', array($name, $gid));
    }
    
    # 根据uid获取其群组创建数
    static function createNumByUid($uid)
    {
        $db = Zend_Registry::get('dbGroup');
        $row = $db->fetchRow('SELECT COUNT(`group_id`) AS `create_num` FROM `tbl_group` WHERE `creater` = ?', $uid);
        return $row['create_num'];
    }
}

?>