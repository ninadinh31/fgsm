<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_FGSP = "localhost";
$database_FGSP = "fgsp";
$username_FGSP = "root";
$password_FGSP = "";
$FGSP = mysql_pconnect($hostname_FGSP, $username_FGSP, $password_FGSP) or trigger_error(mysql_error(),E_USER_ERROR); 
?>