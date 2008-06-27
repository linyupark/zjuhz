<?php

class Zend_View_Helper_Sortbar
{
    function sortbar()
    {
        $group_num = GroupModel::totalNum();
        $sorts = Zend_Registry::get('iniGroup')->sort->name->toArray();
        $temp = ''; // 群组分类HTML
        foreach($sorts as $k=>$v)
        {
            if($k == 21) $temp .= '<hr class="hr-2" /><b>'.Cmd::icon('coins.png').' 财富</b>';
            if($k == 31) $temp .= '<hr class="hr-2" /><b>'.Cmd::icon('sport_soccer.png').' 体育</b>';
            if($k == 41) $temp .= '<hr class="hr-2" /><b>'.Cmd::icon('music.png').' 娱乐</b>';
            if($k == 51) $temp .= '<hr class="hr-2" /><b>'.Cmd::icon('heart.png').' 交友</b>';
            if($k == 99) $temp .= '<hr class="hr-2" />';
            $temp .= '<li><a href="/group/sort?id='.$k.'">'.$v.'</a><span class="quiet">('.GroupModel::totalNum($k).')</span></li>';
        }
        $str = '<h3 class="pd10">按分类浏览</h3>';
        $str .= '<div class="extInline2 mglf10">目前共有'.$group_num.'个群组</div>';
        $str .= '<div class="mglf10">
            <ul class="sortbar">
                '.$temp.'
            </ul>
        </div>';
        return $str;
    }
}

?>