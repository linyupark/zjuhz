<?php

class GroupTagModel
{
    # 获取执行分类下热门的标签
    static function getList($sort_id, $limit = 30)
    {
        $db = Zend_Registry::get('dbGroup');
        return $db->fetchAll('SELECT * FROM `tbl_group_tag` 
                              WHERE `sort_id` = '.$sort_id.' 
                              ORDER BY `rate` DESC LIMIT '.$limit);
    }
    
    # 相册标签增加
    static function albumAdd($gid, $value)
    {
        $db = Zend_Registry::get('dbGroup');
        $row = $db->fetchRow('SELECT `tag_id` FROM `tbl_group_album_tag`
                      WHERE `group_id` = ? AND `name` = ?', array($gid, $value));
        if($row != false) // 数率提高
        $db->update('tbl_group_album_tag',
                        array('rate'=>new Zend_Db_Expr('rate+1')),
                        '`group_id` = '.$gid.' AND `name` = "'.$value.'"');
        else // 新记录
        {
            $db->insert('tbl_group_album_tag', array(
                'group_id' => $gid,
                'name' => $value
            ));
        }
    }
    
    static function insert($sort_id, $tags)
    {
        $db = Zend_Registry::get('dbGroup');
        $tag_arr = explode(' ', $tags);
        foreach($tag_arr as $v)
        {
            $row = $db->fetchRow('SELECT `tag_id` FROM `tbl_group_tag`
                          WHERE `sort_id` = ? AND `name` = ?', array($sort_id, $v));
            if(false != $row) // 有相同的标签，增加rate
            {
                $where[] = 'sort_id='.$sort_id;
                $where[] = "name='{$v}'";
                $db->update('tbl_group_tag', array('rate'=> new Zend_Db_Expr('rate+1')), $where);
            }
            else
            {
                $data = array(
                    'sort_id' => $sort_id,
                    'name' => $v
                );
                $db->insert('tbl_group_tag', $data);
            }
        }
    }
}

?>