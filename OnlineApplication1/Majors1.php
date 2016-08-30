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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmMajor")) {
  $insertSQL = sprintf("INSERT INTO tblsqprogramdetails (`UID`, MID, Type) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['txtUID'], "int"),
                       GetSQLValueString($_POST['selMajors'], "int"),
                       GetSQLValueString($_POST['selCategory1'], "text"));

  mysql_select_db($database_FGSP, $FGSP);
  $Result1 = mysql_query($insertSQL, $FGSP) or die(mysql_error());

  $insertGoTo = "ControlPanel2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_FGSP, $FGSP);
$query_rsProgramList = "SELECT * FROM tblsqprogramlist ORDER BY Programs ASC";
$rsProgramList = mysql_query($query_rsProgramList, $FGSP) or die(mysql_error());
$row_rsProgramList = mysql_fetch_assoc($rsProgramList);
$totalRows_rsProgramList = mysql_num_rows($rsProgramList);

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
<script type="text/javascript">
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
</script>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="POST" name="frmMajor" id="frmMajor">
  <table width="499" border="1">
    <tbody>
      <tr>
        <td width="305">Major</td>
        <td width="178">Type</td>
        <td width="178">UID</td>
      </tr>
      <tr>
        <td><select name="selMajors" required id="selMajors">
          <?php
do {  
?>
          <option value="<?php echo $row_rsProgramList['MID']?>"><?php echo $row_rsProgramList['Programs']?></option>
          <?php
} while ($row_rsProgramList = mysql_fetch_assoc($rsProgramList));
  $rows = mysql_num_rows($rsProgramList);
  if($rows > 0) {
      mysql_data_seek($rsProgramList, 0);
	  $row_rsProgramList = mysql_fetch_assoc($rsProgramList);
  }
?>
        </select></td>
        <td><select name="selCategory1" required id="selCategory1">
          <option>Major</option>
          <option>Minor</option>
        </select></td>
        <td><input name="txtUID" type="text" id="txtUID" value="<?php echo $row_rstViewRegistration['UID']; ?>"></td>
      </tr>
    </tbody>
  </table>
  <p>If you major is not listed, please click here and add your Major 
    <input name="ButOtherMajor" type="button" id="ButOtherMajor" onClick="MM_goToURL('parent','OtherMajor1.php');return document.MM_returnValue" value="Add Other Major (Not Listed)">
  </p>
  <p>
    <input type="submit" name="SubmitMajors" id="SubmitMajors" value="Submit">
    <input type="hidden" name="MM_insert" value="frmMajor">
</p>
</form>
</body>
</html>
<?php
mysql_free_result($rsProgramList);

mysql_free_result($rstViewRegistration);
?>
