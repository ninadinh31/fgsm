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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmAddScholarship")) {
  $insertSQL = sprintf("INSERT INTO tblsqscholarshipdetails (`UID`, ScholarshipID, ScholarshipYear) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['txtUID'], "int"),
                       GetSQLValueString($_POST['selScholarship'], "int"),
                       GetSQLValueString($_POST['txtScholarYear'], "text"));

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
$query_rsScholarship = "SELECT * FROM tblsqscholarship ORDER BY tblsqscholarship.Scholarship";
$rsScholarship = mysql_query($query_rsScholarship, $FGSP) or die(mysql_error());
$row_rsScholarship = mysql_fetch_assoc($rsScholarship);
$totalRows_rsScholarship = mysql_num_rows($rsScholarship);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" id="frmAddScholarship" name="frmAddScholarship" method="POST">
  <p><strong>Add Scholarship(s)</strong></p>
  <table width="873" border="1">
    <tbody>
      <tr>
        <td width="417"><strong>Scholarship List</strong></td>
        <td width="276"><strong>Scholarship Year</strong></td>
        <td width="158"><strong>UID</strong></td>
      </tr>
      <tr>
        <td><select name="selScholarship" id="selScholarship">
        <?php
do {  
?>
            <option value="<?php echo $row_rsScholarship['ScholarshipID']?>"><?php echo $row_rsScholarship['Scholarship']?></option>
            <?php
} while ($row_rsScholarship = mysql_fetch_assoc($rsScholarship));
  $rows = mysql_num_rows($rsScholarship);
  if($rows > 0) {
      mysql_data_seek($rsScholarship, 0);
	  $row_rsScholarship = mysql_fetch_assoc($rsScholarship);
  }
?>
        </select></td>
        <td><input type="text" name="txtScholarYear" id="txtScholarYear"></td>
        <td><input name="txtUID" type="text" id="txtUID" value="<?php echo $row_rstViewRegistration['UID']; ?>"></td>
      </tr>
    </tbody>
  </table>
  <p>
    <input type="submit" name="subAddScholarship" id="subAddScholarship" value="Submit">
  </p>
  <input type="hidden" name="MM_insert" value="frmAddScholarship">
</form>
</body>
</html>
<?php
mysql_free_result($rstViewRegistration);

mysql_free_result($rsScholarship);
?>
