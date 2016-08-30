<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "Login1.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<h2>Welcome to Admin Page | <a href="ControlPanel2.php">Control Panel </a>| <a href="Logout1.php">Log Out</a> </h2>
<form id="frmAdmin" name="frmAdmin" method="post">
  <table width="250" border="1">
    <tbody>
      <tr>
        <td width="240">Manage Users</td>
      </tr>
      <tr>
        <td><a href="ViewApplication1.php">View Applications</a></td>
      </tr>
      <tr>
        <td><a href="SearchApplication1.php">Search Application</a></td>
      </tr>
    </tbody>
  </table>
  <p>&nbsp;</p>
  <table width="251" border="1">
    <tbody>
      <tr>
        <td width="245">Manage Internship</td>
      </tr>
      <tr>
        <td><a href="AddOrganization.php">Add Organization</a></td>
      </tr>
      <tr>
        <td><a href="AddInternship.php">Add Internship</a></td>
      </tr>
      <tr>
        <td><a href="ViewInternship.php">View Internship</a></td>
      </tr>
    </tbody>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</form>
<p>&nbsp;</p>
</body>
</html>