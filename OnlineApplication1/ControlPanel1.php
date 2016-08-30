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

$MM_restrictGoTo = "Login2.php";
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

$colname_rstViewRegistration = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rstViewRegistration = $_SESSION['MM_Username'];
}
mysql_select_db($database_FGSP, $FGSP);
$query_rstViewRegistration = sprintf("SELECT * FROM tblusers WHERE Username = %s", GetSQLValueString($colname_rstViewRegistration, "text"));
$rstViewRegistration = mysql_query($query_rstViewRegistration, $FGSP) or die(mysql_error());
$row_rstViewRegistration = mysql_fetch_assoc($rstViewRegistration);
$totalRows_rstViewRegistration = mysql_num_rows($rstViewRegistration);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<h2>Welcome to the Federal and Global Semester 2016 -  2017 Online Application System</h2>
<p>Welcome
  <?php echo $row_rstViewRegistration['Firstname']; ?>!
</p>
<form id="frmControlPanel" name="frmControlPanel" method="post">
  <table width="260" border="1">
    <tbody>
      <tr>
        <td width="250"><label for="txtUID">UID:</label>
          <a href="Update.php?UID=<?php echo $row_rstViewRegistration['UID'];?>"><?php echo $row_rstViewRegistration['UID'];?></a>              </tr>
      <tr>
        <td><label for="txtFirstname">Firstname:</label>
        <input name="txtFirstname" type="text" id="txtFirstname" value="<?php echo $row_rstViewRegistration['Firstname']; ?>"></td>
      </tr>
      <tr>
        <td><label for="txtLastname">Lastname:</label>
        <input name="txtLastname" type="email" id="txtLastname" value="<?php echo $row_rstViewRegistration['Lastname']; ?>"></td>
      </tr>
      <tr>
        <td><label for="txtEmail">Email:</label>
        <input name="txtEmail" type="text" id="txtEmail" value="<?php echo $row_rstViewRegistration['Email']; ?>"></td>
      </tr>
    </tbody>
  </table>
  <p>Please click links below to complete the application form:</p>
  <p>Section 1 &gt;&gt; <a href="Background1.php?UID=<?php echo $row_rstViewRegistration['UID'];?>">Background Details</a></p>
  <p>Section 2 &gt;&gt; </p>
  <p>&nbsp;</p>
</form>
</body>
</html>
<?php
mysql_free_result($rstViewRegistration);
?>
