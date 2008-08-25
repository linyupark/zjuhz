<?php

session_start();

require_once 'config.php';
require_once 'incl/main.inc';

if(isset($blab_name)&&!headers_sent()){
setcookie('blab_name','',$timestamp,'/');
$blab_name=neutral_escape($blab_name,64,'str');
$query='DELETE FROM '.$prefix."_online WHERE usr_name='$blab_name'";
$result=neutral_query($query);
redirect('login.php',0,0);}

if(isset($browser) && !stristr($browser,'khtml')){
$khtml_workaround='<style type="text/css">html,body{overflow:hidden}</style>';}

if(isset($name)){
if(!isset($language)){$language=0;}$language=(int)$language;
if(!isset($offset)){$offset=0;}$offset=(int)$offset;

$name=str_replace("'",'',$name);
$name=str_replace('&','',$name);
$name=str_replace('+','',$name);
$name=str_replace('"','',$name);
$name=str_replace('\\','',$name);
$name=trim($name);
$name=neutral_escape($name,64,'str');

$query='SELECT * FROM '.$prefix."_online WHERE usr_name='$name' AND ($timestamp-rtime)<20";
$result=neutral_query($query);

if(neutral_num_rows($result)>0){redirect('login.php',0,5);}

setcookie('blab_name',$name,time()+3600*24,'/');
setcookie('blab_lang',$language,time()+3600*24*365,'/');
setcookie('blab_time',$offset,time()+3600*24*365,'/');
redirect('blab.php',0,0);}

$query='SELECT usr_name FROM '.$prefix."_online WHERE ($timestamp-rtime)<20";
$result=neutral_query($query);

$separator=', ';
$online=array();
while($row=neutral_fetch_array($result)){
$online[]=output($row['usr_name'],2);}

sort($online);
$how_many=count($online);
$online=implode($separator,$online);
if(strlen($online)<1){$online=0;}

if(!isset($blab_lang)){$blab_lang=2;}
if(!isset($blab_time)){$blab_time=$timezone;}
$version=file('version');$version=$version[0];

include $skin_dir.'/templates/head.pxtm';
include $skin_dir.'/templates/login.pxtm';
?>