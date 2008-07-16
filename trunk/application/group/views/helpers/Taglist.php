<?php

class Zend_View_Helper_Taglist
{
    function taglist($sort_id)
    {
        $str = '<h3 class="pd10">'.Cmd::sortName($sort_id).'
        <small style="font-size:12px; font-weight:normal">下最热门的标签</small></h3>';
        $str .= '<ul style="padding:0 20px 10px 20px;">'.$this->tags_link($sort_id).'</ul>';
        return $str;
    }
    
    function tags_link($sort_id)
    {
        $tags_arr = GroupTagModel::getList($sort_id);
        $str = '';
        if(count($tags_arr) == 0)
        {
            return '<li>该分类还没有群组</li>';
        }
        else
        {
            foreach($tags_arr as $tag)
            {
                $str .= '<li style="display:inline" class="append-1 pd10"><nobr><a href="/group/tag?q='.urlencode($tag['name']).'">'.$tag['name'].'</a> <em>('.$tag['rate'].')</em></nobr></li>';
            }
            return $str;
        }
    }
}

?>