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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmBackground")) {
  $insertSQL = sprintf("INSERT INTO tblsqapplication (`UID`, Seniority, Gender, Race, OverallGPA, MajorGPA, Credits, Graduation, PgmYear, UserID, Citizenship) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['txtUID'], "int"),
                       GetSQLValueString($_POST['txtSeniority'], "text"),
                       GetSQLValueString($_POST['txtGender'], "text"),
                       GetSQLValueString($_POST['txtRace'], "text"),
                       GetSQLValueString($_POST['txtOverallGPA'], "double"),
                       GetSQLValueString($_POST['txtMajorGPA'], "double"),
                       GetSQLValueString($_POST['txtCreditsCompleted'], "int"),
                       GetSQLValueString($_POST['txtDate'], "date"),
                       GetSQLValueString($_POST['txtPgmYear'], "int"),
                       GetSQLValueString($_POST['txtUserID'], "int"),
                       GetSQLValueString($_POST['selCitizenship'], "text"));

  mysql_select_db($database_FGSP, $FGSP);
  $Result1 = mysql_query($insertSQL, $FGSP) or die(mysql_error());

  $insertGoTo = "ControlPanel2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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

mysql_select_db($database_FGSP, $FGSP);
$query_rsApplication = "SELECT * FROM tblsqapplication";
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
<p><strong style="font-size: 18px; text-align: center;">Background Details</strong></p>
<form action="<?php echo $editFormAction; ?>" id="frmBackground" name="frmBackground" method="POST">
  <table width="642" border="1">
    <tbody>
      <tr>
        <td width="329">Seniority</td>
        <td width="297"><select name="txtSeniority" id="txtSeniority">
          <option value="Freshman">Freshman</option>
          <option value="Sophomore">Sophomore</option>
          <option value="Junior">Junior</option>
          <option value="Senior">Senior</option>
        </select></td>
      </tr>
      <tr>
        <td>Gender</td>
        <td><select name="txtGender" id="txtGender">
          <option value="Female">Female</option>
          <option value="Male">Male</option>
          <option value="Others">Others</option>
        </select></td>
      </tr>
      <tr>
        <td>Race</td>
        <td><select name="txtRace" id="txtRace">
          <option>American Indian or Alaska Native</option>
          <option>Asian</option>
          <option>Black/African American or Caribbean</option>
          <option>Native Hawaiian or Other Pacific Islander</option>
          <option>Hispanic or Latino</option>
          <option>White/Caucasian</option>
          <option>Arabian or Middle Eastern</option>
          <option>Others</option>
        </select></td>
      </tr>
      <tr>
        <td><label for="txtOverallGPA">Overall GPA</label></td>
        <td><input type="text" name="txtOverallGPA" id="txtOverallGPA"></td>
      </tr>
      <tr>
        <td>Major GPA</td>
        <td><input type="text" name="txtMajorGPA" id="txtMajorGPA"></td>
      </tr>
      <tr>
        <td>Latest Credits Completed (Fall 2015/Spring 2016)</td>
        <td><input type="number" name="txtCreditsCompleted" id="txtCreditsCompleted"> </td>
      </tr>
      <tr>
        <td><label for="txtDate1">Expected Graduation Date (Projected)</label></td>
        <td><input type="date" name="txtDate" id="txtDate">        
        e.g., yyyy-mm-dd </td>
      </tr>
      <tr>
        <td>Citizenship</td>
        <td><select name="selCitizenship" id="selCitizenship">
          <option>Single</option>
          <option>Dual</option>
        </select></td>
      </tr>
      <tr>
        <td><input name="txtUserID" type="hidden" id="txtUserID" value="<?php echo $row_rstViewRegistration['UserID']; ?>">
        <input name="txtUID" type="hidden" id="txtUID" value="<?php echo $row_rstViewRegistration['UID']; ?>">
        <input name="txtPgmYear" type="hidden" id="txtPgmYear" value="2017"></td>
        <td><input type="submit" name="subBackground" id="subBackground" value="Submit"></td>
      </tr>
    </tbody>
  </table>
  <p>
    <input type="hidden" name="MM_insert" value="frmBackground">
</p>
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rstViewRegistration);

mysql_free_result($rsApplication);
?>
