<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_localhost = "vpc-umd-mysql.db.internal.aws.umd.edu:3306";
$database_localhost = "FGSP";
$username_localhost = "FGSP";
$password_localhost = "FGSP123";
$localhost = mysql_pconnect($hostname_localhost, $username_localhost, $password_localhost) or trigger_error(mysql_error(),E_USER_ERROR);
?>