<?php

/* 群组成员列表显示 */

class Zend_View_Helper_Memberlist
{
    function memberlist($gid, $pagesize, $page, $pagination = false)
    {
        $result = GroupMemberModel::fetchAll($gid, $pagesize, $page);
        
        $str = '<h3 class="pd5 mglf10 mgu10">成员
        <small class="f12" style="font-weight:normal">共'.$result['numrows'].'位成员</small></h3>';
        
        // 显示管理成员
        if($pagination != false)
        {
            $str .= $this->leaders($gid);
        }
        
        foreach($result['rows'] as $member)
        {
            $str .= '<div style="float:left; padding:15px;">
                <a href="/group/member/profile?uid='.$member['user_id'].'">'.Commons::getUserFace($member['user_id']).'</a>
                <p class="txtc"><a href="/group/member/profile?uid='.$member['user_id'].'">'.$member['realName'].'</a></p>
            </div>';
        }
        
        // 是否显示分页
        if($pagination != false)
        {
            Page::$pagesize = $pagesize;
            Page::create(array(
                'href_open' => '<a href="/group/member?gid='.$gid.'&p=%d">',
                'href_close' => '</a>',
                'num_rows' => $result['numrows'],
                'cur_page' => $page
            ));
            $str .= '<div class="pagination" style="float:none;clear:both">'.Page::$page_str.'</div>';
        }
        return $str;
    }
    
    function leaders($gid)
    {
        $leaders = GroupMemberModel::leaders($gid);
        $str = '';
        foreach($leaders as $leader)
        {
            $str .= '<div style="float:left; padding:15px;">
                <a href="/group/member/profile?uid='.$leader['user_id'].'">'.Commons::getUserFace($leader['user_id']).'</a>
                <p class="txtc">'.GroupRoleModel::name($leader['role'], $gid).':<a href="/group/member/profile?uid='.$leader['user_id'].'">'.$leader['realName'].'</a></p>
            </div>';
        }
        return $str.'<hr class="hr-1" />';
    }
}

?>