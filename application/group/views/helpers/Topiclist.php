<?php

/* 论坛列表显示 */

class Zend_View_Helper_Topiclist
{
    function topiclist($gid, $pagesize, $page, $pagination = false, $elite)
    {
        $result = GroupTopicModel::index($gid, $pagesize, $page, $elite);
        $str = '<h3 class="pd5 mg10">论坛
        <small class="f12" style="font-weight:normal">共<span style="color:red">'.$result['numrows'].'</span>个主题 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="/group/topic?gid='.$gid.'&elite=1">精华帖</a>
        </small>
        <a style="float:right; margin-top:-15px;" href="/group/topic/new?gid='.$gid.'">发新主题</a></h3>';
        if($result['numrows'] == 0) $str .= '<p class="mglf10">论坛中没有主题</p>';
        else
        {
            $str .= '<table class="table-1 mglf10" width="97%" id="topic_list">
            <tr>
                <th width="50%" colspan="2">标题</th>
                <th width="10%" class="txtc">点击</th>
                <th width="10%" class="txtc">回复</th>
                <th width="10%" class="txtc">作者</th>
                <th width="20%" class="txtc">最后更新</th>
            </tr>';
            foreach($result['rows'] as $topic)
            {
            	$icon = '';
            	if($topic['is_top'] == 1)
            	$icon .= Cmd::icon('bbs_top.jpg');
            	if($topic['is_elite'] == 1)
            	$icon .= Cmd::icon('ruby.png');
                if(time() - $topic['reply_time'] < 3600*24)
                $icon .= Cmd::icon('new.png');
                if($topic['is_nickname'] == 1)
                {
                	$puber = $topic['pub_nickname'];
                	$replyer = $topic['reply_nickname'];
                }
                if($topic['is_nickname'] == 0)
                {
                	$puber = '<a onclick="location.href=\'/group/member/profile?uid='.$topic['pub_user'].'\'" href="javascript:ucard('.$topic['pub_user'].')">'.$topic['pub_user_name'].'</a>';
                	$replyer = '<a onclick="location.href=\'/group/member/profile?uid='.$topic['reply_user'].'\'" href="javascript:ucard('.$topic['reply_user'].')">'.$topic['reply_user_name'].'</a>';
                }
                $str .= '<tr>
                			<td width="10px"><input class="hide" type="checkbox" name="t[]" value="'.$topic['topic_id'].'" /></td>
                            <td class="pd10 dashBorder f12">
                            	'.$icon.' <a target="_blank" href="/group/topic/show?gid='.$gid.'&tid='.$topic['topic_id'].'">'.$topic['title'].'</a>
                            </td>
                            <td class="txtc dashBorder">'.$topic['click_num'].'</td>
                            <td class="txtc dashBorder">'.$topic['reply_num'].'</td>
                            <td class="txtc dashBorder">'.$puber.'</td>
                            <td class="txtc dashBorder quiet">'.$replyer.Lp_Date::timespan($topic['reply_time']).'前</td>
                        </tr>';
            }
            $str .= '</table>';
            // 是否显示分页
            if($pagination != false)
            {
                Page::$pagesize = $pagesize;
                Page::create(array(
                    'href_open' => '<a href="/group/topic?gid='.$gid.'&p=%d">',
                    'href_close' => '</a>',
                    'num_rows' => $result['numrows'],
                    'cur_page' => $page
                ));
                $str .= '<div class="pagination">'.Page::$page_str.'</div>';
            }
        }
        return $str;
    }
}

?>