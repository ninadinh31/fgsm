<?php require_once('../Connections/FGSP.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1,2";
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
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_rsApplicationStatus = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsApplicationStatus = $_SESSION['MM_Username'];
}
mysql_select_db($database_FGSP, $FGSP);
$query_rsApplicationStatus = sprintf("SELECT tblusers.Firstname, tblusers.Lastname, tblusers.Username, tblusers.UserLevel, tblusers.`UID`, tblsqapplication.FGDecision FROM tblusers INNER JOIN tblsqapplication ON tblusers.UserID=tblsqapplication.UserID WHERE tblusers.Username = %s", GetSQLValueString($colname_rsApplicationStatus, "text"));
$rsApplicationStatus = mysql_query($query_rsApplicationStatus, $FGSP) or die(mysql_error());
$row_rsApplicationStatus = mysql_fetch_assoc($rsApplicationStatus);
$totalRows_rsApplicationStatus = mysql_num_rows($rsApplicationStatus);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>

</head>

<body>
<h3>Application Status</h3>
<form id="frmAppStatus" name="frmAppStatus" method="post">
  <table width="300" border="1">
    <tbody>
      <tr>
        <td><?php echo $row_rsApplicationStatus['Firstname']; ?></td>
        <td><?php echo $row_rsApplicationStatus['UID']; ?></td>
        <td><?php echo $row_rsApplicationStatus['FGDecision']; ?></td>
      </tr>
    </tbody>
  </table>
</form>
<p>Go back | Log out</p>
</body>
</html>
<?php
mysql_free_result($rsApplicationStatus);
?>
