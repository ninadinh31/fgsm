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

//function to generate complete search query
function build_query($user_search, $sort) {
	
$search_query = "SELECT tblinternshipoffice.SubOrgID, tblinternshipoffice.OrgID, tblinternshipoffice.Category, tblinternshipoffice.IntOffering, tblinternshipoffice.`Description`, tblinternshipoffice.Qualifications, tblinternshipoffice.Location, tblinternshipoffice.AppRequirement, tblinternshipoffice.Website, tblinternshipoffice.Contact, tblinternshipoffice.FGSNotes, tblinternshipoffice.SubOrgName, tblinternshipoffice.`Field`, tblorganization.OrgName FROM tblinternshipoffice INNER JOIN tblorganization ON tblinternshipoffice.OrgID = tblorganization.OrgID";
	
	//backup simple query that works
	//$search_query = "SELECT * FROM tblinternshipoffice";


//old code - works but simple
//$user_search = $_GET['usersearch'];
//mysql_select_db($database_FGSP, $FGSP);
//$query_rsInternship = "SELECT * FROM tblinternshipoffice WHERE Description LIKE '%$user_search%'";

$clean_search = str_replace(',', ' ', $user_search);
$search_words = explode(' ', $clean_search);
$final_search_words = array();
if (count($search_words) > 0) {
	foreach ($search_words as $word) {
		if (!empty($word)) {
			$final_search_words[] = $word;
		}
	}
}

$where_list = array();
if (count($final_search_words) > 0) {
	foreach ($final_search_words as $word) {
	$where_list[] = "Description LIKE '%$word%'";
 }
}
$where_clause=implode(' OR ', $where_list);

if (!empty($where_clause)) {
$search_query .= " WHERE $where_clause";
}
 
//sort the search query
switch ($sort) {
//Ascending by Office
case 1:
	$search_query .= " ORDER BY SubOrgName";
	break;
//Descending by Office
case 2:
	$search_query .= " ORDER BY SubOrgName DESC";
	break;
//Ascending by Category
case 3:
	$search_query .= " ORDER BY Category";
	break;
//Descending by Category
case 4:
	$search_query .= " ORDER BY Category DESC";
	break;
//Ascending by Description
case 5:
	$search_query .= " ORDER BY Description";
	break;
//Descending by Description
case 6:
	$search_query .= " ORDER BY Description DESC";
	break;
default:
//No sorting preference

}
 
return $search_query;
}

//function to sort returned search results

function generate_sort_links($user_search, $sort) {
	$sort_links = '';

switch ($sort) {
case 1:
 $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=2">Office</a></td>';
 $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Category</a></td>';
 $sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Description</a></td>';
break;
case 3:
$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Office</a></td>';
$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=4">Category</a></td>';
$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Description</a></td>';
break;
case 5;
$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Office</a></td>';
$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Category</a></td>';
$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=6">Description</a></td>';
break;
default:
$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=1">Office</a></td>';
$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=3">Category</a></td>';
$sort_links .= '<td><a href = "' . $_SERVER['PHP_SELF'] . '?usersearch=' . $user_search . '&sort=5">Description</a></td>';
}
return $sort_links;
}
$sort = '';
if(isset($_GET['sort'])) {
$sort = $_GET['sort'];
}
$user_search = $_GET['usersearch'];
mysql_select_db($database_FGSP, $FGSP);
$query_rsInternship = build_query($user_search, $sort);
$rsInternship = mysql_query($query_rsInternship, $FGSP) or die(mysql_error());
$row_rsInternship = mysql_fetch_assoc($rsInternship);
$totalRows_rsInternship = mysql_num_rows($rsInternship);

?>
<h2>Internship Search Results</h2>
<form id="frmIntSrcResults" name="frmIntSrcResults" method="post">
  <table width="981" border="1">
    <tbody>
      <tr>
        <td width="222"><strong><?php echo generate_sort_links($user_search, $sort);?></strong></td>
      </tr>
      <?php do { ?>
      <tr>
        <td><?php echo $row_rsInternship['OrgName'];?></td>
        <td><a href="ViewInternship.php?SubOrgID=<?php echo $row_rsInternship['SubOrgID'];?>"><?php echo $row_rsInternship['SubOrgName'];?></a>
        <td><?php echo $row_rsInternship['Category'];?></td>
        <td><?php echo substr($row_rsInternship['Description'],0,10);?> '...</td>
      </tr>
      <?php } while ($row_rsInternship = mysql_fetch_assoc($rsInternship)); ?>
    </tbody>
  </table>
</form>
<p>&nbsp;</p>
<?php
mysql_free_result($rsInternship);
?>
