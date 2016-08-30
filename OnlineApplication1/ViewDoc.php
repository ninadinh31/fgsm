<?php 
if (!isset($_SESSION)) {
  session_start();
}
require_once('../Connections/FGSP.php');?>
<?php
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
$colname_rstViewRegistration = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rstViewRegistration = $_SESSION['MM_Username'];
}
mysql_select_db($database_localhost, $localhost);
$query_rstViewRegistration = sprintf("SELECT * FROM tblUsers WHERE Username = %s", GetSQLValueString($colname_rstViewRegistration, "text"));
$rstViewRegistration = mysql_query($query_rstViewRegistration, $localhost) or die(mysql_error());
$row_rstViewRegistration = mysql_fetch_assoc($rstViewRegistration);
$totalRows_rstViewRegistration = mysql_num_rows($rstViewRegistration);

$colname_rsDocuments = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsDocuments = $_SESSION['MM_Username'];
}
mysql_select_db($database_FGSP, $FGSP);
$query_rsDocuments = sprintf("SELECT tblusers.Username, tblsqdocuments.docName, tblsqdocuments.docContent, tblsqdocuments.PgmYear, tblsqdocuments.`UID`, tblsqdocuments.docCategory, tblsqdocuments.docID FROM tblsqdocuments INNER JOIN tblusers  ON tblusers.UID = tblsqdocuments.UID  INNER JOIN tblsqapplication  ON tblsqapplication.UID = tblsqdocuments.UID AND  tblsqapplication.PgmYear = tblsqdocuments.PgmYear WHERE tblusers.Username = %s", GetSQLValueString($colname_rsDocuments, "text"));
$rsDocuments = mysql_query($query_rsDocuments, $FGSP) or die(mysql_error());
$row_rsDocuments = mysql_fetch_assoc($rsDocuments);
$totalRows_rsDocuments = mysql_num_rows($rsDocuments);

?> 
<?php
//foreach ($mytest as $value)
// {
//         $fn=$value;   
 //echo "File name is ".$fn;
//$file = '/var/www/ugst.umd.edu/data/uploads_globalsemesterdc/'.$fn;
 //       header("Pragma: public");
  //     header("Expires: 0");
   //    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  //     header("Cache-Control: private",false);
  //     header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
  //     header("Content-Disposition: inline; filename=\"$fn\";");
 //      header("Content-Transfer-Encoding: binary");
 //      header("Content-Length: ".@filesize($file));
 //      set_time_limit(0);
 //      readfile($file) or die("File not found.");
// }
    ?>
 <?php
// $fn=$mytest;   //'42049-dos.docx';
 //echo "File name is".$fn;
//$file = '/var/www/ugst.umd.edu/data/uploads_globalsemesterdc/'.$fn;
   //     header("Pragma: public");
   //     header("Expires: 0");
   //     header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    //    header("Cache-Control: private",false);
    //    header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
    //    header("Content-Disposition: inline; filename=\"$fn\";");
    //    header("Content-Transfer-Encoding: binary");
    //    header("Content-Length: ".@filesize($file));
    //    set_time_limit(0);
     //   readfile($file) or die("File not found.");
 ?>
 <?php
 mysql_free_result($rsDocuments);
 ?>