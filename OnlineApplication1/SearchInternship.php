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

$searchField_rsSearchInternship = "%";
if (isset($_GET['searchField'])) {
  $searchField_rsSearchInternship = $_GET['searchField'];
  
mysql_select_db($database_FGSP, $FGSP);
	
$query_rsSearchInternship = sprintf("SELECT tblinternshipoffice.SubOrgID, tblinternshipoffice.OrgID, tblinternshipoffice.Category, tblinternshipoffice.IntOffering, tblinternshipoffice.`Description`, tblinternshipoffice.Qualifications, tblinternshipoffice.Location, tblinternshipoffice.AppRequirement, tblinternshipoffice.Website, tblinternshipoffice.Contact, tblinternshipoffice.FGSNotes, tblinternshipoffice.SubOrgName, tblinternshipoffice.`Field`, tblorganization.OrgName FROM tblinternshipoffice INNER JOIN tblorganization ON tblinternshipoffice.OrgID = tblorganization.OrgID WHERE tblinternshipoffice.Description LIKE %s", GetSQLValueString("%" . $searchField_rsSearchInternship . "%", "text"));


$rsSearchInternship = mysql_query($query_rsSearchInternship, $FGSP) or die(mysql_error());
$row_rsSearchInternship = mysql_fetch_assoc($rsSearchInternship);
$totalRows_rsSearchInternship = mysql_num_rows($rsSearchInternship);

$maxRows_rsSearchInternship = 5;
$pageNum_rsSearchInternship = 0;
if (isset($_GET['pageNum_rsSearchInternship'])) {
  $pageNum_rsSearchInternship = $_GET['pageNum_rsSearchInternship'];
}
$startRow_rsSearchInternship = $pageNum_rsSearchInternship * $maxRows_rsSearchInternship;


if (isset($_GET['totalRows_rsSearchInternship'])) {
  $totalRows_rsSearchInternship = $_GET['totalRows_rsSearchInternship'];
} else {
  $all_rsSearchInternship = mysql_query($query_rsSearchInternship);
  $totalRows_rsSearchInternship = mysql_num_rows($all_rsSearchInternship);
}
$totalPages_rsSearchInternship = ceil($totalRows_rsSearchInternship/$maxRows_rsSearchInternship)-1;
}
?>

<h2><strong>Search Internship</strong></h2>
<form name="form1" method="post" action="ApplicationSrcResult1.php">
  <table width="300" border="1">
    <tbody>
      <tr>
        <td><strong>Keywords</strong></td>
        <td><input type="text" name="searchField" id="searchField"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input name="submit" type="submit" id="submit" formaction="SearchInternship.php" formmethod="GET" value="Search"></td>
      </tr>
    </tbody>
  </table>
  <p>---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</p>
  <h2><strong>Search Results</strong></h2>
    <table width="1109" height="92" border="1">
      <tbody>
        <tr bgcolor="#C1BFBF">
          <td width="226"><strong>Organization</strong></td>
          <td width="271"><strong>Office</strong></td>
          <td width="194"><strong>Category</strong></td>
          <td width="390"><strong>Description</strong></td>
        </tr>
        <?php do { ?>
        <tr>
          <td><?php echo $row_rsSearchInternship['OrgName']; ?></td>
          <td><a href="ViewInternship.php?SubOrgID=<?php echo $row_rsSearchInternship['SubOrgID'];?>"><?php echo $row_rsSearchInternship['SubOrgName']; ?>
          <input name="hidSubOrgID" type="hidden" id="hidSubOrgID" value="<?php echo $row_rsSearchInternship['SubOrgID']; ?>"></td>
          <td><?php echo $row_rsSearchInternship['Category']; ?></td>
          <td><?php echo $row_rsSearchInternship['Description']; ?></td>
        <?php } while ($row_rsSearchInternship = mysql_fetch_assoc($rsSearchInternship)); ?>
        </tr>
      </tbody>
    </table>
  <p>&nbsp;</p>
</form>
<p>&nbsp;</p>
<?php
mysql_free_result($rsSearchInternship);
?>
