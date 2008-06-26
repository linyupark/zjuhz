<?php

/* 论坛列表显示 */

class Zend_View_Helper_Topiclist
{
    function topiclist($gid, $pagesize, $page, $pagination = false)
    {
        $result = GroupTopicModel::index($gid, $pagesize, $page);
        $str = '<h3 class="pd5 mg10">论坛
        <a style="margin-left:620px" href="/group/topic/new?gid='.$gid.'">发新主题</a></h3>';
        if($result['numrows'] == 0) $str .= '<p class="mglf10">论坛中没有主题</p>';
        else
        {
            $str .= '<table class="table-1 mglf10" width="97%">
            <tr>
                <th width="50%">标题</th>
                <th width="10%" class="txtc">点击</th>
                <th width="10%" class="txtc">回复</th>
                <th width="10%" class="txtc">作者</th>
                <th width="20%" class="txtc">最后更新</th>
            </tr>';
            foreach($result['rows'] as $topic)
            {
                $str .= '<tr>
                            <td class="pd10 dashBorder f14">
                            	<a href="/group/topic/show?gid='.$gid.'&tid='.$topic['topic_id'].'">'.$topic['title'].'</a>
                            </td>
                            <td class="txtc dashBorder">'.$topic['click_num'].'</td>
                            <td class="txtc dashBorder">'.$topic['reply_num'].'</td>
                            <td class="txtc dashBorder"><a href="">'.$topic['pub_user_name'].'</a></td>
                            <td class="txtc dashBorder quiet">
                            	<a href="">'.$topic['reply_user_name'].'</a> '.
                				Lp_Date::timespan($topic['reply_time']).'前 
                				
                			</td>
                        </tr>';
            }
            $str .= '</table>';
            // 是否显示分页
            if($pagination != false)
            {
                Page::create(array(
                    'href_open' => '<a href="">',
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