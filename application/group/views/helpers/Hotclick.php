<?php
/**
 * 显示群组点击最高的x个话题
 **/
class Zend_View_Helper_Hotclick
{
    function hotclick($limit = 5)
    {
        $str = '<h3 class="mglf10" style="color:#B32308">点击排行</h3>';
        $hot_list = GroupTopicModel::click($limit);
        foreach($hot_list as $topic)
        {
            $str .= '<p class="mglf10" style="line-height:150%; padding:3px 0 0 0">
                '.Cmd::icon('bullet_red.png').' <a target="_blank" class="sideBarLink lk-2" href="/group/topic/show?gid='.$topic['group_id'].'&tid='.$topic['topic_id'].'">'.$topic['title'].'</a>
            </p>';
        }
        return $str;
    }
}

?>