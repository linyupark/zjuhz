<?php

    /**
     * 每个栏木综合活跃度(成员数+帖子+照片)排列前limit个
     * */

    class Zend_View_Helper_Sortgroup
    {
        function sortgroup($sort, $limit)
        {
            $db = Zend_Registry::get('dbGroup');
            $groups = $db->fetchAll('SELECT `group_id`,`name`
                          FROM `tbl_group`
                          WHERE `sort_id` = ?
                          ORDER BY
                          `member_num` DESC,
                          `topic_num` DESC,
                          `photo_num` DESC 
                          LIMIT '.$limit,(int)$sort);
            $str = '';
            foreach($groups as $g)
            {
                $str .= '<a class="color-'.rand(1,5).'" href="/group/home?gid='.$g['group_id'].'">'.$g['name'].'</a> , ';
            }
            return substr($str,0,-2);
        }
    }