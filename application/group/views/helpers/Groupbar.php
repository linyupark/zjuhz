<?php

/**
 * 控制菜单
 * */
class Zend_View_Helper_Groupbar
{
    function groupbar($row)
    {
        switch($row['private'])
        {
            case '2': $private = '半公开'; break;
            case '3': $private = '私秘' ; break;
            default : $private = '公开' ; break;
        }
        $str = '<h3 class="pd10">群组信息</h3>';
        $str .= '<ul class="mglf10 pdd10 sidebar">
            <li>创建于：'.Commons::date($row['create_time']).'</li>
            <li>类型：'.$private.'</li>
            <li>成员：'.$row['member_num'].'</li>
            <li>话题：'.$row['topic_num'].'</li>
            <li>照片：'.$row['photo_num'].'</li>
            <li>热心度：<span id="gdevote"></span></li>
            <li>分类：<a href="/group/sort?id='.$row['sort_id'].'">'.Cmd::sortName($row['sort_id']).'</a></li>
            <li>标签：'.Cmd::tagLink($row['tags']).'</li>
        </ul>
        <script>$.getJSON("/group/gdevote/level?gid='.$row['group_id'].'",null,function(data){ $("#gdevote").text(data.point); });</script>';
        return $str;
    }
}

?>