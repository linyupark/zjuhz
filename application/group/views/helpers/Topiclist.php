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
            $str .= '<table>
            <tr>
                <th>标题</th>
                <th>点击</th>
                <th>回复</th>
                <th>作者</th>
                <th>最后更新</th>
            </tr>';
            foreach($result['rows'] as $topic)
            {
                $str .= '<tr>
                            <td>'.$topic['title'].'</td>
                            <td>'.$topic['click_num'].'</td>
                            <td>'.$topic['reply_num'].'</td>
                            <td>'.$topic['pub_user_name'].'</td>
                            <td>'.$topic['reply_time'].'</td>
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