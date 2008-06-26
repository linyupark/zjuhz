<?php

class Zend_View_Helper_Clickbar
{
    function clickbar($row)
    {
        $str = '<h3 class="mglf10">访问量</h3>';
        $str .= '<ul class="mglf10 pdd10">
            <li>总访问量：'.$row['total_click'].'</li>
            <li>昨日访问：'.$row['yesterday_click'].'</li>
            <li>今日访问：'.$row['today_click'].'</li>
        </ul>';
        return $str;
    }
}

?>