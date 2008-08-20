<?php

/**
 * 群组数据使用
 * */

class Group
{
    public $_db;
    
    function __construct()
    {
        $iniDb = new Zend_Config_Ini('Ini/Db.ini');
        $params = $iniDb->default->params->toArray();
        $params['dbname'] = 'zjuhz_group';
        $this->_db = Zend_Db::factory($iniDb->default->adapter, $params);
    }
    
    # 从指定的群组中抽取一定数量的主题
    function getTopicFrom($gid, $limit)
    {
        return $this->_db->fetchAll('SELECT `topic_id`,`title` FROM `tbl_group_topic`
                                    WHERE `group_id` = '.$gid.' 
                                    ORDER BY `pub_time` DESC LIMIT '.$limit);
    }
    
    # 获取已经加入群组的信息
    function myGroups()
    {
        return $this->_db->fetchAll('SELECT `name`,`group_id`,`last_access` 
                             FROM `vi_group_member` WHERE `user_id` = ?', Cmd::myid());
    }
    
    function groupNews()
    {
        $groups = $this->myGroups();
        if(count($groups) == 0) return false;
        else
        {
            $events = array();
            foreach($groups as $g)
            {
                $row = $this->_db->fetchRow('SELECT DISTINCT `group_id` 
                                            FROM `tbl_group_event` 
                                            WHERE `group_id` = '.$g['group_id'].' AND `time` > '.$g['last_access']);
                if($row != false)
                {
                    $events[] = array('gid' => $row['group_id'], 'name' => $g['name']);
                }
            }
            return $events;
        }
    }
}

?>