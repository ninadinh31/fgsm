<?php
// *** Logout the current user.
$logoutGoTo = "Login1.php";
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['MM_Username'] = NULL;
$_SESSION['MM_UserGroup'] = NULL;
unset($_SESSION['MM_Username']);
unset($_SESSION['MM_UserGroup']);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>LogOut</title>
</head>

<body>
Thank you! You have logged out successfully.
</body>
</html>