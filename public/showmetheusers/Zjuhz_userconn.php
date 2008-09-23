<?php
/**
 * connection settings
 * replace with your own hostname, database, username and password 
 */
$hostname_conn = "localhost";
$database_conn = "zjuhz_user";
$username_conn = "zjuhz_mysql_dba";
$password_conn = "zjuhz_com_zjuhz_mysql_dba_20080506";
$conn = mysql_pconnect($hostname_conn, $username_conn, $password_conn) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_select_db($database_conn, $conn);
mysql_query("SET NAMES 'utf8'");
?>
