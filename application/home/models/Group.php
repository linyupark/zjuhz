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
}

?>