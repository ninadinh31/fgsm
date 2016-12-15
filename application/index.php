<?php 

require_once('../Connections/NewLogin.php'); 

if (!isset($_SESSION)) {
    session_start();
}

$MM_authorizedUsers = "0,1,2";
$MM_donotCheckaccess = "false";

if (!$MM_authorizedUsers) {
 echo "No authorized";
}

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

$MM_restrictGoTo = "../login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
    $MM_qsChar = "?";
    $MM_referrer = $_SERVER['PHP_SELF'];
    if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
    if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
        $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
    // $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
    header("Location: ". $MM_restrictGoTo); 
    exit;
}

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

if ((isset($_GET['docID'])) && ($_GET['docID'] != "")) {
    $deleteSQL = sprintf("DELETE FROM tblsqDocuments WHERE docID=%s",
       GetSQLValueString($_GET['docID'], "int"));

    mysql_select_db($database_localhost, $localhost);
    $Result1 = mysql_query($deleteSQL, $localhost) or die(mysql_error());

    $deleteGoTo = "index.php#tab-8";
    if (isset($_SERVER['QUERY_STRING'])) {
      $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
      $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frmControlPanel")) {
    $updateSQL = sprintf("UPDATE tblsqApplication SET UserID=%s, InternshipInterest=%s, PastInternLocation=%s, InternInFall=%s, FallInternLoc=%s, OtherComments=%s WHERE `UID`=%s",
       GetSQLValueString($_POST['UserID'], "int"),
       GetSQLValueString($_POST['txtEditGenInternship'], "text"),
       GetSQLValueString($_POST['txtEditPastInternship'], "text"),
       GetSQLValueString($_POST['BoxEditInterningInFall'], "text"),
       GetSQLValueString($_POST['txtEditFallIntLocation'], "text"),
       GetSQLValueString($_POST['txtEditOtherComment'], "text"),
       GetSQLValueString($_POST['UID'], "int"));

    mysql_select_db($database_localhost, $localhost);
    $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());

    $updateGoTo = "index.php";
    if (isset($_SERVER['QUERY_STRING'])) {
      $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
      $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

require_once('includes/header.php');


$colname_rstViewRegistration = "-1";
if (isset($_SESSION['MM_Username'])) {
    $colname_rstViewRegistration = $_SESSION['MM_Username'];
}
mysql_select_db($database_localhost, $localhost);
$query_rstViewRegistration = sprintf("SELECT * FROM tblUsers WHERE Username = %s", GetSQLValueString($colname_rstViewRegistration, "text"));
$rstViewRegistration = mysql_query($query_rstViewRegistration, $localhost) or die(mysql_error());
$row_rstViewRegistration = mysql_fetch_assoc($rstViewRegistration);
$totalRows_rstViewRegistration = mysql_num_rows($rstViewRegistration);

$colname_rsApplication = "-1";
if (isset($_SESSION['MM_Username'])) {
    $colname_rsApplication = $_SESSION['MM_Username'];
}
mysql_select_db($database_localhost, $localhost);
$query_rsApplication = sprintf("SELECT tblUsers.Username, tblUsers.Firstname, tblUsers.Lastname, tblUsers.Email, tblsqApplication.`UID`, tblsqApplication.Seniority, tblsqApplication.Gender, tblsqApplication.Race, tblsqApplication.OverallGPA, tblsqApplication.MajorGPA, tblsqApplication.Credits, tblsqApplication.Graduation, tblsqApplication.PgmYear, tblsqApplication.Citizenship, tblsqApplication.InternshipInterest, tblsqApplication.PastInternLocation, tblsqApplication.InternInFall, tblsqApplication.FallInternLoc, tblsqApplication.OtherComments FROM tblUsers INNER JOIN tblsqApplication ON tblUsers.UserID=tblsqApplication.UserID WHERE tblUsers.Username = %s", GetSQLValueString($colname_rsApplication, "text"));
$rsApplication = mysql_query($query_rsApplication, $localhost) or die(mysql_error());
$row_rsApplication = mysql_fetch_assoc($rsApplication);
$totalRows_rsApplication = mysql_num_rows($rsApplication);

$maxRows_rsMajor = 10;
$pageNum_rsMajor = 0;
if (isset($_GET['pageNum_rsMajor'])) {
    $pageNum_rsMajor = $_GET['pageNum_rsMajor'];
}
$startRow_rsMajor = $pageNum_rsMajor * $maxRows_rsMajor;

$colname_rsMajor = "-1";
if (isset($_SESSION['MM_Username'])) {
    $colname_rsMajor = $_SESSION['MM_Username'];
}
mysql_select_db($database_localhost, $localhost);
$query_rsMajor = sprintf("SELECT tblUsers.Username, tblsqProgramList.Programs, tblsqProgramDetails.`UID`, tblsqProgramDetails.MID, tblsqProgramDetails.Type, tblsqProgramDetails.OtherMajors  FROM tblsqProgramDetails INNER JOIN tblsqProgramList ON tblsqProgramDetails.MID = tblsqProgramList.MID INNER JOIN tblUsers ON tblUsers.UID = tblsqProgramDetails.UID WHERE tblUsers.Username = %s", GetSQLValueString($colname_rsMajor, "text"));
$query_limit_rsMajor = sprintf("%s LIMIT %d, %d", $query_rsMajor, $startRow_rsMajor, $maxRows_rsMajor);
$rsMajor = mysql_query($query_limit_rsMajor, $localhost) or die(mysql_error());
$row_rsMajor = mysql_fetch_assoc($rsMajor);

if (isset($_GET['totalRows_rsMajor'])) {
    $totalRows_rsMajor = $_GET['totalRows_rsMajor'];
} else {
    $all_rsMajor = mysql_query($query_rsMajor);
    $totalRows_rsMajor = mysql_num_rows($all_rsMajor);
}
$totalPages_rsMajor = ceil($totalRows_rsMajor/$maxRows_rsMajor)-1;

$maxRows_rsViewUNIVProgram = 5;
$pageNum_rsViewUNIVProgram = 0;
if (isset($_GET['pageNum_rsViewUNIVProgram'])) {
    $pageNum_rsViewUNIVProgram = $_GET['pageNum_rsViewUNIVProgram'];
}
$startRow_rsViewUNIVProgram = $pageNum_rsViewUNIVProgram * $maxRows_rsViewUNIVProgram;

$colname_rsViewUNIVProgram = "-1";
if (isset($_SESSION['MM_Username'])) {
    $colname_rsViewUNIVProgram = $_SESSION['MM_Username'];
}
mysql_select_db($database_localhost, $localhost);
$query_rsViewUNIVProgram = sprintf("SELECT tblsqUNIVProgramDetails.LID, CONCAT(b.LLPName, ' - ', a.LLPName) AS UPrograms, tblUsers.Username, tblsqUNIVProgramDetails.`UID` FROM tblsqUNIVPrograms a INNER JOIN tblsqUNIVPrograms b ON a.MainLID=b.LID INNER JOIN tblsqUNIVProgramDetails ON tblsqUNIVProgramDetails.LID = a.LID INNER JOIN tblUsers ON tblsqUNIVProgramDetails.UID = tblUsers.UID WHERE tblUsers.Username = %s ", GetSQLValueString($colname_rsViewUNIVProgram, "text"));
$query_limit_rsViewUNIVProgram = sprintf("%s LIMIT %d, %d", $query_rsViewUNIVProgram, $startRow_rsViewUNIVProgram, $maxRows_rsViewUNIVProgram);
$rsViewUNIVProgram = mysql_query($query_limit_rsViewUNIVProgram, $localhost) or die(mysql_error());
$row_rsViewUNIVProgram = mysql_fetch_assoc($rsViewUNIVProgram);

if (isset($_GET['totalRows_rsViewUNIVProgram'])) {
    $totalRows_rsViewUNIVProgram = $_GET['totalRows_rsViewUNIVProgram'];
} else {
    $all_rsViewUNIVProgram = mysql_query($query_rsViewUNIVProgram);
    $totalRows_rsViewUNIVProgram = mysql_num_rows($all_rsViewUNIVProgram);
}
$totalPages_rsViewUNIVProgram = ceil($totalRows_rsViewUNIVProgram/$maxRows_rsViewUNIVProgram)-1;

$maxRows_rsConcentration = 6;
$pageNum_rsConcentration = 0;
if (isset($_GET['pageNum_rsConcentration'])) {
    $pageNum_rsConcentration = $_GET['pageNum_rsConcentration'];
}
$startRow_rsConcentration = $pageNum_rsConcentration * $maxRows_rsConcentration;

$colname_rsConcentration = "-1";
if (isset($_SESSION['MM_Username'])) {
    $colname_rsConcentration = $_SESSION['MM_Username'];
}
mysql_select_db($database_localhost, $localhost);
$query_rsConcentration = sprintf("SELECT  tblsqConcentration.Concentration, tblsqApplicationRank.ConID, tblsqApplicationRank.Rank, tblsqApplicationRank.RankID, tblsqApplication.UID, tblsqApplication.PgmYear, tblUsers.UserID, tblUsers.Username FROM tblsqConcentration RIGHT JOIN tblsqApplicationRank ON tblsqApplicationRank.ConID = tblsqConcentration.ConID LEFT JOIN tblsqApplication ON tblsqApplicationRank.UID = tblsqApplication.UID AND tblsqApplicationRank.PgmYear = tblsqApplication.PgmYear LEFT JOIN tblUsers ON tblsqApplication.UserID = tblUsers.UserID WHERE tblUsers.Username = %s ", GetSQLValueString($colname_rsConcentration, "text"));
$query_limit_rsConcentration = sprintf("%s LIMIT %d, %d", $query_rsConcentration, $startRow_rsConcentration, $maxRows_rsConcentration);
$rsConcentration = mysql_query($query_limit_rsConcentration, $localhost) or die(mysql_error());
$row_rsConcentration = mysql_fetch_assoc($rsConcentration);

if (isset($_GET['totalRows_rsConcentration'])) {
    $totalRows_rsConcentration = $_GET['totalRows_rsConcentration'];
} else {
    $all_rsConcentration = mysql_query($query_rsConcentration);
    $totalRows_rsConcentration = mysql_num_rows($all_rsConcentration);
}
$totalPages_rsConcentration = ceil($totalRows_rsConcentration/$maxRows_rsConcentration)-1;

$maxRows_rsCitizenshipList = 4;
$pageNum_rsCitizenshipList = 0;
if (isset($_GET['pageNum_rsCitizenshipList'])) {
    $pageNum_rsCitizenshipList = $_GET['pageNum_rsCitizenshipList'];
}
$startRow_rsCitizenshipList = $pageNum_rsCitizenshipList * $maxRows_rsCitizenshipList;

$colname_rsCitizenshipList = "-1";
if (isset($_SESSION['MM_Username'])) {
    $colname_rsCitizenshipList = $_SESSION['MM_Username'];
}
mysql_select_db($database_localhost, $localhost);
$query_rsCitizenshipList = sprintf("SELECT tblsqCitizenshipDetails.UID, tblsqCitizenshipDetails.CountryID, tblsqCountry.Country, tblUsers.Username FROM tblsqCountry RIGHT JOIN tblsqCitizenshipDetails ON tblsqCountry.CountryID = tblsqCitizenshipDetails.CountryID LEFT JOIN tblUsers ON tblsqCitizenshipDetails.UID = tblUsers.UID WHERE tblUsers.Username = %s ORDER BY tblsqCountry.Country", GetSQLValueString($colname_rsCitizenshipList, "text"));
$query_limit_rsCitizenshipList = sprintf("%s LIMIT %d, %d", $query_rsCitizenshipList, $startRow_rsCitizenshipList, $maxRows_rsCitizenshipList);
$rsCitizenshipList = mysql_query($query_limit_rsCitizenshipList, $localhost) or die(mysql_error());
$row_rsCitizenshipList = mysql_fetch_assoc($rsCitizenshipList);

if (isset($_GET['totalRows_rsCitizenshipList'])) {
    $totalRows_rsCitizenshipList = $_GET['totalRows_rsCitizenshipList'];
} else {
    $all_rsCitizenshipList = mysql_query($query_rsCitizenshipList);
    $totalRows_rsCitizenshipList = mysql_num_rows($all_rsCitizenshipList);
}
$totalPages_rsCitizenshipList = ceil($totalRows_rsCitizenshipList/$maxRows_rsCitizenshipList)-1;

$maxRows_rsSupplementaryCourses = 5;
$pageNum_rsSupplementaryCourses = 0;
if (isset($_GET['pageNum_rsSupplementaryCourses'])) {
    $pageNum_rsSupplementaryCourses = $_GET['pageNum_rsSupplementaryCourses'];
}
$startRow_rsSupplementaryCourses = $pageNum_rsSupplementaryCourses * $maxRows_rsSupplementaryCourses;

$colname_rsSupplementaryCourses = "-1";
if (isset($_SESSION['MM_Username'])) {
    $colname_rsSupplementaryCourses = $_SESSION['MM_Username'];
}
mysql_select_db($database_localhost, $localhost);
$query_rsSupplementaryCourses = sprintf("SELECT tblUsers.UserID, tblUsers.Username, tblsqSupplementaryDetails.CourseID, tblsqSupplementaryDetails.YearTaken, tblsqSupplementaryDetails.`UID`, tblsqSupplementaryCourses.CourseTitle FROM tblsqSupplementaryDetails INNER JOIN  tblUsers  ON tblsqSupplementaryDetails.UID=tblUsers.UID  INNER JOIN tblsqSupplementaryCourses  ON tblsqSupplementaryCourses.CourseID = tblsqSupplementaryDetails.CourseID WHERE tblUsers.Username = %s ", GetSQLValueString($colname_rsSupplementaryCourses, "text"));
$query_limit_rsSupplementaryCourses = sprintf("%s LIMIT %d, %d", $query_rsSupplementaryCourses, $startRow_rsSupplementaryCourses, $maxRows_rsSupplementaryCourses);
$rsSupplementaryCourses = mysql_query($query_limit_rsSupplementaryCourses, $localhost) or die(mysql_error());
$row_rsSupplementaryCourses = mysql_fetch_assoc($rsSupplementaryCourses);

if (isset($_GET['totalRows_rsSupplementaryCourses'])) {
    $totalRows_rsSupplementaryCourses = $_GET['totalRows_rsSupplementaryCourses'];
} else {
    $all_rsSupplementaryCourses = mysql_query($query_rsSupplementaryCourses);
    $totalRows_rsSupplementaryCourses = mysql_num_rows($all_rsSupplementaryCourses);
}
$totalPages_rsSupplementaryCourses = ceil($totalRows_rsSupplementaryCourses/$maxRows_rsSupplementaryCourses)-1;

$maxRows_rsDocuments = 5;
$pageNum_rsDocuments = 0;
if (isset($_GET['pageNum_rsDocuments'])) {
    $pageNum_rsDocuments = $_GET['pageNum_rsDocuments'];
}
$startRow_rsDocuments = $pageNum_rsDocuments * $maxRows_rsDocuments;

$colname_rsDocuments = "-1";
if (isset($_SESSION['MM_Username'])) {
    $colname_rsDocuments = $_SESSION['MM_Username'];
}
mysql_select_db($database_localhost, $localhost);
$query_rsDocuments = sprintf("SELECT tblUsers.Username, tblsqDocuments.docName, tblsqDocuments.docContent, tblsqDocuments.PgmYear, tblsqDocuments.`UID`, tblsqDocuments.docCategory, tblsqDocuments.docID FROM tblsqDocuments INNER JOIN tblUsers  ON tblUsers.UID = tblsqDocuments.UID  INNER JOIN tblsqApplication  ON tblsqApplication.UID = tblsqDocuments.UID AND  tblsqApplication.PgmYear = tblsqDocuments.PgmYear WHERE tblUsers.Username = %s", GetSQLValueString($colname_rsDocuments, "text"));
$query_limit_rsDocuments = sprintf("%s LIMIT %d, %d", $query_rsDocuments, $startRow_rsDocuments, $maxRows_rsDocuments);
$rsDocuments = mysql_query($query_limit_rsDocuments, $localhost) or die(mysql_error());
$row_rsDocuments = mysql_fetch_assoc($rsDocuments);

if (isset($_GET['totalRows_rsDocuments'])) {
    $totalRows_rsDocuments = $_GET['totalRows_rsDocuments'];
} else {
    $all_rsDocuments = mysql_query($query_rsDocuments);
    $totalRows_rsDocuments = mysql_num_rows($all_rsDocuments);
}
$totalPages_rsDocuments = ceil($totalRows_rsDocuments/$maxRows_rsDocuments)-1;

$maxRows_rsMarketing = 6;
$pageNum_rsMarketing = 0;
if (isset($_GET['pageNum_rsMarketing'])) {
    $pageNum_rsMarketing = $_GET['pageNum_rsMarketing'];
}
$startRow_rsMarketing = $pageNum_rsMarketing * $maxRows_rsMarketing;

$colname_rsMarketing = "-1";
if (isset($_SESSION['MM_Username'])) {
    $colname_rsMarketing = $_SESSION['MM_Username'];
}
mysql_select_db($database_localhost, $localhost);
$query_rsMarketing = sprintf("SELECT tblUsers.UserID, tblUsers.Username, tblsqMarketing.MarketingType, tblsqMarketingDetails.UID, tblsqMarketingDetails.PgmYear, tblsqMarketingDetails.MarketingID, tblsqMarketingDetails.MarketingLocation FROM tblsqMarketingDetails INNER JOIN  tblUsers  ON tblsqMarketingDetails.UID=tblUsers.UID  INNER JOIN tblsqMarketing ON tblsqMarketingDetails.MarketingID = tblsqMarketing.MarketingID WHERE tblUsers.Username = %s ", GetSQLValueString($colname_rsMarketing, "text"));
$query_limit_rsMarketing = sprintf("%s LIMIT %d, %d", $query_rsMarketing, $startRow_rsMarketing, $maxRows_rsMarketing);
$rsMarketing = mysql_query($query_limit_rsMarketing, $localhost) or die(mysql_error());
$row_rsMarketing = mysql_fetch_assoc($rsMarketing);

if (isset($_GET['totalRows_rsMarketing'])) {
    $totalRows_rsMarketing = $_GET['totalRows_rsMarketing'];
} else {
    $all_rsMarketing = mysql_query($query_rsMarketing);
    $totalRows_rsMarketing = mysql_num_rows($all_rsMarketing);
}
$totalPages_rsMarketing = ceil($totalRows_rsMarketing/$maxRows_rsMarketing)-1;

$maxRows_rsLanguages = 5;
$pageNum_rsLanguages = 0;
if (isset($_GET['pageNum_rsLanguages'])) {
    $pageNum_rsLanguages = $_GET['pageNum_rsLanguages'];
}
$startRow_rsLanguages = $pageNum_rsLanguages * $maxRows_rsLanguages;

$colname_rsLanguages = "-1";
if (isset($_SESSION['MM_Username'])) {
    $colname_rsLanguages = $_SESSION['MM_Username'];
}
mysql_select_db($database_localhost, $localhost);
$query_rsLanguages = sprintf("SELECT tblsqLanguage.Language, tblsqLanguage.CountryID, tblsqLanguageDetails.UID, tblsqLanguageDetails.LanguageID, tblsqLanguageDetails.ProficiencyLevel, tblUsers.Username  FROM tblsqLanguageDetails INNER JOIN tblsqLanguage ON tblsqLanguageDetails.LanguageID=tblsqLanguage.LanguageID INNER JOIN tblUsers ON tblUsers.UID=tblsqLanguageDetails.UID WHERE tblUsers.Username = %s", GetSQLValueString($colname_rsLanguages, "text"));
$query_limit_rsLanguages = sprintf("%s LIMIT %d, %d", $query_rsLanguages, $startRow_rsLanguages, $maxRows_rsLanguages);
$rsLanguages = mysql_query($query_limit_rsLanguages, $localhost) or die(mysql_error());
$row_rsLanguages = mysql_fetch_assoc($rsLanguages);

if (isset($_GET['totalRows_rsLanguages'])) {
    $totalRows_rsLanguages = $_GET['totalRows_rsLanguages'];
} else {
    $all_rsLanguages = mysql_query($query_rsLanguages);
    $totalRows_rsLanguages = mysql_num_rows($all_rsLanguages);
}
$totalPages_rsLanguages = ceil($totalRows_rsLanguages/$maxRows_rsLanguages)-1;

$maxRows_rsViewScholarship = 5;
$pageNum_rsViewScholarship = 0;
if (isset($_GET['pageNum_rsViewScholarship'])) {
    $pageNum_rsViewScholarship = $_GET['pageNum_rsViewScholarship'];
}
$startRow_rsViewScholarship = $pageNum_rsViewScholarship * $maxRows_rsViewScholarship;

$colname_rsViewScholarship = "-1";
if (isset($_SESSION['MM_Username'])) {
    $colname_rsViewScholarship = $_SESSION['MM_Username'];
}
mysql_select_db($database_localhost, $localhost);
$query_rsViewScholarship = sprintf("SELECT tblsqScholarshipDetails.ScholarshipID, tblsqScholarshipDetails.`UID`, tblsqScholarshipDetails.ScholarshipYear, tblsqScholarship.Scholarship, tblUsers.Username FROM tblsqScholarshipDetails INNER JOIN tblsqScholarship ON tblsqScholarshipDetails.ScholarshipID=tblsqScholarship.ScholarshipID INNER JOIN tblUsers ON tblsqScholarshipDetails.UID=tblUsers.UID WHERE tblUsers.Username = %s", GetSQLValueString($colname_rsViewScholarship, "text"));
$query_limit_rsViewScholarship = sprintf("%s LIMIT %d, %d", $query_rsViewScholarship, $startRow_rsViewScholarship, $maxRows_rsViewScholarship);
$rsViewScholarship = mysql_query($query_limit_rsViewScholarship, $localhost) or die(mysql_error());
$row_rsViewScholarship = mysql_fetch_assoc($rsViewScholarship);

if (isset($_GET['totalRows_rsViewScholarship'])) {
    $totalRows_rsViewScholarship = $_GET['totalRows_rsViewScholarship'];
} else {
    $all_rsViewScholarship = mysql_query($query_rsViewScholarship);
    $totalRows_rsViewScholarship = mysql_num_rows($all_rsViewScholarship);
}
$totalPages_rsViewScholarship = ceil($totalRows_rsViewScholarship/$maxRows_rsViewScholarship)-1;

$maxRows_rsStudyAbroad = 5;
$pageNum_rsStudyAbroad = 0;
if (isset($_GET['pageNum_rsStudyAbroad'])) {
    $pageNum_rsStudyAbroad = $_GET['pageNum_rsStudyAbroad'];
}
$startRow_rsStudyAbroad = $pageNum_rsStudyAbroad * $maxRows_rsStudyAbroad;

$colname_rsStudyAbroad = "-1";
if (isset($_SESSION['MM_Username'])) {
    $colname_rsStudyAbroad = $_SESSION['MM_Username'];
}
mysql_select_db($database_localhost, $localhost);
$query_rsStudyAbroad = sprintf("SELECT tblsqStudyAbroad.StudyAID, tblsqStudyAbroad.`UID`, tblsqStudyAbroad.Institution, tblsqStudyAbroad.StudyYear, tblsqCountry.Country, tblUsers.Username FROM tblsqStudyAbroad INNER JOIN tblsqCountry ON tblsqCountry.CountryID=tblsqStudyAbroad.CountryID INNER JOIN tblUsers ON tblUsers.UID=tblsqStudyAbroad.UID WHERE tblUsers.Username=%s", GetSQLValueString($colname_rsStudyAbroad, "text"));
$query_limit_rsStudyAbroad = sprintf("%s LIMIT %d, %d", $query_rsStudyAbroad, $startRow_rsStudyAbroad, $maxRows_rsStudyAbroad);
$rsStudyAbroad = mysql_query($query_limit_rsStudyAbroad, $localhost) or die(mysql_error());
$row_rsStudyAbroad = mysql_fetch_assoc($rsStudyAbroad);

if (isset($_GET['totalRows_rsStudyAbroad'])) {
    $totalRows_rsStudyAbroad = $_GET['totalRows_rsStudyAbroad'];
} else {
    $all_rsStudyAbroad = mysql_query($query_rsStudyAbroad);
    $totalRows_rsStudyAbroad = mysql_num_rows($all_rsStudyAbroad);
}
$totalPages_rsStudyAbroad = ceil($totalRows_rsStudyAbroad/$maxRows_rsStudyAbroad)-1;
?>


<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin</title>
    <link href="../jQueryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
    <link href="../jQueryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
    <link href="../jQueryAssets/jquery.ui.tabs.min.css" rel="stylesheet" type="text/css">
    <link href="../css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="../css/style.css" rel="stylesheet" type="text/css">
    <script src="../jQueryAssets/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script src="../jQueryAssets/jquery.ui-1.10.4.tabs.min.js" type="text/javascript"></script>
    <script src="../js/ControlPanel2.js" type="text/javascript"></script> 
    <!-- <script type="text/javascript">
      function MM_goToURL() { //v3.0
        var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
        for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
      }
  </script> -->
</head>

<body>
    <div class="container">
        <a href="Admin1.php">Admin</a> | <a href="../logout.php"> Log Out</a>
        <p> Welcome
            <?php 
            echo $row_rstViewRegistration['Firstname']; 
            ?>! 
            <?php 
            echo $row_rstViewRegistration['UID']; 
            ?>
        </p>
        <form action="<?php echo $editFormAction; ?>" id="frmControlPanel" name="frmControlPanel" method="POST">
            <p>Please click links below to complete the application form:<?php echo $row_rstViewRegistration['Username']; ?></p>

            <div id="Tabs1" class="col-md-12">
                <ul class="nav nav-pills">
                    <li class="active"><a href="#tabs-1">Background</a></li>
                    <li><a href="#tabs-2">Major(s)</a></li>
                    <li><a href="#tabs-3">University Programs</a></li>
                    <li><a href="#tabs-4">Rank Concentrations</a></li>
                    <li><a href="#tabs-6">Internship Interest</a></li>
                    <li><a href="#tabs-5">Supplementary Courses</a></li>
                    <li><a href="#tabs-7">Upload Documents</a></li>
                    <li><a href="#tabs-8">Survey</a></li>
                    <li><a href="#tabs-9">Review Application</a></li>
                </ul>

                <div id="tabs-1">
                    <?php include 'background.php'; ?>
                </div>

                <div id="tabs-2">
                    <?php include 'major.php'; ?>
                </div>

                <div id="tabs-3">
                    <?php include 'program.php'; ?>
                </div>

                <div id="tabs-4">
                    <?php include 'concentration.php'; ?>
                </div>

                <div id="tabs-5">
                    <?php include 'supplementary.php'; ?>
                </div>

                <div id="tabs-6">
                    <?php include 'internship.php'; ?>
                </div>

                <div id="tabs-7">
                    <?php include 'upload.php'; ?>
                </div>

                <div id="tabs-8">
                    <?php include 'survey.php'; ?>
                </div>

                <div id="tabs-9">
                    <h1 align="center"><i><a href="ReviewApplicationAdmin.php">Review Application</a></i></h1>
                </div>

            </div>
            <input type="hidden" name="MM_update" value="frmControlPanel">
        </form>
        <script type="text/javascript">
          $(function() {
             $( "#Tabs1" ).tabs(); 
         });
        </script>
    </div>
</body>
</html>

<?php
mysql_free_result($rstViewRegistration);

mysql_free_result($rsApplication);

mysql_free_result($rsMajor);

mysql_free_result($rsViewUNIVProgram);

mysql_free_result($rsConcentration);

mysql_free_result($rsCitizenshipList);

mysql_free_result($rsSupplementaryCourses);

mysql_free_result($rsDocuments);

mysql_free_result($rsMarketing);

mysql_free_result($rsLanguages);

mysql_free_result($rsViewScholarship);

mysql_free_result($rsStudyAbroad);
?>
