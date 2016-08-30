<form id="frmDocConfirmDelete" name="frmDocConfirmDelete" method="post">
  <p><strong>Delete Confirm Page</strong></p>
  <table width="516" border="1">
    <tbody>
      <tr>
        <td width="137"><?php echo $row_rsDeleteDocuments['docID']; ?></td>
        <td width="104"><?php echo $row_rsDeleteDocuments['docCategory']; ?></td>
        <td width="153"><input name="subDelete" type="submit" id="subDelete" formmethod="POST" value="Confirm Delete"></td>
      </tr>
    </tbody>
  </table>
  <p>&nbsp;</p>
</form>
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
?>
<?php
if ((isset($_POST['txtdocID'])) && ($_POST['txtdocID'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tblsqdocuments WHERE docID=%s",
                       GetSQLValueString($_POST['txtdocID'], "int"));

  mysql_select_db($database_FGSP, $FGSP);
  $Result1 = mysql_query($deleteSQL, $FGSP) or die(mysql_error());

  $deleteGoTo = "ControlPanel2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$colname_rsDeleteDocuments = "-1";
if (isset($_GET['docID'])) {
  $colname_rsDeleteDocuments = $_GET['docID'];
}
mysql_select_db($database_FGSP, $FGSP);
$query_rsDeleteDocuments = sprintf("SELECT * FROM tblsqdocuments WHERE docID = %s", GetSQLValueString($colname_rsDeleteDocuments, "int"));
$rsDeleteDocuments = mysql_query($query_rsDeleteDocuments, $FGSP) or die(mysql_error());
$row_rsDeleteDocuments = mysql_fetch_assoc($rsDeleteDocuments);
$totalRows_rsDeleteDocuments = mysql_num_rows($rsDeleteDocuments);

mysql_free_result($rsDeleteDocuments);
?>
