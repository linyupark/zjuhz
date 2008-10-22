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
    
    # 获取群组h小时新帖
    function getNewTopic($h = 24)
    {
        $now = time();
        $cutime = $now - 3600*$h;
        return $this->_db->fetchAll('SELECT `topic_id`,`group_name`,`group_id`,`title`,`pub_time`
                                    FROM `vi_group_topic` WHERE `pub_time` > '.$cutime.' ORDER BY `pub_time` DESC');
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
    
    /**
	 * 判断用户基础数据是否进行过初始化
	 *
	 * @param int $uid
	 * @return boolean
	 */
	function isInit($uid)
	{
		return $this->_db->fetchRow('SELECT `uid` FROM `tbl_group_user` WHERE `uid` = ?',$uid);
	}
	
	/**
	 * 进行基础用户数据初始化
	 *
	 * @param array $data
	 
	function init($data)
	{
		if(!is_array($data)) return FALSE;
		$db = $this->_db;
		if(FALSE == $this->isInit($data['uid']))
		{
			$db->insert('tbl_group_user',array(
				'uid' => $data['uid'],
				'realName' => $data['realName'],
				'sex' => $data['sex']
			));
		}
		else //临时增加的，要求保持更新其最新的个人信息到群组资料 
		{
			$db->update('tbl_group_user', array(
				'hometown_p' => $data['hometown_p'],
				'hometown_c' => $data['hometown_c'],
				'location_p' => $data['location_p'],
				'location_c' => $data['location_c'],
				'birthday' => strtotime($data['birthday']),
				'year' => $data['year'],
				'college' => $data['college']
			), 'uid = '.$data['uid']);
		}
		return true;
	}
	*/
}

?>