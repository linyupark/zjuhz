<?php

/**
 *显示在线用户
 */

class Zend_View_Helper_Online
{
    function online()
    {
        $str = '<h3 class="pd10">群组在线校友</h3>';
        $db = Zend_Registry::get('dbGroup');
        $users = $db->fetchAll('SELECT `uid`,`realName` FROM `tbl_group_user`
                        WHERE `group_state` = 0 AND `last_active` > '.(time()-900));
        
        if(count($users) == 0)
        {
            $str .= '<p>没有人在线</p>';
        }
        else
        {
            foreach($users as $u)
            {
                 $str .= '<div style="float:left; display:block; padding:5px 10px;" class="txtc">
                <a href="/group/member/profile?id='.$u['uid'].'">'.Commons::getUserFace($u['uid'],'small').'</a><br />
                <a href="/group/member/profile?id='.$u['uid'].'">'.$u['realName'].'</a></div>';
            }
        }
        
        return $str;
    }
}

?>