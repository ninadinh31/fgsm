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
$stdName_rsViewApplications = "%";
if (isset($_GET['searchField'])) {
  $stdName_rsViewApplications = $_GET['searchField'];
}
mysql_select_db($database_FGSP, $FGSP);
$query_rsViewApplications = sprintf("SELECT CONCAT(tblusers.Firstname, ' ', tblusers.Lastname) AS Fullname, tblusers.Email, tblusers.Firstname, tblusers.Lastname, tblusers.`UID`, tblusers.RegisterDate, tblsqapplication.Seniority, tblsqapplication.Gender, tblsqapplication.Race, tblsqapplication.OverallGPA, tblsqapplication.MajorGPA, tblsqapplication.PgmYear FROM tblsqapplication, tblusers WHERE tblsqapplication.UID = tblUsers.UID AND CONCAT(' ',Firstname, Lastname) LIKE %s", GetSQLValueString("%" . $stdName_rsViewApplications . "%", "text"));
$rsViewApplications = mysql_query($query_rsViewApplications, $FGSP) or die(mysql_error());
$row_rsViewApplications = mysql_fetch_assoc($rsViewApplications);
$totalRows_rsViewApplications = mysql_num_rows($rsViewApplications);

if (isset($_GET['totalRows_rsViewApplications'])) {
  $totalRows_rsViewApplications = $_GET['totalRows_rsViewApplications'];
} else {
  $all_rsViewApplications = mysql_query($query_rsViewApplications);
  $totalRows_rsViewApplications = mysql_num_rows($all_rsViewApplications);
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<h3>Search Results</h3>
<form id="frmViewApplications" name="frmViewApplications" method="post">
    <table width="1175" border="1">
      <tbody>
        <tr>
          <td bgcolor="#1DEBE7"><strong>Firstname</strong></td>
          <td bgcolor="#1DEBE7"><strong>Lastname</strong></td>
          <td bgcolor="#1DEBE7"><strong>Gender</strong></td>
          <td bgcolor="#1DEBE7"><strong>Seniority</strong></td>
          <td bgcolor="#1DEBE7"><strong>UID</strong></td>
          <td bgcolor="#1DEBE7"><strong>GPA</strong></td>
        </tr>
       <?php do { ?>
        <tr>
          <td width="196"><?php echo $row_rsViewApplications['Firstname']; ?></td>
          <td width="195"><?php echo $row_rsViewApplications['Lastname']; ?></td>
          <td width="182"><?php echo $row_rsViewApplications['Gender']; ?></td>
          <td width="190"><?php echo $row_rsViewApplications['Seniority']; ?></td>
          <td width="164"><a href="ViewDetails1.php?UID=<?php echo $row_rsViewApplications['UID'];?>"><?php echo $row_rsViewApplications['UID']; ?></a></td>
          <td width="50"><?php echo $row_rsViewApplications['OverallGPA']; ?></td>
         <?php } while ($row_rsViewApplications = mysql_fetch_assoc($rsViewApplications)); ?>
        </tr>
      </tbody>
    </table>
</form>
</body>
</html>
<?php
mysql_free_result($rsViewApplications);
?>
