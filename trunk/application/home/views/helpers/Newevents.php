<?php
/**
 * 显示群组最近的一些活动信息
 **/
class Zend_View_Helper_Newevents
{
    function newevents($limit)
    {
        $E = new Events();
        $events = $E->_db->fetchAll('SELECT * FROM `tbl_events`
                               WHERE `sign_close` > '.time().'
                               ORDER BY `sign_close`
                               ASC LIMIT '.$limit);

        $str = '<table class="table-1" width="100%">
        <tr>
            <th class="txtl f14"><strong>近期活动</strong></th>
            <th width="30%" class="txtc">报名截止</th>
        </tr>';
        
        if(count($events) > 0)
        {
            foreach($events as $e)
            {
                $str .= '<tr>';
                $str .= '<td class="pd10"><a href="/group/events/show?eid='.$e['event_id'].'">'.stripcslashes($e['title']).'</a></td>';
                $str .= '<td class="pd10 txtc quiet">'.Lp_Date::timespan(time() - ($e['sign_close'] - time())).'后</td>';
                $str .= '</tr>';
            }
            
        }
        else
        {
            $str .= '<tr><td colspan="2" class="pd10">目前没有可报名的活动</td></tr>';
        }
        
        $str .= '</table>';
        
        return $str;
    }
}