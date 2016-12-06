<?php 

	session_start();
	unset($_SESSION['MM_UserGroup']);
	unset($_SESSION['MM_Username']);
	session_destroy();

	header("Location: login.php");

?>