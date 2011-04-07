<?php
/*
 *      empty.php
 *
 */
include 'config.php';

$con=mysql_connect(DB_HOST,DB_UNAME,DB_PASSWD);
mysql_select_db("koltweeps",$con);
mysql_query("TRUNCATE TABLE  `trends`");
mysql_query("TRUNCATE TABLE  `tweets`");
mysql_close($con);
?>
