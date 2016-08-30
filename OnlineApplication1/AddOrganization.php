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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmOrganization")) {
  $insertSQL = sprintf("INSERT INTO tblorganization (OrgName) VALUES (%s)",
                       GetSQLValueString($_POST['txtOrgName'], "text"));

  mysql_select_db($database_FGSP, $FGSP);
  $Result1 = mysql_query($insertSQL, $FGSP) or die(mysql_error());

  $insertGoTo = "AddOrganization.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$maxRows_rsOrganization = 10;
$pageNum_rsOrganization = 0;
if (isset($_GET['pageNum_rsOrganization'])) {
  $pageNum_rsOrganization = $_GET['pageNum_rsOrganization'];
}
$startRow_rsOrganization = $pageNum_rsOrganization * $maxRows_rsOrganization;

mysql_select_db($database_FGSP, $FGSP);
$query_rsOrganization = "SELECT * FROM tblorganization ORDER BY OrgName ASC";
$query_limit_rsOrganization = sprintf("%s LIMIT %d, %d", $query_rsOrganization, $startRow_rsOrganization, $maxRows_rsOrganization);
$rsOrganization = mysql_query($query_limit_rsOrganization, $FGSP) or die(mysql_error());
$row_rsOrganization = mysql_fetch_assoc($rsOrganization);

if (isset($_GET['totalRows_rsOrganization'])) {
  $totalRows_rsOrganization = $_GET['totalRows_rsOrganization'];
} else {
  $all_rsOrganization = mysql_query($query_rsOrganization);
  $totalRows_rsOrganization = mysql_num_rows($all_rsOrganization);
}
$totalPages_rsOrganization = ceil($totalRows_rsOrganization/$maxRows_rsOrganization)-1;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<p><strong>Add New Organization</strong></p>
<form action="<?php echo $editFormAction; ?>" id="frmOrganization" name="frmOrganization" method="POST">
  <fieldset>
  <table width="185" border="1">
    <tbody>
      <tr>
        <td width="179">Organization        </td>
      </tr>
      <tr>
        <td><textarea name="txtOrgName" cols="60" rows="3" id="txtOrgName"></textarea></td> 
      </tr>
    </tbody>
  </table>
  <p>
    <input name="subOrganization" type="submit" id="subOrganization" formmethod="POST" value="Submit">
    <input name="reset" type="reset" id="reset" form="frmOrganization" value="Reset">
  </p>
  </fieldset>
  <p><strong>Organization List </strong></p>
  
  <table width="642" border="1">
      <tbody>
        <tr>
          <td width="146">Organization ID</td>
          <td width="344">Organization</td>
          <td width="60">&nbsp;</td>
          <td width="64">&nbsp;</td>
        </tr>
        <?php do { ?>
        <tr>
          <td><?php echo $row_rsOrganization['OrgID']; ?></td>
          <td><?php echo $row_rsOrganization['OrgName']; ?></td>
          <td><a href="EditOrganization.php?OrgID=<?php echo $row_rsOrganization['OrgID'];?>">Edit</a></td>
          <td><a href="DeleteOrganization.php?OrgID=<?php echo $row_rsOrganization['OrgID'];?>">Delete</a></td>
        </tr>
        <?php } while ($row_rsOrganization = mysql_fetch_assoc($rsOrganization)); ?>
      </tbody>
    </table>
  <input type="hidden" name="MM_insert" value="frmOrganization">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsOrganization);
?>
