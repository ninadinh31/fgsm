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

if ((isset($_GET['docID'])) && ($_GET['docID'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tblsqdocuments WHERE docID=%s",
                       GetSQLValueString($_GET['docID'], "int"));

  mysql_select_db($database_FGSP, $FGSP);
  $Result1 = mysql_query($deleteSQL, $FGSP) or die(mysql_error());

  $deleteGoTo = "ControlPanel2.php#tab-8";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frmControlPanel")) {
  $updateSQL = sprintf("UPDATE tblusers SET UserLevel=%s WHERE UserID=%s",
                       GetSQLValueString($_POST['submit'], "int"),
                       GetSQLValueString($_POST['UserID'], "int"));

  mysql_select_db($database_FGSP, $FGSP);
  $Result1 = mysql_query($updateSQL, $FGSP) or die(mysql_error());

  $updateGoTo = "Submission.php";
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
mysql_select_db($database_FGSP, $FGSP);
$query_rstViewRegistration = sprintf("SELECT * FROM tblusers WHERE Username = %s", GetSQLValueString($colname_rstViewRegistration, "text"));
$rstViewRegistration = mysql_query($query_rstViewRegistration, $FGSP) or die(mysql_error());
$row_rstViewRegistration = mysql_fetch_assoc($rstViewRegistration);
$totalRows_rstViewRegistration = mysql_num_rows($rstViewRegistration);

$colname_rsApplication = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsApplication = $_SESSION['MM_Username'];
}
mysql_select_db($database_FGSP, $FGSP);
$query_rsApplication = sprintf("SELECT tblusers.Username, tblusers.Firstname, tblusers.Lastname, tblusers.Email, tblsqapplication.`UID`, tblsqapplication.Seniority, tblsqapplication.Gender, tblsqapplication.Race, tblsqapplication.OverallGPA, tblsqapplication.MajorGPA, tblsqapplication.Credits, tblsqapplication.Graduation, tblsqapplication.PgmYear, tblsqapplication.Citizenship, tblsqapplication.InternshipInterest, tblsqapplication.PastInternLocation, tblsqapplication.InternInFall, tblsqapplication.FallInternLoc, tblsqapplication.OtherComments FROM tblusers INNER JOIN tblsqapplication ON tblusers.UserID=tblsqapplication.UserID WHERE tblusers.Username = %s", GetSQLValueString($colname_rsApplication, "text"));
$rsApplication = mysql_query($query_rsApplication, $FGSP) or die(mysql_error());
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
mysql_select_db($database_FGSP, $FGSP);
$query_rsMajor = sprintf("SELECT tblusers.Username, tblsqprogramlist.Programs, tblsqprogramdetails.`UID`, tblsqprogramdetails.MID, tblsqprogramdetails.Type, tblsqprogramdetails.OtherMajors  FROM tblsqprogramdetails INNER JOIN tblsqprogramlist ON tblsqprogramdetails.MID = tblsqprogramlist.MID INNER JOIN tblusers ON tblusers.UID = tblsqprogramdetails.UID WHERE tblusers.Username = %s", GetSQLValueString($colname_rsMajor, "text"));
$query_limit_rsMajor = sprintf("%s LIMIT %d, %d", $query_rsMajor, $startRow_rsMajor, $maxRows_rsMajor);
$rsMajor = mysql_query($query_limit_rsMajor, $FGSP) or die(mysql_error());
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
mysql_select_db($database_FGSP, $FGSP);
$query_rsViewUNIVProgram = sprintf("SELECT tblsqunivprogramdetails.LID, CONCAT(b.LLPName, ' - ', a.LLPName) AS UPrograms, tblusers.Username FROM tblsqunivprograms a INNER JOIN tblsqunivprograms b ON a.MainLID=b.LID INNER JOIN tblsqunivprogramdetails ON tblsqunivprogramdetails.LID = a.LID INNER JOIN tblusers ON tblsqunivprogramdetails.UID = tblusers.UID WHERE tblusers.Username = %s ", GetSQLValueString($colname_rsViewUNIVProgram, "text"));
$query_limit_rsViewUNIVProgram = sprintf("%s LIMIT %d, %d", $query_rsViewUNIVProgram, $startRow_rsViewUNIVProgram, $maxRows_rsViewUNIVProgram);
$rsViewUNIVProgram = mysql_query($query_limit_rsViewUNIVProgram, $FGSP) or die(mysql_error());
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
mysql_select_db($database_FGSP, $FGSP);
$query_rsConcentration = sprintf("SELECT tblsqconcentration.Concentration, tblsqapplicationrank.ConID, tblsqapplicationrank.Rank, tblsqapplicationrank.RankID, tblsqapplication.`UID`, tblsqapplication.PgmYear, tblusers.UserID, tblusers.Username FROM tblsqconcentration RIGHT JOIN tblsqapplicationrank ON tblsqapplicationrank.ConID = tblsqconcentration.ConID LEFT JOIN tblsqapplication ON tblsqapplicationrank.UID = tblsqapplication.UID AND tblsqapplicationrank.PgmYear = tblsqapplication.PgmYear LEFT JOIN tblUsers ON tblsqapplication.UserID = tblUsers.UserID WHERE tblusers.Username = %s ", GetSQLValueString($colname_rsConcentration, "text"));
$query_limit_rsConcentration = sprintf("%s LIMIT %d, %d", $query_rsConcentration, $startRow_rsConcentration, $maxRows_rsConcentration);
$rsConcentration = mysql_query($query_limit_rsConcentration, $FGSP) or die(mysql_error());
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
mysql_select_db($database_FGSP, $FGSP);
$query_rsCitizenshipList = sprintf("SELECT tblsqcitizenshipdetails.UID, tblsqcitizenshipdetails.CountryID, tblsqcountry.Country, tblUsers.Username FROM tblsqcountry RIGHT JOIN tblsqcitizenshipdetails ON tblsqcountry.CountryID = tblsqcitizenshipdetails.CountryID LEFT JOIN tblUsers ON tblsqcitizenshipdetails.UID = tblUsers.UID WHERE tblUsers.Username = %s ORDER BY tblsqcountry.Country", GetSQLValueString($colname_rsCitizenshipList, "text"));
$query_limit_rsCitizenshipList = sprintf("%s LIMIT %d, %d", $query_rsCitizenshipList, $startRow_rsCitizenshipList, $maxRows_rsCitizenshipList);
$rsCitizenshipList = mysql_query($query_limit_rsCitizenshipList, $FGSP) or die(mysql_error());
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
mysql_select_db($database_FGSP, $FGSP);
$query_rsSupplementaryCourses = sprintf("SELECT tblusers.UserID, tblusers.Username, tblsqsupplementarydetails.CourseID, tblsqsupplementarydetails.YearTaken, tblsqsupplementarydetails.`UID`, tblsqsupplementarycourses.CourseTitle FROM tblsqsupplementarydetails INNER JOIN  tblusers  ON tblsqsupplementarydetails.UID=tblUsers.UID  INNER JOIN tblsqsupplementarycourses  ON tblsqsupplementarycourses.CourseID = tblsqsupplementarydetails.CourseID WHERE tblusers.Username = %s ", GetSQLValueString($colname_rsSupplementaryCourses, "text"));
$query_limit_rsSupplementaryCourses = sprintf("%s LIMIT %d, %d", $query_rsSupplementaryCourses, $startRow_rsSupplementaryCourses, $maxRows_rsSupplementaryCourses);
$rsSupplementaryCourses = mysql_query($query_limit_rsSupplementaryCourses, $FGSP) or die(mysql_error());
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
mysql_select_db($database_FGSP, $FGSP);
$query_rsDocuments = sprintf("SELECT tblusers.Username, tblsqdocuments.docName, tblsqdocuments.docContent, tblsqdocuments.PgmYear, tblsqdocuments.`UID`, tblsqdocuments.docCategory, tblsqdocuments.docID FROM tblsqdocuments INNER JOIN tblusers  ON tblusers.UID = tblsqdocuments.UID  INNER JOIN tblsqapplication  ON tblsqapplication.UID = tblsqdocuments.UID AND  tblsqapplication.PgmYear = tblsqdocuments.PgmYear WHERE tblusers.Username = %s", GetSQLValueString($colname_rsDocuments, "text"));
$query_limit_rsDocuments = sprintf("%s LIMIT %d, %d", $query_rsDocuments, $startRow_rsDocuments, $maxRows_rsDocuments);
$rsDocuments = mysql_query($query_limit_rsDocuments, $FGSP) or die(mysql_error());
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
mysql_select_db($database_FGSP, $FGSP);
$query_rsMarketing = sprintf("SELECT tblusers.UserID, tblusers.Username, tblsqMarketing.MarketingType, tblsqMarketingDetails.UID, tblsqMarketingDetails.PgmYear, tblsqMarketingDetails.MarketingID, tblsqMarketingDetails.MarketingLocation FROM tblsqMarketingDetails INNER JOIN  tblusers  ON tblsqMarketingDetails.UID=tblUsers.UID  INNER JOIN tblsqMarketing ON tblsqMarketingDetails.MarketingID = tblsqMarketing.MarketingID WHERE tblusers.Username = %s ", GetSQLValueString($colname_rsMarketing, "text"));
$query_limit_rsMarketing = sprintf("%s LIMIT %d, %d", $query_rsMarketing, $startRow_rsMarketing, $maxRows_rsMarketing);
$rsMarketing = mysql_query($query_limit_rsMarketing, $FGSP) or die(mysql_error());
$row_rsMarketing = mysql_fetch_assoc($rsMarketing);

if (isset($_GET['totalRows_rsMarketing'])) {
  $totalRows_rsMarketing = $_GET['totalRows_rsMarketing'];
} else {
  $all_rsMarketing = mysql_query($query_rsMarketing);
  $totalRows_rsMarketing = mysql_num_rows($all_rsMarketing);
}
$totalPages_rsMarketing = ceil($totalRows_rsMarketing/$maxRows_rsMarketing)-1;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin</title>
<link href="../jQueryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="../jQueryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<script src="../jQueryAssets/jquery-1.11.1.min.js" type="text/javascript"></script><script type="text/javascript">
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
</script>
</head>

<body>
<h2>Review and Submit Application  | <a href="Admin1.php">Admin</a> | <a href="Logout1.php"> Log Out</a></h2>
<form action="<?php echo $editFormAction; ?>" id="frmControlPanel" name="frmControlPanel" method="POST">
  <p>Please click links below to complete the application form:<?php echo $row_rstViewRegistration['Username']; ?></p>
  <?php if ($totalRows_rsMajor > 0) { // Show if recordset not empty ?>
    <table width="924" height="171" border="1">
      <tbody>
        <tr bgcolor="#B3B3B3">
          <td><strong>Background Information</strong></td>
        </tr>
        <tr>
          <td><table width="300" border="0">
            <tbody>
              <tr>
                <td>Firstname</td>
                <td><?php echo $row_rstViewRegistration['Firstname']; ?></td>
              </tr>
              <tr>
                <td>Lastname</td>
                <td><?php echo $row_rstViewRegistration['Lastname']; ?></td>
              </tr>
              <tr>
                <td>UID</td>
                <td><?php echo $row_rstViewRegistration['UID']; ?></td>
              </tr>
              <tr>
                <td>Email</td>
                <td><?php echo $row_rstViewRegistration['Email']; ?></td>
              </tr>
            </tbody>
          </table>
            <table width="584" border="1">
              <tbody>
                <tr>
                  <td width="111">Seniority</td>
                  <td width="457"><?php echo $row_rsApplication['Seniority']; ?></td>
                </tr>
                <tr>
                  <td>Gender</td>
                  <td><?php echo $row_rsApplication['Gender']; ?></td>
                </tr>
                <tr>
                  <td>Race</td>
                  <td><?php echo $row_rsApplication['Race']; ?></td>
                </tr>
                <tr>
                  <td>Overall GPA</td>
                  <td><?php printf("%.2f", $row_rsApplication['OverallGPA']); ?></td>
                </tr>
                <tr>
                  <td>Major GPA</td>
                  <td><?php printf("%.2f", $row_rsApplication['MajorGPA']); ?></td>
                </tr>
                <tr>
                  <td>Credits</td>
                  <td><?php echo $row_rsApplication['Credits']; ?></td>
                </tr>
                <tr>
                  <td>Graduation Date</td>
                  <td><?php echo $row_rsApplication['Graduation']; ?></td>
                </tr>
                <tr>
                  <td><input name="UserID" type="hidden" id="UserID" value="<?php echo $row_rstViewRegistration['UserID']; ?>">
                    Citizenship</td>
                  <td><?php echo $row_rsApplication['Citizenship']; ?></td>
                </tr>
              </tbody>
            </table></td>
        </tr>
        <tr>
          <td><input name="EditBackground" type="button" id="EditBackground" onClick="MM_goToURL('parent','EditBackground1.php');return document.MM_returnValue" value="Edit Background"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><strong>Citizenship</strong></td>
        </tr>
        <tr>
          <td><?php do { ?>
              <?php if ($totalRows_rsCitizenshipList > 0) { // Show if recordset not empty ?>
                <table width="600" border="1">
                  <tbody>
                    <tr>
                      <td width="348"><?php echo $row_rsCitizenshipList['Country']; ?>
                        <input name="hiddenField" type="hidden" id="hiddenField" value="<?php echo $row_rstViewRegistration['UserID']; ?>"></td>
                      <td width="236"><a href="DeleteCountry1.php?UID=<?php echo $row_rsCitizenshipList['UID'];?> && CountryID=<?php echo 	  $row_rsCitizenshipList['CountryID'];?>">Delete Country</a></td>
                    </tr>
                  </tbody>
                </table>
                <?php } // Show if recordset not empty ?>
              <?php } while ($row_rsCitizenshipList = mysql_fetch_assoc($rsCitizenshipList)); ?>
            <input name="AddCitizenship" type="button" id="AddCitizenship" onClick="MM_goToURL('parent','Citizenship1.php');return document.MM_returnValue" value="Add Citizenship"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><strong>Major(s) and Minor(s)
            <input name="Editbutton" type="button" id="Editbutton" onClick="MM_goToURL('parent','OtherMajor1.php');return document.MM_returnValue" value="Edit Other Major">
          </strong></td>
        </tr>
        <tr>
          <td><table width="872" border="1" align="left">
            <tbody>
              <?php do { ?>
                <tr>
                  <td width="615"><?php echo $row_rsMajor['Programs']; ?><strong>
                      <input name="UID" type="hidden" id="UID" value="<?php echo $row_rsMajor['UID']; ?>">
                      <input name="MID" type="hidden" id="MID" value="<?php echo $row_rsMajor['MID']; ?>">
                    </strong></td>
                  <td width="100"><?php echo $row_rsMajor['Type']; ?></td>
                  <td width="135"><a href="DeleteMajor1.php?UID=<?php echo $row_rsMajor['UID'];?> && MID=<?php echo $row_rsMajor['MID'];?>">Delete Major</a></td>
                </tr>
                <?php } while ($row_rsMajor = mysql_fetch_assoc($rsMajor)); ?>
            </tbody>
          </table>
            &nbsp;</td>
        </tr>
        <tr>
          <td><p>
            <input name="AddMajor" type="button" id="AddMajor" onClick="MM_goToURL('parent','Majors1.php');return document.MM_returnValue" value="Add Major">
          </p></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><strong>University Programs</strong></td>
        </tr>
        <tr>
          <td><table width="876" border="1">
            <tbody>
              <?php do { ?>
                <tr>
                  <td width="619"><?php echo $row_rsViewUNIVProgram['UPrograms']; ?></td>
                  <td width="241">Add Delete </td>
                </tr>
                <?php } while ($row_rsViewUNIVProgram = mysql_fetch_assoc($rsViewUNIVProgram)); ?>
            </tbody>
          </table></td>
        </tr>
        <tr>
          <td><input name="AddPrograms" type="button" id="AddPrograms" onClick="MM_goToURL('parent','UniversityPrograms.php');return document.MM_returnValue" value="Add Programs"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><strong>Concentrations</strong></td>
        </tr>
        <tr>
          <td><table width="876" height="57" border="1">
            <tbody>
              <tr>
                <td width="469"><strong>Concentration</strong></td>
                <td width="147"><strong>Rank</strong></td>
                <td width="238">&nbsp;</td>
              </tr>
              <?php do { ?>
                <tr>
                  <td><?php echo $row_rsConcentration['Concentration']; ?>
                    <input name="txtConID" type="hidden" id="txtConID" value="<?php echo $row_rsConcentration['ConID']; ?>"></td>
                  <td><?php echo $row_rsConcentration['Rank']; ?></td>
                  <td><a href="DeleteConcentration1.php?RankID=<?php echo $row_rsConcentration['RankID'];?>">Delete Concentration</a></td>
                </tr>
                <?php } while ($row_rsConcentration = mysql_fetch_assoc($rsConcentration)); ?>
            </tbody>
          </table></td>
        </tr>
        <tr>
          <td><input name="AddConcentration" type="button" id="AddConcentration" onClick="MM_goToURL('parent','Concentration1.php');return document.MM_returnValue" value="Add Concentration"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><strong>Internship Interest</strong></td>
        </tr>
        <tr>
          <td><table width="875" border="1">
            <tbody>
              <tr>
                <td width="244">General Internship Interest</td>
                <td width="615"><?php echo $row_rsApplication['InternshipInterest']; ?></td>
              </tr>
              <tr>
                <td>Past Internship Experience</td>
                <td><?php echo $row_rsApplication['PastInternLocation']; ?></td>
              </tr>
              <tr>
                <td>Interning in Fall 2016?</td>
                <td><?php echo $row_rsApplication['InternInFall']; ?></td>
              </tr>
              <tr>
                <td>If interning in Fall 2916, please specify location</td>
                <td><?php echo $row_rsApplication['FallInternLoc']; ?></td>
              </tr>
              <tr>
                <td>Other comments on your interest or passion </td>
                <td><?php echo $row_rsApplication['OtherComments']; ?></td>
              </tr>
            </tbody>
          </table>
            <input name="ButAddIntInterest" type="button" id="ButAddIntInterest" onClick="MM_goToURL('parent','InternshipInterest1.php');return document.MM_returnValue" value="Edit Internship Interest"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><strong>Supplementary Courses</strong></td>
        </tr>
        <tr>
          <td><table width="876" border="1">
            <tbody>
              <tr>
                <td width="531"><strong>Supplementary Courses</strong></td>
                <td width="230"><strong>Course Code
                  <input name="crsUID" type="hidden" id="crsUID" value="<?php echo $row_rsSupplementaryCourses['UID']; ?>">
                </strong></td>
                <td width="93">&nbsp;</td>
              </tr>
              <?php do { ?>
                <tr>
                  <td><?php echo $row_rsSupplementaryCourses['CourseTitle']; ?></td>
                  <td><?php echo $row_rsSupplementaryCourses['CourseID']; ?></td>
                  <td><a href="DeleteSupplementaryCrs1.php?UID=<?php echo $row_rsSupplementaryCourses['UID'];?> && CourseID=<?php echo $row_rsSupplementaryCourses['CourseID'];?>">Delete Course</a></td>
                </tr>
                <?php } while ($row_rsSupplementaryCourses = mysql_fetch_assoc($rsSupplementaryCourses)); ?>
            </tbody>
          </table></td>
        </tr>
        <tr>
          <td><input name="AddSupplementary" type="button" id="AddSupplementary" onClick="MM_goToURL('parent','Supplementary1.php');return document.MM_returnValue" value="Add Supplementary Course"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><strong>Upload Documents</strong></td>
        </tr>
        <tr>
          <td><table width="880" height="53" border="1">
            <tbody>
              <tr>
                <td width="254"><strong>Document Type</strong></td>
                <td width="510"><strong>Document</strong></td>
                <td width="94">&nbsp;</td>
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
            <input name="butUploadDocs" type="button" id="butUploadDocs" onClick="MM_goToURL('parent','Documents1.php');return document.MM_returnValue" value="Upload Documents"></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><strong>Submit Application</strong></td>
        </tr>
        <tr>
          <td><input name="submit" type="hidden" id="submit" value="2">
            <input type="submit" name="submitbutton" id="submitbutton" value="Submit Application">
            (<em>Please note: Once application is submitted, you will not be able to access this information again</em>)</td>
        </tr>
      </tbody>
    </table>
    <?php } // Show if recordset not empty ?>
<input type="hidden" name="MM_update" value="frmControlPanel">
  <input type="hidden" name="MM_update" value="frmControlPanel">
</form>
<script type="text/javascript">
//
</script>
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
?>
