<?php
/* 最新创建群组 */
class Zend_View_Helper_Newgroup
{
    function newgroup($limit = 3)
    {
        $db = Zend_Registry::get('dbGroup');
        $groups = $db->fetchAll('SELECT `group_id`,`name`,`topic_num` 
                      FROM `tbl_group` 
                      ORDER BY `create_time` DESC LIMIT '.$limit);
        $str = '<h3 class="pd10">新群组</h3>
        <table width="100%"><tr>';
        if( count($groups) > 0 )
        {
            foreach($groups as $group)
            {
                $str .= '<td class="txtc pd10" width="33%" style="line-height:150%">
                <a href="/group/home?gid='.$group['group_id'].'">'.Cmd::groupIcon($group['group_id']).'</a><br />
                <a href="/group/home?gid='.$group['group_id'].'">'.$group['name'].'</a><br />
                主题数:'.$group['topic_num'].'</td>';
            }
        }
        else
        {
            $str .= '<td>还没有任何群组创建</td>';
        }
        $str .= '</tr></table>';
        return $str;
    }
}

?>