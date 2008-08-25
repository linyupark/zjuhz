<?php 
require_once 'config.php';
require_once 'incl/main.inc';

$version=file('version');$version=$version[0];

if(isset($browser) && !stristr($browser,'khtml')){
$khtml_workaround='<style type="text/css">html,body{overflow:hidden}</style>';}

$msg='';

if(isset($why)){
if(eregi('[^a-z_0-9]',$why)){$why=' [details: URL]';}
switch($why){
case 'link':$msg=$lang['error_lnk'];break;
case 'no_permission':$msg=$lang['error_nop'];break;
case 'browser':$msg=$lang['error_bro'];break;
case 'NaN':$msg=$lang['error_nan'].'<br /><br /><a href="./"><b>'.$lang['continue'].'</b></a>';break;
default:$msg=$lang['error_unk'].'<br />'.$why;break;}
}

include $skin_dir.'/templates/head.pxtm';
include $skin_dir.'/templates/info.pxtm';

?>