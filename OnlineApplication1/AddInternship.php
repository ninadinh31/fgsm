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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmAddInternship")) {
  $insertSQL = sprintf("INSERT INTO tblinternshipoffice (OrgID, Category, IntOffering, `Description`, Qualifications, Location, AppRequirement, Website, Contact, FGSNotes, SubOrgName, `Field`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['selCategory'], "int"),
                       GetSQLValueString($_POST['selCatType'], "text"),
                       GetSQLValueString($_POST['txtOffering'], "text"),
                       GetSQLValueString($_POST['txtDescription'], "text"),
                       GetSQLValueString($_POST['txtQualification'], "text"),
                       GetSQLValueString($_POST['txtLocation'], "text"),
                       GetSQLValueString($_POST['txtApply'], "text"),
                       GetSQLValueString($_POST['txtWebsite'], "text"),
                       GetSQLValueString($_POST['txtContact'], "int"),
                       GetSQLValueString($_POST['txtNotes'], "text"),
                       GetSQLValueString($_POST['txtOffice'], "text"),
                       GetSQLValueString($_POST['txtField'], "text"));

  mysql_select_db($database_FGSP, $FGSP);
  $Result1 = mysql_query($insertSQL, $FGSP) or die(mysql_error());

  $insertGoTo = "Admin1.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_FGSP, $FGSP);
$query_rsOrganization = "SELECT * FROM tblorganization ORDER BY OrgName ASC";
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
<h3>Add Internship Details</h3>
<form action="<?php echo $editFormAction; ?>" id="frmAddInternship" name="frmAddInternship" method="POST">
  <table width="353" border="0">
    <tbody>
      <tr>
        <td width="122">Organization</td>
        <td width="221"><label for="selCategory"></label>
          <select name="selCategory" id="selCategory">
        <?php
do {  
?>
          <option value="<?php echo $row_rsOrganization['OrgID']?>"><?php echo $row_rsOrganization['OrgName']?></option>
          <?php
} while ($row_rsOrganization = mysql_fetch_assoc($rsOrganization));
  $rows = mysql_num_rows($rsOrganization);
  if($rows > 0) {
      mysql_data_seek($rsOrganization, 0);
	  $row_rsOrganization = mysql_fetch_assoc($rsOrganization);
  }
?> 
        </select></td>
      </tr>
      <tr>
        <td>Office Name</td>
        <td><input type="text" name="txtOffice" id="txtOffice"></td>
      </tr>
      <tr>
        <td>Category</td>
        <td><label for="selCatType"></label>
          <select name="selCatType" id="selCatType">
            <option value="Federal">Federal</option>
            <option value="Congressional">Congressional</option>
            <option value="Embassy">Embassy</option>
        </select></td>
      </tr>
      <tr>
        <td>Related Fields</td>
        <td><input name="txtField" type="text" id="txtField" size="60"></td>
      </tr>
      <tr>
        <td>Internship Offering</td>
        <td><input type="text" name="txtOffering" id="txtOffering"></td>
      </tr>
      <tr>
        <td>Job Description</td>
        <td><textarea name="txtDescription" cols="120" rows="15" id="txtDescription"></textarea></td>
      </tr>
      <tr>
        <td>Qualification</td>
        <td><textarea name="txtQualification" cols="120" rows="15" id="txtQualification"></textarea></td>
      </tr>
      <tr>
        <td>Location</td>
        <td><textarea name="txtLocation" cols="60" id="txtLocation"></textarea></td>
      </tr>
      <tr>
        <td>How to Apply</td>
        <td><textarea name="txtApply" cols="60" id="txtApply"></textarea></td>
      </tr>
      <tr>
        <td>Websites</td>
        <td><input type="text" name="txtWebsite" id="txtWebsite"></td>
      </tr>
      <tr>
        <td>Contact Info</td>
        <td><input type="text" name="txtContact" id="txtContact"></td>
      </tr>
      <tr>
        <td>Additional Notes</td>
        <td><textarea name="txtNotes" cols="120" rows="15" id="txtNotes"></textarea></td>
      </tr>
    </tbody>
  </table>
  <p>
    <input type="submit" name="subInternship" id="subInternship" value="Submit">
    <input type="hidden" name="txtSubOrgID" id="txtSubOrgID">
    <input type="hidden" name="MM_insert" value="frmAddInternship">
</p>
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsOrganization);
?>
