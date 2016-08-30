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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmMarketing")) {
  $insertSQL = sprintf("INSERT INTO tblsqmarketingdetails (`UID`, PgmYear, MarketingID, MarketingLocation, OtherMarketing) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['txtUID'], "int"),
                       GetSQLValueString($_POST['txtPgmYear'], "date"),
                       GetSQLValueString($_POST['selMarketing'], "int"),
                       GetSQLValueString($_POST['txtLocation'], "text"),
                       GetSQLValueString($_POST['txtOtherMarketing'], "text"));

  mysql_select_db($database_FGSP, $FGSP);
  $Result1 = mysql_query($insertSQL, $FGSP) or die(mysql_error());

  $insertGoTo = "ControlPanel2.php#tab-8";
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
$query_rsMarketingList = "SELECT * FROM tblsqmarketing";
$rsMarketingList = mysql_query($query_rsMarketingList, $FGSP) or die(mysql_error());
$row_rsMarketingList = mysql_fetch_assoc($rsMarketingList);
$totalRows_rsMarketingList = mysql_num_rows($rsMarketingList);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<p>How did you hear about the Federal Semester and/or Global Semester Program</p>
<form action="<?php echo $editFormAction; ?>" id="frmMarketing" name="frmMarketing" method="POST">
  <table width="1024" border="1">
    <tbody>
      <tr>
        <td width="198"><strong>Source</strong></td>
        <td width="219"><strong>List Name/Location</strong></td>
        <td width="350"><strong>Other (e.g. display case, classroom/student organization announcement, etc)</strong></td>
        <td width="140"><strong>UID</strong></td>
        <td width="83"><strong>PgmYear</strong></td>
      </tr>
      <tr>
        <td><select name="selMarketing" id="selMarketing">
          <?php
do {  
?>
          <option value="<?php echo $row_rsMarketingList['MarketingID']?>"><?php echo $row_rsMarketingList['MarketingType']?></option>
          <?php
} while ($row_rsMarketingList = mysql_fetch_assoc($rsMarketingList));
  $rows = mysql_num_rows($rsMarketingList);
  if($rows > 0) {
      mysql_data_seek($rsMarketingList, 0);
	  $row_rsMarketingList = mysql_fetch_assoc($rsMarketingList);
  }
?>
        </select></td>
        <td><input type="text" name="txtLocation" id="txtLocation"></td>
        <td><input type="text" name="txtOtherMarketing" id="txtOtherMarketing"></td>
        <td><input name="txtUID" type="text" id="txtUID" value="<?php echo $row_rstViewRegistration['UID']; ?>"></td>
        <td><input name="txtPgmYear" type="text" id="txtPgmYear" value="2017"></td>
      </tr>
    </tbody>
  </table>
  <p>
    <input name="subMarketing" type="submit" id="subMarketing" formmethod="POST" value="Submit">
  </p>
  <input type="hidden" name="MM_insert" value="frmMarketing">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rstViewRegistration);

mysql_free_result($rsMarketingList);
?>
