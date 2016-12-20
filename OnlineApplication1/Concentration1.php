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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmConcentration")) {
  $insertSQL = sprintf("INSERT INTO tblsqapplicationrank (`UID`, ConID, Rank, PgmYear) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['txtUID'], "int"),
                       GetSQLValueString($_POST['Homeland'], "int"),
                       GetSQLValueString($_POST['selectRank'], "int"),
                       GetSQLValueString($_POST['txtPgmYear'], "date"));

$insertSQL2 = sprintf("INSERT INTO tblsqapplicationrank (`UID`, ConID, Rank, PgmYear) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['txtUID2'], "int"),
                       GetSQLValueString($_POST['Energy'], "int"),
                       GetSQLValueString($_POST['selectRank2'], "int"),
                       GetSQLValueString($_POST['txtPgmYear2'], "date"));

  mysql_select_db($database_FGSP, $FGSP);
  $Result1 = mysql_query($insertSQL, $FGSP) or die(mysql_error());
  $Result2 = mysql_query($insertSQL2, $FGSP) or die(mysql_error());

  $insertGoTo = "ControlPanel2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_FGSP, $FGSP);
$query_rsConcentrationLookup = "SELECT * FROM tblsqconcentration ORDER BY ConID ASC";
$rsConcentrationLookup = mysql_query($query_rsConcentrationLookup, $FGSP) or die(mysql_error());
$row_rsConcentrationLookup = mysql_fetch_assoc($rsConcentrationLookup);
$totalRows_rsConcentrationLookup = mysql_num_rows($rsConcentrationLookup);

$colname_rstViewRegistration = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rstViewRegistration = $_SESSION['MM_Username'];
}
mysql_select_db($database_FGSP, $FGSP);
$query_rstViewRegistration = sprintf("SELECT * FROM tblusers WHERE Username = %s", GetSQLValueString($colname_rstViewRegistration, "text"));
$rstViewRegistration = mysql_query($query_rstViewRegistration, $FGSP) or die(mysql_error());
$row_rstViewRegistration = mysql_fetch_assoc($rstViewRegistration);
$totalRows_rstViewRegistration = mysql_num_rows($rstViewRegistration);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<style type="text/css">
body {
	background-color: #C1BFBF;
}
</style>
</head>

<body>
<p style="text-align: center; font-family: Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif; font-weight: bolder; font-size: 18px; color: #BE0F30;"><strong>Please rank all concentrations in which you are interested in applying</strong></p>
<form action="<?php echo $editFormAction; ?>" id="frmConcentration" name="frmConcentration" method="POST">
  <table width="849" height="409" border="1" align="center">
    <tbody>
      <tr>
        <td width="286" bgcolor="#76D4F1"><strong>Concentration</strong></td>
        <td width="49" bgcolor="#76D4F1"><strong>Rank</strong></td>
        <td width="176" bgcolor="#76D4F1"><strong>UID</strong></td>
        <td width="144" bgcolor="#76D4F1"><strong>PgmYear</strong></td>
        <td width="98" bgcolor="#76D4F1">&nbsp;</td>
      </tr>
      <tr>
        <td bgcolor="#76D4F1">Homeland Security
        <input name="Homeland" type="hidden" id="Homeland" value="1"></td>
        <td bgcolor="#76D4F1"><select name="selectRank" id="selectRank">
          <option>1</option> 
          <option>2</option>
          <option>3</option>
          <option>4</option>
          <option>5</option>
          <option>6</option>
        </select></td>
        <td bgcolor="#76D4F1"><input name="txtUID" type="text" id="txtUID" value="<?php echo $row_rstViewRegistration['UID']; ?>"></td>
        <td bgcolor="#76D4F1"><input name="txtPgmYear" type="text" id="txtPgmYear" value="2017"></td>
        <td bgcolor="#76D4F1">&nbsp;</td>
      </tr>
      <tr>
        <td bgcolor="#76D4F1">Energy and Environmental Policy
        <input name="Energy" type="hidden" id="Energy" value="2"></td>
        <td bgcolor="#76D4F1"><select name="selectRank2" id="selectRank2">
          <option>1</option>
          <option>2</option>
          <option>3</option>
          <option>4</option>
          <option>5</option>
          <option>6</option>
        </select></td>
        <td bgcolor="#76D4F1"><input name="txtUID2" type="text" id="txtUID2" value="<?php echo $row_rstViewRegistration['UID']; ?>"></td>
        <td bgcolor="#76D4F1"><input name="txtPgmYear2" type="text" id="txtPgmYear2" value="2017"></td>
        <td bgcolor="#76D4F1"><input type="submit" name="submitConcentration" id="submitConcentration" value="Add"></td>
      </tr>
      <tr>
        <td bgcolor="#76D4F1">Public Health Policy
        <input name="Health" type="hidden" id="Health" value="5"></td>
        <td bgcolor="#76D4F1"><select name="select" id="select">
        </select></td>
        <td bgcolor="#76D4F1">&nbsp;</td>
        <td bgcolor="#76D4F1">&nbsp;</td>
        <td bgcolor="#76D4F1">&nbsp;</td>
      </tr>
      <tr>
        <td bgcolor="#76D4F1">Science Diplomacy
          <input type="hidden" name="ScienceDip" id="ScienceDip" value="6"></td>
        <td bgcolor="#76D4F1">&nbsp;</td>
        <td bgcolor="#76D4F1">&nbsp;</td>
        <td bgcolor="#76D4F1">&nbsp;</td>
        <td bgcolor="#76D4F1">&nbsp;</td>
      </tr>
      <tr>
        <td height="65" bgcolor="#76D4F1">U.S. Diplomacy and Policymaking
        <input name="Diplomacy" type="hidden" id="Diplomacy" value="4"></td>
        <td bgcolor="#76D4F1">&nbsp;</td>
        <td bgcolor="#76D4F1">&nbsp;</td>
        <td bgcolor="#76D4F1">&nbsp;</td>
        <td bgcolor="#76D4F1">&nbsp;</td>
      </tr>
      <tr>
        <td bgcolor="#76D4F1">Responses to Global Challenges
        <input name="Responses" type="hidden" id="Responses" value="3"></td>
        <td bgcolor="#76D4F1">&nbsp;</td>
        <td bgcolor="#76D4F1">&nbsp;</td>
        <td bgcolor="#76D4F1">&nbsp;</td>
        <td bgcolor="#76D4F1">&nbsp;</td>
      </tr>
      <tr>
        <td bgcolor="#76D4F1">Critical Regions and International
        Relations 
        <input name="Critical" type="hidden" id="Critical" value="7"></td>
        <td bgcolor="#76D4F1">&nbsp;</td>
        <td bgcolor="#76D4F1">&nbsp;</td>
        <td bgcolor="#76D4F1">&nbsp;</td>
        <td bgcolor="#76D4F1">&nbsp;</td>
      </tr>
    </tbody>
  </table>
  <span style="color: #1097D0"></span>
  <p style="color: #1097D0">&nbsp; </p>
  <p>
    <input type="hidden" name="MM_insert" value="frmConcentration">
  </p>
</form>
</body>
</html>
<?php
mysql_free_result($rsConcentrationLookup);

mysql_free_result($rstViewRegistration);
?>
