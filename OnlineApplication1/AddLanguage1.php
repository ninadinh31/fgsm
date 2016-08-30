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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmAddLanguages")) {
  $insertSQL = sprintf("INSERT INTO tblsqlanguagedetails (`UID`, LanguageID, ProficiencyLevel) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['textfield'], "int"),
                       GetSQLValueString($_POST['selLanguages'], "int"),
                       GetSQLValueString($_POST['selProfLevel'], "text"));

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
$query_rsLanguages = "SELECT * FROM tblsqlanguage ORDER BY tblsqlanguage.`Language`";
$rsLanguages = mysql_query($query_rsLanguages, $FGSP) or die(mysql_error());
$row_rsLanguages = mysql_fetch_assoc($rsLanguages);
$totalRows_rsLanguages = mysql_num_rows($rsLanguages);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" id="frmAddLanguages" name="frmAddLanguages" method="post">
  <p><strong>Add Language Skills</strong></p>
  <table width="709" border="1">
    <tbody>
      <tr>
        <td width="293">Language 
          <select name="selLanguages" id="selLanguages">
            <?php
do {  
?>
            <option value="<?php echo $row_rsLanguages['LanguageID']?>"><?php echo $row_rsLanguages['Language']?></option>
            <?php
} while ($row_rsLanguages = mysql_fetch_assoc($rsLanguages));
  $rows = mysql_num_rows($rsLanguages);
  if($rows > 0) {
      mysql_data_seek($rsLanguages, 0);
	  $row_rsLanguages = mysql_fetch_assoc($rsLanguages);
  }
?>
        </select></td>
        <td width="216"><select name="selProfLevel" id="selProfLevel">
            <option>Native</option>
            <option>Proficient</option>
            <option>Fluent</option>
            <option>Intermediate</option>
            <option>Basic</option>
        </select></td>
        <td width="178"><input name="textfield" type="text" id="textfield" value="<?php echo $row_rstViewRegistration['UID']; ?>"></td>
      </tr>
    </tbody>
  </table>
  <p>
    <input type="submit" name="subLanguage" id="subLanguage" value="Submit">
  </p>
  <input type="hidden" name="MM_insert" value="frmAddLanguages">
</form>
</body>
</html>
<?php
mysql_free_result($rstViewRegistration);

mysql_free_result($rsLanguages);
?>
