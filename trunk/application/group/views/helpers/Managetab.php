<?php

class Zend_View_Helper_Managetab
{
    function managetab($action, $gid)
    {
        $str = '<div class="extInline mg10 tab hrefspan-8">
            管理选项：
            <a href="/group/manage/index?gid='.$gid.'"{index}>常规</a>
            <a href="/group/manage/member?gid='.$gid.'"{member}>成员管理</a>
            <a href="/group/manage/private?gid='.$gid.'"{private}>群组类型</a>
            <a href="/group/manage/custom?gid='.$gid.'"{custom}>个性化定制</a>
            <a href="/group/manage/link?gid='.$gid.'"{link}>友情链接</a>
        </div>';
        $str = str_replace('{'.$action.'}', ' class="here"', $str);
        $str = preg_replace('/\{(.*)\}/i', '', $str);
        return $str;
    }
}

?>