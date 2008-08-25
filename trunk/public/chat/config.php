<?php

/* PLEASE DO NOT ALLOW EVEN ONE BLANK SPACE/LINE IN THIS FILE OUTSIDE '<?php' AND '?>' */

error_reporting(1);import_request_variables('gpc');error_reporting(8);


// --------------------------------- //


$db_type='mysql';                    // database type, *lowercase* ( options: mysql, mysqli, postgre, sqlite )

$db_host='localhost';                // database host ( in most cases 'localhost' )
$db_user='root';                         // database user (not used with sqlite)
$db_pass='123123';                         // database password  (not used  with sqlite)
$db_name='zjuhz_chat';                         // Database [mysql, postgre]. Note that the installation script cannot create a database for you!
$db_sqlite='sqlite/blablite.dat';    // Database [sqlite]: 'path/filename', the file must be CHMODed to 777! )
$skin_dir='skin4x-d';                 // skin directory [no trailing slashes]

$prefix='blte';                      // Table prefix
$error_log='sql_errors.txt';         // CHMODed to 777 file to store sql errors
$persistent_connection='0';          // [0 or 1] Establishes a persistent connection to the SQL server. If you are not sure leave it '0'


$title='杭州浙江大学校友会聊天室';

$format='H:i:s';     // Time format
$timezone=8;         // 0=GMT [default]

$update=6;           // seconds [6-20]
$history=60;         // minutes [5-120]

$ads_fqcy=0;         // inline ads frequency. [0-100] 0 = never, 100 = after each message [acceptable level 5-10]

$no_errs=1;          /* suppress http errors caused by network lags etc 
                     [0 = sometimes errors & error info, 1 = no errors & no info] */

?>