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
            
            $replies = $db->fetchAll('SELECT `tbl_group_reply`.`topic_id`,
                                             `tbl_group_topic`.`group_id`,
                                             `tbl_group_topic`.`title`,
                                             `tbl_group_topic`.`reply_num`
                                    FROM `tbl_group_reply`
                                    LEFT JOIN `tbl_group_topic` ON `tbl_group_reply`.`topic_id` = `tbl_group_topic`.`topic_id`
                                    WHERE `tbl_group_reply`.`user_id` = '.Cmd::myid().'
                                    ORDER BY `tbl_group_reply`.`reply_time` DESC 
                                    LIMIT '.(int)$limit);
            
            $str = '<table class="table-1" width="50%" style="float:left">
            <tr>
                <th class="txtl" width="50%" colspan="2"><b>最近发表的</b></th>
            </tr>';
            foreach($topics as $t)
            {
                $str .= '<tr>';
                $str .= '<td class="pd5 dashBorder"><a target="_blank" class="f12" href="/group/topic/show?gid='.$t['group_id'].'&tid='.$t['topic_id'].'">'.stripslashes($t['title']).'</a></td>';
                $str .= '<td class="dashBorder txtc quiet">回复('.$t['reply_num'].')</td>';
                $str .= '</tr>';
            }
            $str .= '</table>';
            
            $str .= '<table class="table-1" width="50%" style="float:left">
            <tr>
                <th class="txtl" width="50%"><b>最近参与的</b></th>
            </tr>';
            foreach($replies as $r)
            {
                $str .= '<tr>';
                $str .= '<td class="pd5 dashBorder"><a target="_blank" class="f12" href="/group/topic/show?gid='.$r['group_id'].'&tid='.$r['topic_id'].'">'.stripslashes($r['title']).'</a></td>';
                $str .= '</tr>';
            }
            $str .= '</table>';
            return $str;
        }
    }