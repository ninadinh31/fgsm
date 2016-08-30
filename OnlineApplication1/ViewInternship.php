<?php require_once('../Connections/FGSP.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1,3";
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

$maxRows_rsViewInternship = 1;
$pageNum_rsViewInternship = 0;
if (isset($_GET['pageNum_rsViewInternship'])) {
  $pageNum_rsViewInternship = $_GET['pageNum_rsViewInternship'];
}
$startRow_rsViewInternship = $pageNum_rsViewInternship * $maxRows_rsViewInternship;

$colname_rsViewInternship = "-1";
if (isset($_GET['SubOrgID'])) {
  $colname_rsViewInternship = $_GET['SubOrgID'];
}
mysql_select_db($database_FGSP, $FGSP);
$query_rsViewInternship = sprintf("SELECT tblinternshipoffice.SubOrgID, tblinternshipoffice.OrgID, tblinternshipoffice.Category, tblinternshipoffice.IntOffering, tblinternshipoffice.`Description`, tblinternshipoffice.Qualifications, tblinternshipoffice.Location, tblinternshipoffice.AppRequirement, tblinternshipoffice.Website, tblinternshipoffice.Contact, tblinternshipoffice.FGSNotes, tblinternshipoffice.SubOrgName, tblinternshipoffice.`Field`, tblorganization.OrgName FROM tblinternshipoffice INNER JOIN tblorganization ON tblinternshipoffice.OrgID = tblorganization.OrgID WHERE tblinternshipoffice.SubOrgID = %s", GetSQLValueString($colname_rsViewInternship, "int"));
$query_limit_rsViewInternship = sprintf("%s LIMIT %d, %d", $query_rsViewInternship, $startRow_rsViewInternship, $maxRows_rsViewInternship);
$rsViewInternship = mysql_query($query_limit_rsViewInternship, $FGSP) or die(mysql_error());
$row_rsViewInternship = mysql_fetch_assoc($rsViewInternship);

if (isset($_GET['totalRows_rsViewInternship'])) {
  $totalRows_rsViewInternship = $_GET['totalRows_rsViewInternship'];
} else {
  $all_rsViewInternship = mysql_query($query_rsViewInternship);
  $totalRows_rsViewInternship = mysql_num_rows($all_rsViewInternship);
}
$totalPages_rsViewInternship = ceil($totalRows_rsViewInternship/$maxRows_rsViewInternship)-1;

$queryString_rsViewInternship = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsViewInternship") == false && 
        stristr($param, "totalRows_rsViewInternship") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsViewInternship = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsViewInternship = sprintf("&totalRows_rsViewInternship=%d%s", $totalRows_rsViewInternship, $queryString_rsViewInternship);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<p><strong>View Internship Details</strong></p>
<form id="frmAddInternship" name="frmAddInternship" method="POST">
  <?php do { ?>
    <fieldset>
    <table width="800" border="1">
      <tbody>
        <tr>
          <td width="122">Organization</td>
          <td width="221"><label for="selCategory"><?php echo $row_rsViewInternship['OrgName']; ?>
            <input name="hidSubOrgID" type="hidden" id="hidSubOrgID" value="<?php echo $row_rsViewInternship['SubOrgID']; ?>">
          </label></td>
        </tr>
        <tr>
          <td>Office Name</td>
          <td><?php echo $row_rsViewInternship['SubOrgName']; ?></td>
        </tr>
        <tr>
          <td>Category</td>
          <td><label for="selCatType"><?php echo $row_rsViewInternship['Category']; ?></label></td>
        </tr>
        <tr>
          <td>Internship Offering</td>
          <td><?php echo $row_rsViewInternship['IntOffering']; ?></td>
        </tr>
        <tr>
          <td>Job Description</td>
          <td><?php echo $row_rsViewInternship['Description']; ?></td>
        </tr>
        <tr>
          <td>Qualification</td>
          <td><?php echo $row_rsViewInternship['Qualifications']; ?></td>
        </tr>
        <tr>
          <td>Location</td>
          <td><?php echo $row_rsViewInternship['Location']; ?></td>
        </tr>
        <tr>
          <td>How to Apply</td>
          <td><?php echo $row_rsViewInternship['AppRequirement']; ?></td>
        </tr>
        <tr>
          <td>Websites</td>
          <td><?php echo $row_rsViewInternship['Website']; ?></td>
        </tr>
        <tr>
          <td>Contact Info</td>
          <td><?php echo $row_rsViewInternship['Contact']; ?></td>
        </tr>
        <tr>
          <td>Additional Notes</td>
          <td><?php echo $row_rsViewInternship['FGSNotes']; ?></td>
        </tr>
      </tbody>
    </table>
    </fieldset>
    <?php } while ($row_rsViewInternship = mysql_fetch_assoc($rsViewInternship)); ?>
<a href="<?php printf("%s?pageNum_rsViewInternship=%d%s", $currentPage, min($totalPages_rsViewInternship, $pageNum_rsViewInternship + 1), $queryString_rsViewInternship); ?>">Next |</a><a href="<?php printf("%s?pageNum_rsViewInternship=%d%s", $currentPage, max(0, $pageNum_rsViewInternship - 1), $queryString_rsViewInternship); ?>"> Previous</a> <?php echo "Records ".($startRow_rsViewInternship + 1)?><?php echo " of ".$totalRows_rsViewInternship ?>
  <p>
    <input type="hidden" name="txtSubOrgID" id="txtSubOrgID">
    <input name="hidSubOrgID" type="hidden" id="hidSubOrgID" value="<?php echo $row_rsViewInternship['SubOrgID']; ?>">
    <input name="hidOrgID" type="hidden" id="hidOrgID" value="<?php echo $row_rsViewInternship['OrgID']; ?>">
  </p>
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsViewInternship);
?>
