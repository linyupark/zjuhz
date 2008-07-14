<?php

class GroupAlbumModel
{
	# 获取下一张图
	static function next($gid, $cur_aid, $col = '*')
	{
		$db = Zend_Registry::get('dbGroup');
		$row = $db->fetchRow('SELECT '.$col.' FROM `tbl_group_album`
							 WHERE `group_id` = '.$gid.' AND `album_id` < '.$cur_aid. ' ORDER BY `album_id` DESC');
		if($col == '*')
		return $row;
		else return $row[$col];
	}
	
	# 获取上一张图
	static function previous($gid, $cur_aid, $col = '*')
	{
		$db = Zend_Registry::get('dbGroup');
		$row = $db->fetchRow('SELECT '.$col.' FROM `tbl_group_album`
							 WHERE `group_id` = '.$gid.' AND `album_id` > '.$cur_aid. ' ORDER BY `album_id` ASC');
		if($col == '*')
		return $row;
		else return $row[$col];
	}
	
	# 获取单张图片
	static function fetch($aid, $col = '*')
	{
		$db = Zend_Registry::get('dbGroup');
		$row = $db->fetchRow('SELECT '.$col.' FROM `tbl_group_album` WHERE `album_id` = '.$aid);
		if($col == '*')
		return $row;
		else return $row[$col];
	}
	
    # 获取同组图片
    static function fetchBatch($bc)
    {
        $db = Zend_Registry::get('dbGroup');
        return $db->fetchAll('SELECT * FROM `tbl_group_album` WHERE `batch` = "'.$bc.'"');
    }
    
    # 图片信息更新
    static function update($aid, $data)
    {
        $db = Zend_Registry::get('dbGroup');
        return $db->update('tbl_group_album', $data, 'album_id = '.$aid);
    }
    
    # 图片输入
    static function insert($gid, $data)
    {
        $db = Zend_Registry::get('dbGroup');
        $db->insert('tbl_group_album', $data, 'group_id = '.$gid);
        return $db->lastInsertId();
    }
    
    /**
     * 图片显示列表
     */
    static function index($gid, $pagesize, $page)
    {
        $db = Zend_Registry::get('dbGroup');
        // 获取图片总数
		$row = $db->fetchRow('SELECT COUNT(`album_id`) AS `numrows`
                             FROM `tbl_group_album`
                             WHERE `group_id` = ?',$gid);
		
		$result['numrows'] = $row['numrows'];
		$offset = ($page-1)*$pagesize;
		
		$rows = $db->fetchAll('SELECT * FROM `tbl_group_album` 
					  WHERE `group_id` = '.$gid.' 
                      ORDER BY `pubtime` DESC
                      LIMIT '.$offset.','.$pagesize);
		$result['rows'] = $rows;
		
		return $result;
    }
}

?>