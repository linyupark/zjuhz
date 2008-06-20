<?php

class Zend_View_Helper_Managetab
{
    function managetab($action)
    {
        $str = '<div class="extInline mg10 tab hrefspan-8">
            管理选项：
            <a href=""{index}>常规</a>
            <a href=""{member}>成员管理</a>
            <a href=""{private}>群组类型</a>
            <a href=""{custom}>个性化定制</a>
            <a href=""{link}>友情链接</a>
        </div>';
        $str = str_replace('{'.$action.'}', ' class="here"', $str);
        $str = preg_replace('/\{(.*)\}/i', '', $str);
        return $str;
    }
}

?>