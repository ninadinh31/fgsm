<?php require_once('../Connections/FGSP.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

$MM_authorizedUsers = "0,1";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frmOtherMajor")) {
  $updateSQL = sprintf("UPDATE tblsqapplication SET OtherMajor=%s WHERE `UID`=%s AND PgmYear=%s",
                       GetSQLValueString($_POST['txtOtherMajor'], "text"),
                       GetSQLValueString($_POST['txtUID'], "int"),
                       GetSQLValueString($_POST['txtPgmYear'], "date"));

  mysql_select_db($database_FGSP, $FGSP);
  $Result1 = mysql_query($updateSQL, $FGSP) or die(mysql_error());

  $updateGoTo = "ControlPanel2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

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

$colname_rstViewRegistration = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rstViewRegistration = $_SESSION['MM_Username'];
}
mysql_select_db($database_FGSP, $FGSP);
$query_rstViewRegistration = sprintf("SELECT * FROM tblusers WHERE Username = %s", GetSQLValueString($colname_rstViewRegistration, "text"));
$rstViewRegistration = mysql_query($query_rstViewRegistration, $FGSP) or die(mysql_error());
$row_rstViewRegistration = mysql_fetch_assoc($rstViewRegistration);
$totalRows_rstViewRegistration = mysql_num_rows($rstViewRegistration);

$colname_rsApplication = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsApplication = $_SESSION['MM_Username'];
}
mysql_select_db($database_FGSP, $FGSP);
$query_rsApplication = sprintf("SELECT tblusers.Username, tblsqapplication.PgmYear, tblsqapplication.UID, tblsqapplication.OtherMajor FROM tblusers INNER JOIN tblsqapplication ON tblusers.UserID=tblsqapplication.UserID WHERE tblusers.Username = %s", GetSQLValueString($colname_rsApplication, "text"));
$rsApplication = mysql_query($query_rsApplication, $FGSP) or die(mysql_error());
$row_rsApplication = mysql_fetch_assoc($rsApplication);
$totalRows_rsApplication = mysql_num_rows($rsApplication);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Background1</title>
<link href="../jQueryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="../jQueryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<script src="../jQueryAssets/jquery-1.11.1.min.js" type="text/javascript"></script>
</head>

<body>
<p><strong>Add Other Major</strong></p>
<form action="<?php echo $editFormAction; ?>" id="frmOtherMajor" name="frmOtherMajor" method="POST">
  <table width="642" border="1">
    <tbody>
      <tr>
        <td height="54">Other Major</td>
        <td><textarea name="txtOtherMajor" cols="60" rows="5" id="txtOtherMajor"><?php echo $row_rsApplication['OtherMajor']; ?></textarea></td>
      </tr>
      <tr>
        <td width="329">UserID</td>
        <td width="297"><input name="txtUserID" type="number" id="txtUserID" value="<?php echo $row_rstViewRegistration['UserID']; ?>"></td>
      </tr>
      <tr>
        <td>UID</td>
        <td><input name="txtUID" type="text" id="txtUID" value="<?php echo $row_rstViewRegistration['UID']; ?>" readonly></td>
      </tr>
      <tr>
        <td>Program Year</td>
        <td><input name="txtPgmYear" type="number" id="txtPgmYear" value="2017"></td>
      </tr>
      <tr>
        <td><input name="txtUserID" type="hidden" id="txtUserID" value="<?php echo $row_rstViewRegistration['UserID']; ?>"></td>
        <td><input type="submit" name="subOtherMajor" id="subOtherMajor" value="Submit"></td>
      </tr>
    </tbody>
  </table>
  <p>
    <input type="hidden" name="MM_update" value="frmOtherMajor">
</p>
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rstViewRegistration);

mysql_free_result($rsApplication);
?>
