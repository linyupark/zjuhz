<?php

/**
 * LP.Framework for PHP v0.2
 * Copyright (c) 2008 ~ 2011 http://www.netvtm.com All rights reserved.
 * Author : Linyupark <llinyupark@gmail.com>
 */

class Lp_Date
{
  #算时间间隔 $seconds : 比较老的时间戳 , $time : 要比较的时间戳 , 省略 $omit : 可选s
  static function timespan($seconds = 1, $time = '', $delimiter = '')
  {
    if (!is_numeric($seconds))
      $seconds = 1;
    if (!is_numeric($time))
      $time = time();

    //如果$sec比指定时间戳还要新
    if ($seconds >= $time)
      $seconds = 1;
    else
      $seconds = $time - $seconds;

    $str = '';
    //年份
    $years = floor($seconds / 31536000);
    if ($years > 0)
    {
      $str .= $years.(($years > 1) ? '年' : '年').$delimiter;
    }
    $seconds -= $years * 31536000;
    //月份
    $months = floor($seconds / 2628000);
    if ($years > 0 || $months > 0)
    {
      if ($months > 0)
      {
        $str .= $months.(($months > 1) ? '月' : '月').$delimiter;
      }
      $seconds -= $months * 2628000;
    }
    //周
    $weeks = floor($seconds / 604800);
    if ($years > 0 || $months > 0 || $weeks > 0)
    {
      if ($weeks > 0)
      {
        $str .= $weeks.(($weeks > 1) ? '周' : '周').$delimiter;
      }
      $seconds -= $weeks * 604800;
    }
    //天
    $days = floor($seconds / 86400);
    if ($months > 0 || $weeks > 0 || $days > 0)
    {
      if ($days > 0)
      {
        $str .= $days.(($days > 1) ? '天' : '天').$delimiter;
      }
      $seconds -= $days * 86400;
    }
    //小时
    $hours = floor($seconds / 3600);
    if ($days > 0 || $hours > 0)
    {
      if ($hours > 0)
      {
        $str .= $hours.(($hours > 1) ? '小时' : '小时').$delimiter;
      }
      $seconds -= $hours * 3600;
    }
    //分钟
    $minutes = floor($seconds / 60);
    if ($days > 0 || $hours > 0 || $minutes > 0)
    {
      if ($minutes > 0)
      {
        $str .= $minutes.(($minutes > 1) ? '分钟' : '分钟').$delimiter;
      }
      $seconds -= $minutes * 60;
    }
    //秒
    if ($str == '')
    {
      $str .= $seconds.(($seconds > 1) ? '秒' : '秒').$delimiter;
    }
    if ($delimiter != "")
      $str = substr($str, 0,  - 1);
    return $str;
  }

  #Mysql数据库的Timestamp转成Unix
  static function mysql_to_unix($time = '')
  {
    $time = str_replace('-', '', $time);
    $time = str_replace(':', '', $time);
    $time = str_replace(' ', '', $time);
    return mktime(substr($time, 8, 2), substr($time, 10, 2), substr($time, 12, 2), substr($time, 4, 2), substr($time, 6, 2), substr($time, 0, 4));
  }

  #指定年月里有多少天
  static function days_in_month($month = 0, $year = '')
  {
    if ($month < 1 || $month > 12)
      return 0;
    if (!is_string($year) || strlen($year) != 4)
      $year = date("Y");
    if ($month == 2)
    {
      //是否为闰年
      if ($year % 400 == 0 || ($year % 4 == 0 && $year % 100 != 0))
        return 29;
    }
    $days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    return $days_in_month[$month - 1];
  }
}

?>
