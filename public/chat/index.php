<?php

session_start();

require_once 'config.php';
require_once 'incl/main.inc';

if($_SESSION['common']['role']!='member')
{
    echo "<h3 style='padding:30px;'>请登录本站后再使用聊天室！</h3>";
    redirect('/',0,0);
}


if(!is_dir($skin_dir.'/templates')){die('skin directory unavailable...');}

if(isset($language)&&!headers_sent()){$language=(int)$language;
setcookie('blab_lang',$language,time()+3600*24*365,'/');}

if(isset($offset)&&!headers_sent()){$offset=(int)$offset;
setcookie('blab_time',$offset,time()+3600*24*365,'/');}

redirect('login.php',0,0);
?>