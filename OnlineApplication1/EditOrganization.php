<?php require_once('../Connections/FGSP.php'); ?>

<?php 
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1";
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frmOrganization")) {
  $updateSQL = sprintf("UPDATE tblorganization SET OrgName=%s WHERE OrgID=%s",
                       GetSQLValueString($_POST['txtOrgName'], "text"),
                       GetSQLValueString($_POST['hiddenField'], "int"));

  mysql_select_db($database_FGSP, $FGSP);
  $Result1 = mysql_query($updateSQL, $FGSP) or die(mysql_error());

  $updateGoTo = "AddOrganization.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsOrganization = "-1";
if (isset($_GET['OrgID'])) {
  $colname_rsOrganization = $_GET['OrgID'];
}
mysql_select_db($database_FGSP, $FGSP);
$query_rsOrganization = sprintf("SELECT * FROM tblorganization WHERE OrgID = %s ORDER BY OrgName ASC", GetSQLValueString($colname_rsOrganization, "int"));
$rsOrganization = mysql_query($query_rsOrganization, $FGSP) or die(mysql_error());
$row_rsOrganization = mysql_fetch_assoc($rsOrganization);
$totalRows_rsOrganization = mysql_num_rows($rsOrganization);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<strong>Edit </strong><strong>Organization</strong>
<form action="<?php echo $editFormAction; ?>" id="frmOrganization" name="frmOrganization" method="POST">
  <table width="185" border="1">
    <tbody>
      <tr>
        <td width="179">Organization        
        <input name="hiddenField" type="hidden" id="hiddenField" value="<?php echo $row_rsOrganization['OrgID']; ?>"></td>
      </tr>
      <tr>
        <td><textarea name="txtOrgName" cols="60" rows="3" id="txtOrgName"><?php echo $row_rsOrganization['OrgName']; ?></textarea></td> 
      </tr>
    </tbody>
  </table>
  <p>
    <input name="subOrganization" type="submit" id="subOrganization" formmethod="POST" value="Submit">
  </p>
  <input type="hidden" name="MM_update" value="frmOrganization">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsOrganization);
?>
