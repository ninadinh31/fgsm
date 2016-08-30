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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frmEditBackground")) {
  $updateSQL = sprintf("UPDATE tblsqapplication SET Seniority=%s, Gender=%s, Race=%s, OverallGPA=%s, MajorGPA=%s, Credits=%s, Graduation=%s, UserID=%s, Citizenship=%s WHERE `UID`=%s AND PgmYear=%s",
                       GetSQLValueString($_POST['txtSeniority'], "text"),
                       GetSQLValueString($_POST['txtGender'], "text"),
                       GetSQLValueString($_POST['txtRace'], "text"),
                       GetSQLValueString($_POST['txtOverallGPA'], "double"),
                       GetSQLValueString($_POST['txtMajorGPA'], "double"),
                       GetSQLValueString($_POST['txtCreditsCompleted'], "int"),
                       GetSQLValueString($_POST['txtDate'], "date"),
                       GetSQLValueString($_POST['txtUserID'], "int"),
                       GetSQLValueString($_POST['selCitizenship'], "text"),
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
$query_rsApplication = sprintf("SELECT tblusers.Username, tblsqapplication.Seniority, tblsqapplication.Gender, tblsqapplication.Race, tblsqapplication.OverallGPA, tblsqapplication.MajorGPA, tblsqapplication.Credits, tblsqapplication.Graduation, tblsqapplication.PgmYear, tblsqapplication.UID, tblsqapplication.Citizenship, tblsqapplication.InternshipInterest, tblsqapplication.PastInternLocation, tblsqapplication.InternInFall, tblsqapplication.FallInternLoc, tblsqapplication.OtherComments FROM tblusers INNER JOIN tblsqapplication ON tblusers.UserID=tblsqapplication.UserID WHERE tblusers.Username = %s", GetSQLValueString($colname_rsApplication, "text"));
$rsApplication = mysql_query($query_rsApplication, $FGSP) or die(mysql_error());
$row_rsApplication = mysql_fetch_assoc($rsApplication);
$totalRows_rsApplication = mysql_num_rows($rsApplication);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>EditBackground</title>
<link href="../jQueryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="../jQueryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<script src="../jQueryAssets/jquery-1.11.1.min.js" type="text/javascript"></script>
</head>

<body>
<p><strong style="font-size: 18px; text-align: center;">Edit Background </strong></p>
<form action="<?php echo $editFormAction; ?>" id="frmEditBackground" name="frmEditBackground" method="POST">
  <table width="642" border="1">
    <tbody>
      <tr>
        <td width="329">Seniority</td>
        <td width="297"><select name="txtSeniority" id="txtSeniority">
          <option value="Freshman" <?php if (!(strcmp("Freshman", $row_rsApplication['Seniority']))) {echo "selected=\"selected\"";} ?>>Freshman</option>
          <option value="Sophomore" <?php if (!(strcmp("Sophomore", $row_rsApplication['Seniority']))) {echo "selected=\"selected\"";} ?>>Sophomore</option>
          <option value="Junior" <?php if (!(strcmp("Junior", $row_rsApplication['Seniority']))) {echo "selected=\"selected\"";} ?>>Junior</option>
          <option value="Senior" <?php if (!(strcmp("Senior", $row_rsApplication['Seniority']))) {echo "selected=\"selected\"";} ?>>Senior</option>
        </select></td>
      </tr>
      <tr>
        <td>Gender</td>
        <td><select name="txtGender" id="txtGender">
          <option value="Female" <?php if (!(strcmp("Female", $row_rsApplication['Gender']))) {echo "selected=\"selected\"";} ?>>Female</option>
          <option value="Male" <?php if (!(strcmp("Male", $row_rsApplication['Gender']))) {echo "selected=\"selected\"";} ?>>Male</option>
          <option value="Others" <?php if (!(strcmp("Others", $row_rsApplication['Gender']))) {echo "selected=\"selected\"";} ?>>Others</option>
        </select></td>
      </tr>
      <tr>
        <td>Race</td>
        <td><select name="txtRace" id="txtRace">
          <option value="American Indian or Alaska Native" <?php if (!(strcmp("American Indian or Alaska Native", $row_rsApplication['Race']))) {echo "selected=\"selected\"";} ?>>American Indian or Alaska Native</option>
          <option value="Asian" <?php if (!(strcmp("Asian", $row_rsApplication['Race']))) {echo "selected=\"selected\"";} ?>>Asian</option>
          <option value="Black/African American or Caribbean" <?php if (!(strcmp("Black/African American or Caribbean", $row_rsApplication['Race']))) {echo "selected=\"selected\"";} ?>>Black/African American or Caribbean</option>
          <option value="Native Hawaiian or Other Pacific Islander" <?php if (!(strcmp("Native Hawaiian or Other Pacific Islander", $row_rsApplication['Race']))) {echo "selected=\"selected\"";} ?>>Native Hawaiian or Other Pacific Islander</option>
          <option value="Hispanic or Latino" <?php if (!(strcmp("Hispanic or Latino", $row_rsApplication['Race']))) {echo "selected=\"selected\"";} ?>>Hispanic or Latino</option>
          <option value="White/Caucasian" <?php if (!(strcmp("White/Caucasian", $row_rsApplication['Race']))) {echo "selected=\"selected\"";} ?>>White/Caucasian</option>
          <option value="Arabian or Middle Eastern" <?php if (!(strcmp("Arabian or Middle Eastern", $row_rsApplication['Race']))) {echo "selected=\"selected\"";} ?>>Arabian or Middle Eastern</option>
          <option value="Others" <?php if (!(strcmp("Others", $row_rsApplication['Race']))) {echo "selected=\"selected\"";} ?>>Others</option>
        </select></td>
      </tr>
      <tr>
        <td><label for="txtOverallGPA">Overall GPA</label></td>
        <td><input name="txtOverallGPA" type="text" id="txtOverallGPA" value="<?php echo $row_rsApplication['OverallGPA']; ?>"></td>
      </tr>
      <tr>
        <td>Major GPA</td>
        <td><input name="txtMajorGPA" type="text" id="txtMajorGPA" value="<?php echo $row_rsApplication['MajorGPA']; ?>"></td>
      </tr>
      <tr>
        <td>Latest Credits Completed (Fall 2015/Spring 2016)</td>
        <td><input name="txtCreditsCompleted" type="number" id="txtCreditsCompleted" value="<?php echo $row_rsApplication['Credits']; ?>"> </td>
      </tr>
      <tr>
        <td><label for="txtDate">Expected Graduation Date (Projected)</label></td>
        <td><input name="txtDate" type="date" id="txtDate" value="<?php echo $row_rsApplication['Graduation']; ?>">        
        e.g., 31-May-2017</td>
      </tr>
      <tr>
        <td>Citizenship</td>
        <td><select name="selCitizenship" id="selCitizenship">
          <option value="Single" <?php if (!(strcmp("Single", $row_rsApplication['Citizenship']))) {echo "selected=\"selected\"";} ?>>Single</option>
          <option value="Dual" <?php if (!(strcmp("Dual", $row_rsApplication['Citizenship']))) {echo "selected=\"selected\"";} ?>>Dual</option>
        </select></td>
      </tr>
      <tr>
        <td><input name="txtUserID" type="hidden" id="txtUserID" value="<?php echo $row_rstViewRegistration['UserID']; ?>">
        <input name="txtUID" type="hidden" id="txtUID" value="<?php echo $row_rstViewRegistration['UID']; ?>">
        <input name="txtPgmYear" type="hidden" id="txtPgmYear" value="2017"></td>
        <td><input type="submit" name="subEditBackground" id="subEditBackground" value="Submit"></td>
      </tr>
    </tbody>
  </table>
  <p></p>
  <input type="hidden" name="MM_update" value="frmEditBackground">
</form>
<p>&nbsp;</p>
//<script type="text/javascript">
//
//</script>
</body>
</html>
<?php
mysql_free_result($rstViewRegistration);

mysql_free_result($rsApplication);
?>
