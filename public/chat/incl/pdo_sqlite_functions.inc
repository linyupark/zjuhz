<?php

$db=0;

function neutral_escape($a,$b,$c){
if(strlen($a)>$b){$a='';}
if($c=='int'){$a=(int)$a;}
else{
if(get_magic_quotes_gpc()){
$a=stripslashes($a);}
if($c!='txt'){
$a=str_replace("\r",'',$a);
$a=str_replace("\n",' ',$a);}
$a=str_replace("\0",'',$a);
$a=str_replace("'","''",$a);
}$a=trim($a);return $a;}

function neutral_dbconnect(){
global $db,$db_sqlite;
$db = new PDO("sqlite:$db_sqlite");}

function neutral_query($q){
global $db,$queries;$queries+=1;
$sth = $db->prepare($q);
$sth->execute();
return $sth;}

function neutral_fetch_array($q){
$r=$q->fetch();return $r;}

function neutral_num_rows($q){
global $query;
$exp=explode(' FROM ',$query);$exp[0]='SELECT COUNT(*)';$exp=implode(' FROM ',$exp);
$r=neutral_query($exp);
$r=$r->fetchColumn();return $r;}

$autoinc='NULL';

?>