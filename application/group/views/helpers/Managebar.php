<?php

/**
 * 控制菜单
 * */
class Zend_View_Helper_Managebar
{
    function managebar($gid, $controller)
    {
        $str = '<div class="managebar">
            <a href="/group/home?gid='.$gid.'"{home}>'.Cmd::icon('house.png').' 主页</a>
            <a href="/group/topic?gid='.$gid.'"{topic}>'.Cmd::icon('comments.png').' 论坛</a>
            <a href="/group/album?gid='.$gid.'"{album}>'.Cmd::icon('photo.png').' 相册</a>
            <a href="/group/member?gid='.$gid.'"{member}>'.Cmd::icon('group.png').' 成员</a>';
        // 是否是管理员
        if(true == Cmd::isManager($gid))
        {
            $str .= '<a href="/group/manage?gid='.$gid.'"{manage}>'.Cmd::icon('wrench.png').' 管理</a>';
        }
        // 是否为成员
        if(Zend_Registry::get('sessGroup')->my[$gid]['role'] != null)
        {
            $str .= '<a href="/group/invite?gid='.$gid.'"{invite}>'.Cmd::icon('emoticon_grin.png').' 邀请</a>';
        }
        $str .= '</div>';
        $str = str_replace('{'.$controller.'}', ' class="here"', $str);
        $str = preg_replace('/\{([a-z])\}/i', '', $str);
        return $str;
    }
}

?>