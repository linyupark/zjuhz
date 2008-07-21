<?php

/**
 *显示在线用户
 */

class Zend_View_Helper_Online
{
    function online()
    {
        $db = Zend_Registry::get('dbGroup');
        $row = $db->fetchRow('SELECT COUNT(`uid`) AS `numrows`  FROM `tbl_group_user`
                        WHERE `group_state` = 0 AND `last_active` > '.(time()-900));
        
        $users = $db->fetchAll('SELECT `uid`,`realName` FROM `tbl_group_user`
                        WHERE `group_state` = 0 AND `last_active` > '.(time()-900).' LIMIT 10');
        
        $str = '<h3 class="pd10">群组在线校友 <small class="f12 quiet">共'.$row['numrows'].'人</small></h3>
        <table>';
        
        if(count($users) == 0)
        {
            $str .= '<tr><p>没有人在线</p></tr>';
        }
        else
        {
            foreach($users as $k => $u)
            {
                if($k%5 == 0)
                $str .= '<tr>';
                 $str .= '<td class="txtc pd10">
                <a href="/group/member/profile?uid='.$u['uid'].'">'.Commons::getUserFace($u['uid'],'small').'</a><br />
                <a onclick="/group/member/profile?uid='.$u['uid'].'" href="javascript:ucard('.$u['uid'].')">'.$u['realName'].'</a></td>';
                if($k%5 == 4)
                $str .= '</tr>';
            }
        }
        
        return $str.'</table>';
    }
}

?>