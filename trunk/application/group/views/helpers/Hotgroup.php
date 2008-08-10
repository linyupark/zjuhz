<?php

    class Zend_View_Helper_Hotgroup
    {
        function hotgroup($limit = 5)
        {
            $db = Zend_Registry::get('dbGroup');
            $groups = $db->fetchAll('SELECT `group_id`,`name`,`total_click`
                          FROM `tbl_group` ORDER BY `total_click` DESC LIMIT '.(int)$limit);
            $str = '<h3 class="pd10">热门群组</h3>';
            foreach($groups as $k => $g)
            {
                $str .= '<div class="mglf10" style="line-height:150%; padding:3px 0 0 0">
                    '.Cmd::groupIcon($g['group_id'], 'width="30" height="30"').' <a href="/group/home?gid='.$g['group_id'].'">'.$g['name'].'</a>
                <span style="color:#D7E4EA; font-weight:bold; font-size:'.(22-$k*2).'px;">'.$g['total_click'].'</span></div>';
            }
            return $str;
        }
    }