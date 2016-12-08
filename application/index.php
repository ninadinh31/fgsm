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
    $deleteSQL = sprintf("DELETE FROM tblsqdocuments WHERE docID=%s",
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
  $query_rsSupplementaryCourses = sprintf("SELECT tblUsers.UserID, tblUsers.Username, tblsqSupplementaryDetails.CourseID, tblsqSupplementaryDetails.YearTaken, tblsqSupplementaryDetails.`UID`, tblsqsupplementarycourses.CourseTitle FROM tblsqSupplementaryDetails INNER JOIN  tblUsers  ON tblsqSupplementaryDetails.UID=tblUsers.UID  INNER JOIN tblsqsupplementarycourses  ON tblsqsupplementarycourses.CourseID = tblsqSupplementaryDetails.CourseID WHERE tblUsers.Username = %s ", GetSQLValueString($colname_rsSupplementaryCourses, "text"));
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
  $query_rsDocuments = sprintf("SELECT tblUsers.Username, tblsqdocuments.docName, tblsqdocuments.docContent, tblsqdocuments.PgmYear, tblsqdocuments.`UID`, tblsqdocuments.docCategory, tblsqdocuments.docID FROM tblsqdocuments INNER JOIN tblUsers  ON tblUsers.UID = tblsqdocuments.UID  INNER JOIN tblsqApplication  ON tblsqApplication.UID = tblsqdocuments.UID AND  tblsqApplication.PgmYear = tblsqdocuments.PgmYear WHERE tblUsers.Username = %s", GetSQLValueString($colname_rsDocuments, "text"));
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
  $query_rsLanguages = sprintf("SELECT tblsqlanguage.`Language`, tblsqlanguage.CountryID, tblsqlanguagedetails.`UID`, tblsqlanguagedetails.LanguageID, tblsqlanguagedetails.ProficiencyLevel, tblUsers.Username  FROM tblsqlanguagedetails INNER JOIN tblsqLanguage ON tblsqlanguagedetails.LanguageID=tblsqlanguage.LanguageID INNER JOIN tblUsers ON tblUsers.UID=tblsqlanguagedetails.UID WHERE tblUsers.Username = %s", GetSQLValueString($colname_rsLanguages, "text"));
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
  $query_rsViewScholarship = sprintf("SELECT tblsqscholarshipdetails.ScholarshipID, tblsqscholarshipdetails.`UID`, tblsqscholarshipdetails.ScholarshipYear, tblsqscholarship.Scholarship, tblUsers.Username FROM tblsqscholarshipdetails INNER JOIN tblsqscholarship ON tblsqscholarshipdetails.ScholarshipID=tblsqscholarship.ScholarshipID INNER JOIN tblUsers ON tblsqscholarshipdetails.UID=tblUsers.UID WHERE tblUsers.Username = %s", GetSQLValueString($colname_rsViewScholarship, "text"));
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
  $query_rsStudyAbroad = sprintf("SELECT tblsqstudyabroad.StudyAID, tblsqstudyabroad.`UID`, tblsqstudyabroad.Institution, tblsqstudyabroad.StudyYear, tblsqCountry.Country, tblUsers.Username FROM tblsqstudyabroad INNER JOIN tblsqCountry ON tblsqCountry.CountryID=tblsqstudyabroad.CountryID INNER JOIN tblUsers ON tblUsers.UID=tblsqstudyabroad.UID WHERE tblUsers.Username=%s", GetSQLValueString($colname_rsStudyAbroad, "text"));
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
      <!-- <img src="images/smallheader.jpg" alt="Federal/Global Semester Banner" style="margin-bottom:1px;position:relative;width:100%"> -->
      <h2 align="center">
        Welcome to the Federal and Global Semester 2016 -  2017 Online Application System<br>
        <a href="Admin1.php">Admin</a> | <a href="Logout1.php"> Log Out</a>
      </h2>
      <p align="center">Welcome
        <?php 
          echo $row_rstViewRegistration['Firstname']; 
        ?>! 
        <?php 
          echo $row_rstViewRegistration['UID']; 
        ?>
      </p>
      <form action="<?php echo $editFormAction; ?>" id="frmControlPanel" name="frmControlPanel" method="POST">
        <p>Please click links below to complete the application form:<?php echo $row_rstViewRegistration['Username']; ?></p>
        
        
        <div id="Tabs1">
          <ul>
            <li><a href="#tabs-1">Background</a></li>
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
            <h3>Background</h3>
            <table class="table">
              <tbody>
                <tr>
                  <td width="111"><strong>Seniority</strong></td>
                  <td width="219">
                    <?php 
                      echo $row_rsApplication['Seniority'];
                    ?>
                  </td>
                  <td width="183"><strong>Citizenship</strong></td>
                  <td width="446">
                    <?php 
                      echo $row_rsApplication['Citizenship'];
                    ?>
                  </td>
                </tr>
                <tr>
                  <td><strong>Gender</strong></td>
                  <td>
                    <?php 
                      echo $row_rsApplication['Gender']; 
                    ?>
                  </td>
                  <td rowspan="2"><strong>General Internship Interest</strong></td>
                  <td rowspan="2">
                    <?php 
                      echo $row_rsApplication['InternshipInterest']; 
                    ?>
                  </td>
                </tr>
                <tr>
                  <td><strong>Race</strong></td>
                  <td>
                    <?php 
                      echo $row_rsApplication['Race']; 
                    ?>
                  </td>
                </tr>
                <tr>
                  <td><strong>Overall GPA</strong></td>
                  <td>
                    <?php 
                      printf("%.2f", $row_rsApplication['OverallGPA']); 
                    ?>
                  </td>
                  <td rowspan="2"><strong>Past Students Internship</strong></td>
                  <td rowspan="2">
                    <?php 
                      echo $row_rsApplication['PastInternLocation']; 
                    ?>
                  </td>
                </tr>
                <tr>
                  <td><strong>Major GPA</strong></td>
                  <td>
                    <?php 
                      printf("%.2f", $row_rsApplication['MajorGPA']); 
                    ?>
                  </td>
                </tr>
                <tr>
                  <td><strong>Credits</strong></td>
                  <td>
                    <?php 
                      echo $row_rsApplication['Credits']; 
                    ?>
                  </td>
                  <td><strong>Interning in Fall 2016?</strong></td>
                  <td>
                    <?php 
                      echo $row_rsApplication['InternInFall']; 
                    ?>
                  </td>
                </tr>
                <tr>
                  <td><strong>Graduation Date</strong></td>
                  <td>
                    <?php 
                      echo date("m/d/Y", strtotime($row_rsApplication['Graduation'])); 
                    ?>
                  </td>
                  <td rowspan="2"><strong>If Interning in Fall, please specify location</strong></td>
                  <td rowspan="2">
                    <?php 
                      echo $row_rsApplication['FallInternLoc']; 
                    ?>
                  </td>
                </tr>
              </tbody>
            </table>
            <input name="EditBackground" type="button" class="btn btn-primary" id="EditBackground" onClick="MM_goToURL('parent','EditBackground1.php');return document.MM_returnValue" value="Edit Background"> 
            <br>
            <h3>Citizenship</h3>
            <table class="table">
            	<thead>
              	<th>Country</th>
                <th>&nbsp;</th>
              </thead>
              <tbody>
                <?php 
                  do { 
                ?>
                <tr>
                  <td width="363"><?php echo $row_rsCitizenshipList['Country']; ?>
                    <input name="hiddenField" type="hidden" id="hiddenField" value="<?php echo $row_rstViewRegistration['UserID']; ?>"></td>
                  <td width="116">
                    <a href="DeleteCountry1.php?UID=<?php echo $row_rsCitizenshipList['UID'];?> && CountryID=<?php echo 
                    $row_rsCitizenshipList['CountryID'];?>">
                      Delete Country
                    </a>
                  </td>
                </tr>
                <?php 
                  } while ($row_rsCitizenshipList = mysql_fetch_assoc($rsCitizenshipList));
                ?>
              </tbody>
            </table>
            <input name="AddCitizenship" type="button" class="btn btn-primary" onClick="MM_goToURL('parent','Citizenship1.php');return document.MM_returnValue" value="Add Citizenship"> 
            <br>
            <h3>Language Skills (if any)</h3>
            <?php 
              do { 
            ?>
              <table class="table">
                <thead>
                	<th>Language</th>
                  <th>&nbsp;</th>
                </thead>
                <tbody>
                  <tr>
                    <td width="365"><?php echo $row_rsLanguages['Language']; ?>
                    <input name="hiddenUID" type="hidden" id="hiddenUID" value="<?php echo $row_rsLanguages['UID']; ?>"></td>
                    <td width="118">&nbsp;</td>
                  </tr>
                </tbody>
              </table>
            <?php 
              } while ($row_rsLanguages = mysql_fetch_assoc($rsLanguages)); 
            ?>
            <input name="butAddLanguage" type="button" class="btn btn-primary" onClick="MM_goToURL('parent','AddLanguage1.php');return document.MM_returnValue" value="Add Language">
          </div>
          
          <div id="tabs-2">
            <h3 align="center">Major</h3>
            <table width="493" border="3" align="center">
              <tbody>
                <tr>
                  <td width="259">
                    <strong>Program
                      <input name="UID" type="hidden" id="UID" value="<?php echo $row_rsMajor['UID']; ?>">
                      <input name="MID" type="hidden" id="MID" value="<?php echo $row_rsMajor['MID']; ?>">
                    </strong>
                  </td>
                  <td width="106"><strong>Type</strong></td>
                  <td width="106">&nbsp;</td>
                </tr>
                <?php 
                  do { 
                ?>
                  <tr>
                    <td><?php echo $row_rsMajor['Programs']; ?></td>
                    <td><?php echo $row_rsMajor['Type']; ?></td>
                    <td><a href="DeleteMajor1.php?UID=<?php echo $row_rsMajor['UID'];?> && MID=<?php echo $row_rsMajor['MID'];?>">Delete Major</a></td>
                  </tr>
                <?php } while ($row_rsMajor = mysql_fetch_assoc($rsMajor)); ?>
              </tbody>
            </table>
            <p align="center">
              <input name="Add Major" type="button" id="Add Major" onClick="MM_goToURL('parent','Majors1.php');return document.MM_returnValue" value="Add Major">
              <input name="Editbutton" type="button" id="Editbutton" onClick="MM_goToURL('parent','OtherMajor1.php');return document.MM_returnValue" value="Edit Other Major">
            </p>
          </div>
          
          <div id="tabs-3">
            <h3 align="center">University Programs</h3>
            <table width="893" border="1px solid black" align="center">
              <tbody>
                <tr>
                  <td width="498"><strong>University Programs
                    <input name="UID" type="hidden" id="UID" value="<?php echo $row_rsViewUNIVProgram['UID']; ?>">
                    <input name="LID" type="hidden" id="LID" value="<?php echo $row_rsViewUNIVProgram['LID']; ?>">
                  </strong></td>
                  <td width="379">&nbsp;</td>
                </tr>
                <?php 
                  do { 
                ?>
                  <tr>
                    <td><?php echo $row_rsViewUNIVProgram['UPrograms']; ?></td>
                    <td><a href="DeleteUniversityPrograms1.php?UID=<?php echo $row_rsViewUNIVProgram['UID'];?> && LID=<?php echo 	  $row_rsViewUNIVProgram['LID'];?>">Delete Program</a></td>
                  </tr>
                <?php 
                  } while ($row_rsViewUNIVProgram = mysql_fetch_assoc($rsViewUNIVProgram)); 
                ?>
              </tbody>
            </table>
            <p align="center">
              <input name="Add University Programs" type="button" id="AddUniPrograms" onClick="MM_goToURL('parent','UniversityPrograms.php');return document.MM_returnValue" value="Add University Programs">
            </p>
            <br>
            <h3 align="center">Scholarships</h3>
            <table align="center" width="892" border="1">
              <tbody>
                <tr>
                  <td width="502"><strong>Scholarship(s)
                    <input name="UID" type="hidden" id="UID" value="<?php echo $row_rsViewScholarship['UID']; ?>">
                    <input name="ScholarshipID" type="hidden" id="ScholarshipID" value="<?php echo $row_rsViewScholarship['ScholarshipID']; ?>">
                    </strong>
                  </td>
                  <td width="232"><strong>Year</strong></td>
                  <td width="136">&nbsp;</td>
                </tr>
               <?php do { ?>
                <tr>
                  <td><?php echo $row_rsViewScholarship['Scholarship']; ?></td>
                  <td><?php echo $row_rsViewScholarship['ScholarshipYear']; ?></td>
                  <td><a href="DeleteScholarship1.php?UID=<?php echo $row_rsViewScholarship['UID'];?> && ScholarshipID=<?php echo $row_rsViewScholarship['ScholarshipID'];?>">Delete Scholarship</a></td>
                </tr>
                <?php } while ($row_rsViewScholarship = mysql_fetch_assoc($rsViewScholarship)); ?>
              </tbody>
            </table>
            <p align="center">
              <input name="butAddScholarship" type="button" id="butAddScholarship" onClick="MM_goToURL('parent','AddScholarship1.php');return document.MM_returnValue" value="Add Scholarship">
            </p>
            <br>
            <h3 align="center">Study Abroad Experience</h3>
            <table align="center" width="895" border="1">
              <tbody>
                <tr>
                  <td width="294">
                    <strong>Institution/Location
                      <input name="StudyAID" type="hidden" id="StudyAID" value="<?php echo $row_rsStudyAbroad['StudyAID']; ?>">
                    </strong>
                  </td>
                  <td width="246"><strong>Country</strong></td>
                  <td width="190"><strong>Year</strong></td>
                  <td width="137">&nbsp;</td>
                </tr>
                <?php do { ?>
                  <tr>
                    <td><?php echo $row_rsStudyAbroad['Institution']; ?></td>
                    <td><?php echo $row_rsStudyAbroad['Country']; ?></td>
                    <td><?php echo $row_rsStudyAbroad['StudyYear']; ?></td>
                    <td><a href="DeleteStudyAbroad.php?StudyAID=<?php echo $row_rsStudyAbroad['StudyAID'];?>">Delete Study Abroad</a></td>
                  </tr>
                <?php } while ($row_rsStudyAbroad = mysql_fetch_assoc($rsStudyAbroad)); ?>
              </tbody>
            </table>
            <p align="center">
              <input name="butAddStudyAbroad" type="button" id="butAddStudyAbroad" onClick="MM_goToURL('parent','AddStudyAbroad1.php');return document.MM_returnValue" value="Add Study Abroad">
            </p>
          </div>
          
          <div id="tabs-4">
            <h3 align="center">Rank of Concentrations</h3>
            <table align="center" width="872" height="57" border="1">
              <tbody>
                <tr>
                  <td width="165">&nbsp;</td>
                  <td width="286"><strong>Concentration</strong></td>
                  <td width="147"><strong>Rank</strong></td>
                  <td width="246">&nbsp;</td>
                </tr>
                <?php 
                  do { 
                ?>
                  <tr>
                    <td><?php echo $row_rsConcentration['UID']; ?></td>
                    <td><?php echo $row_rsConcentration['Concentration']; ?></td>
                    <td><?php echo $row_rsConcentration['Rank']; ?></td>
                    <td><a href="DeleteConcentration1.php?RankID=<?php echo $row_rsConcentration['RankID'];?>">Delete Concentration</a></td>
                  </tr>
                <?php 
                  } while ($row_rsConcentration = mysql_fetch_assoc($rsConcentration)); 
                ?>
              </tbody>
            </table>
            <br>
            <p align="center">
              <input name="AddConcentration" type="button" id="AddConcentration" onClick="MM_goToURL('parent','Concentration1.php');return document.MM_returnValue" value="Add Concentration">
            </p>
          </div>
          
          <div id="tabs-5">
            <h3 align="center">Supplementary Courses</h3>
            <table align="center" width="883" border="1">
              <tbody>
                <tr>
                  <td width="240"><strong>Supplementary Courses</strong></td>
                  <td width="230"><strong>Course Code</strong></td>
                  <td width="197"><strong>UID</strong></td>
                  <td width="188">&nbsp;</td>
                </tr>
                <?php do { ?>
                <tr>
                  <td><?php echo $row_rsSupplementaryCourses['CourseTitle']; ?></td>
                  <td><?php echo $row_rsSupplementaryCourses['CourseID']; ?></td>
                  <td><?php echo $row_rsSupplementaryCourses['UID']; ?></td>
                  <td><a href="DeleteSupplementaryCrs1.php?UID=<?php echo $row_rsSupplementaryCourses['UID'];?> && CourseID=<?php echo $row_rsSupplementaryCourses['CourseID'];?>">Delete Course</a></td>
                </tr>
                <?php } while ($row_rsSupplementaryCourses = mysql_fetch_assoc($rsSupplementaryCourses)); ?>
              </tbody>
            </table>
            <p align="center">
              <input name="AddSupplementary" type="button" id="AddSupplementary" onClick="MM_goToURL('parent','Supplementary1.php');return document.MM_returnValue" value="Add Supplementary Course">
              </p>
          </div>

          <div id="tabs-6">
            <h3 align="center">Internship Interests</h3>
            <table align="center" width="491" border="1">
              <tbody>
                <tr>
                  <td width="254"><b>General Internship Interest</b></td>
                  <td width="221"><?php echo $row_rsApplication['InternshipInterest']; ?></td>
                </tr>
                <tr>
                  <td><b>Past Student Internship</b></td>
                  <td><?php echo $row_rsApplication['PastInternLocation']; ?></td>
                </tr>
                <tr>
                  <td><b>Interning in Fall 2016?</b></td>
                  <td><?php echo $row_rsApplication['InternInFall']; ?></td>
                </tr>
                <tr>
                  <td><b>If interning in Fall 2016, please specify location</b></td>
                  <td><?php echo $row_rsApplication['FallInternLoc']; ?></td>
                </tr>
                <tr>
                  <td><b>Other comments on your interest or passion</b></td>
                  <td><?php echo $row_rsApplication['OtherComments']; ?></td>
                </tr>
              </tbody>
            </table>
            <p align="center">
              <input name="ButAddIntInterest" type="button" id="ButAddIntInterest" onClick="MM_goToURL('parent','InternshipInterest1.php');return document.MM_returnValue" value="Add/Edit Internship Interest">
            </p>
          </div>
          
          <div id="tabs-7">
            <h3 align="center">Upload Documents</h3>
            <table align="center" width="488" height="53" border="1">
              <tbody>
                <tr>
                  <td width="273"><strong>Document Type</strong></td>
                  <td width="199"><strong>Document</strong></td>
                  <td width="199">&nbsp;</td>
                </tr>
                <?php do { ?>
                <tr>
                  <td><?php echo $row_rsDocuments['docCategory']; ?></td>
                  <td><a href="uploads/<?php echo $row_rsDocuments['docName'] ?>"><?php echo $row_rsDocuments['docName'];?></a></td>
                  <td><input name="txtdocID" type="hidden" id="txtdocID" value="<?php echo $row_rsDocuments['docID']; ?>">
                    <a href="DeleteDoc1.php?docID=<?php echo $row_rsDocuments['docID'] ?>">Delete</a></td>
                </tr>
                <?php } while ($row_rsDocuments = mysql_fetch_assoc($rsDocuments)); ?>
              </tbody>
            </table>
            <p align="center">
              <input name="butUploadDocs" type="button" id="butUploadDocs" onClick="MM_goToURL('parent','Documents1.php');return document.MM_returnValue" value="Upload Documents">
            </p>
          </div>

          <div id="tabs-8">
            <h3 align="center">Survey</h3>
            <p align="center"><i>How did you hear about the Federal Semester and Global Semester Programs?</i></p>
            <table align="center" width="1007" border="1">
              <tbody>
                <tr>
                  <td width="219"><strong>Method</strong></td>
                  <td width="273"><strong>Details</strong></td>
                  <td width="119">UID</td>
                  <td width="156">PgmYear</td>
                  <td width="206">&nbsp;</td>
                </tr>
                <?php do { ?>
                <tr>
                  <td><?php echo $row_rsMarketing['MarketingType']; ?></td>
                  <td><?php echo $row_rsMarketing['MarketingLocation']; ?></td>
                  <td><?php echo $row_rsMarketing['UID']; ?></td>
                  <td><?php echo $row_rsMarketing['PgmYear']; ?></td>
                  <td><a href="DeleteMarketing1.php?UID=<?php echo $row_rsMarketing['UID'];?> && PgmYear=<?php echo $row_rsMarketing['PgmYear'];?> && MarketingID=<?php echo $row_rsMarketing['MarketingID'];?>">Delete</a></td>
                </tr>
                <?php } while ($row_rsMarketing = mysql_fetch_assoc($rsMarketing)); ?>
              </tbody>
            </table>
            <p align="center"><input name="subAddMarketing" type="submit" id="subAddMarketing" onClick="MM_goToURL('parent','Marketing1.php');return document.MM_returnValue" value="Add Survey"></p>
            <p>&nbsp;</p>
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
