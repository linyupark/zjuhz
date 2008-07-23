<?php

/**
 * 操作zjuhz_user数据(临时)
 * */

class User
{
    public $_db;
    
    function __construct()
    {
        $iniDb = new Zend_Config_Ini('Ini/Db.ini');
        $params = $iniDb->default->params->toArray();
        $params['dbname'] = 'zjuhz_user';
        
        $this->_db = Zend_Db::factory($iniDb->default->adapter, $params);
        
        $this->_db->query('SET NAMES "utf8"');
    }
    
    /**
     * 看看有没有这个通讯组
     * */
    function findGroupByName($gname)
    {
        return $this->_db->fetchRow('SELECT `gid` 
                             FROM `tbl_user_address_group`
                             WHERE `uid` = ? AND `gname` = ?',
                             array(Cmd::myid(), $gname));
    }
    
    /**
     * 看看自己的名片里有没有这个人
     * */
    function findCardByName($cname)
    {
        return $this->_db->fetchRow('SELECT `cid`,`gid`,`status`
                             FROM `tbl_user_address_card`
                             WHERE `uid` = ? AND `cname` = ?',
                             array(Cmd::myid(), $cname));
    }
    
    /**
     * 建立临时用于邀请的通讯组
     * */
    function createGroup($data)
    {
        return $this->_db->insert('tbl_user_address_group', $data);
    }
}

?>