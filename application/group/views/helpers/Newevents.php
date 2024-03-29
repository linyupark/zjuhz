<?php
/**
 * 显示群组最近的一些活动信息
 **/
class Zend_View_Helper_Newevents
{
    function newevents($limit)
    {
        $E = new EventsModel('dbEvent');
        $select = $E->select()->order('sign_close ASC')->where('sign_close > '.time())->limit($limit);
        $events = $E->fetchAll($select);
        
        $str = '<table class="table-1" width="100%" id="events_table">
        <tr>
            <th class="txtl">活动名称</th>
            <th class="txtc">报名截止</th>
        </tr>';
        
        if(count($events) > 0)
        {
            foreach($events as $e)
            {
                $str .= '<tr>';
                $str .= '<td class="pd10"><a target="_blank" href="/group/events/show?eid='.$e->event_id.'">'.stripcslashes($e->title).'</a></td>';
                $str .= '<td class="pd10 txtc quiet">'.Lp_Date::timespan(time() - ($e->sign_close - time())).'后</td>';
                $str .= '</tr>';
            }
            
        }
        else
        {
            $str .= '<tr><td class="pd10">目前没有可报名的活动</td></tr>';
        }
        
        $str .= '</table>';
        
        return $str;
    }
}