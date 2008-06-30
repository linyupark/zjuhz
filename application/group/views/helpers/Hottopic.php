<?php
/**
 * 显示群组最热x个话题
 **/
class Zend_View_Helper_Hottopic
{
    function hottopic($limit = 10)
    {
        $str = '<h3 class="mglf10" style="color:#B32308">近期热门话题</h3>';
        $hot_list = GroupTopicModel::hot($limit);
        foreach($hot_list as $topic)
        {
            $str .= '<p class="mglf10" style="line-height:150%; padding:5px 0 3px 0">
                <a href="">['.GroupModel::info($topic['group_id'],'name').']</a> 
                <a href="/group/topic?gid='.$topic['group_id'].'&tid='.$topic['topic_id'].'">'.$topic['title'].'</a>
            </p>';
        }
        return $str;
    }
}

?>