<?php

/** 近段时间我所发布的帖子 */

    class Zend_View_Helper_Mytopic
    {
        function mytopic($limit)
        {
            $db = Zend_Registry::get('dbGroup');
            $topics = $db->fetchAll('SELECT `topic_id`,`group_id`,`title`,`reply_num`
                          FROM `tbl_group_topic`
                          WHERE `pub_user` = '.Cmd::myid().'
                          ORDER BY `pub_time` DESC 
                          LIMIT '.(int)$limit);
            
            if(count($topics) == 0) return '您还没有发布过任何话题';
            
            $str = '<table width="100%">';
            foreach($topics as $t)
            {
                $str .= '<tr>';
                $str .= '<td width="20%" class="txtr pd5"><a class="quiet" href="/group/home?gid='.$t['group_id'].'">['.GroupModel::info($t['group_id'], 'name').']</a></td>';
                $str .= '<td><a target="_blank" class="f14" href="/group/topic/show?gid='.$t['group_id'].'&tid='.$t['topic_id'].'">'.stripslashes($t['title']).'</a></td>';
                $str .= '<td width="20%" class="txtc quiet">回复('.$t['reply_num'].')</td></tr>';
            }
            return $str.'</table>';
        }
    }