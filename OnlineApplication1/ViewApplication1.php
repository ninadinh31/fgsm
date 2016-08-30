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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsViewApplications = 10;
$pageNum_rsViewApplications = 0;
if (isset($_GET['pageNum_rsViewApplications'])) {
  $pageNum_rsViewApplications = $_GET['pageNum_rsViewApplications'];
}
$startRow_rsViewApplications = $pageNum_rsViewApplications * $maxRows_rsViewApplications;

mysql_select_db($database_FGSP, $FGSP);
$query_rsViewApplications = "SELECT tblusers.Firstname, tblusers.Lastname, tblusers.Username, tblusers.Email, tblusers.`UID`, tblusers.RegisterDate, tblsqapplication.Seniority, tblsqapplication.Gender, tblsqapplication.Race, tblsqapplication.OverallGPA, tblsqapplication.MajorGPA, tblsqapplication.PgmYear FROM tblsqapplication, tblusers WHERE tblsqapplication.UID = tblUsers.UID";
$query_limit_rsViewApplications = sprintf("%s LIMIT %d, %d", $query_rsViewApplications, $startRow_rsViewApplications, $maxRows_rsViewApplications);
$rsViewApplications = mysql_query($query_limit_rsViewApplications, $FGSP) or die(mysql_error());
$row_rsViewApplications = mysql_fetch_assoc($rsViewApplications);


if (isset($_GET['totalRows_rsViewApplications'])) {
  $totalRows_rsViewApplications = $_GET['totalRows_rsViewApplications'];
} else {
  $all_rsViewApplications = mysql_query($query_rsViewApplications);
  $totalRows_rsViewApplications = mysql_num_rows($all_rsViewApplications);
}
$totalPages_rsViewApplications = ceil($totalRows_rsViewApplications/$maxRows_rsViewApplications)-1;

$queryString_rsViewApplications = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsViewApplications") == false && 
        stristr($param, "totalRows_rsViewApplications") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsViewApplications = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsViewApplications = sprintf("&totalRows_rsViewApplications=%d%s", $totalRows_rsViewApplications, $queryString_rsViewApplications);
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
<h3>View 2016 Applications</h3>
<form id="frmViewApplications" name="frmViewApplications" method="post">
  <?php do { ?>
    <table width="1189" border="1">
      <tbody>
        <tr>
          <td width="120"><nav><a href="<?php printf("%s?pageNum_rsViewApplications=%d%s", $currentPage, 0, $queryString_rsViewApplications); ?>"><?php echo $row_rsViewApplications['Firstname']; ?></a></nav></td>
          <td width="120"><?php echo $row_rsViewApplications['Lastname']; ?></td>
          <td width="50"><?php echo $row_rsViewApplications['Gender']; ?></td>
          <td width="70"><?php echo $row_rsViewApplications['Seniority']; ?></td>
          <td width="120"><a href="UpdateApplicant.php?UID=<?php echo $row_rsViewApplications['UID'];?>"><?php echo $row_rsViewApplications['UID']; ?></a></td>
          <td width="30"><?php echo $row_rsViewApplications['OverallGPA']; ?></td>
          <td width="8"><input name="button" type="button" id="button" onClick="MM_goToURL('parent','UpdateApplicant.php');return document.MM_returnValue" value="Update"></td>
        </tr>
      </tbody>
    </table>
    <?php } while ($row_rsViewApplications = mysql_fetch_assoc($rsViewApplications)); ?>
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsViewApplications);
?>
