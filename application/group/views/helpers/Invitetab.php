<?php

class Zend_View_Helper_Invitetab
{
    function invitetab($action, $gid)
    {
        $str = '<div class="extInline mg10 tab hrefspan-8">
            邀请范围：
            <a href="/group/invite/index?gid='.$gid.'"{index}>无组人士</a>
            <a href="/group/invite/search?gid='.$gid.'"{search}>特征搜索</a>
        </div>';
        $str = str_replace('{'.$action.'}', ' class="here"', $str);
        $str = preg_replace('/\{(.*)\}/i', '', $str);
        return $str;
    }
}

?>