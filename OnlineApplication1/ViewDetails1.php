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
$maxRows_rsStudentDetails = 6;
$pageNum_rsStudentDetails = 0;
if (isset($_GET['pageNum_rsStudentDetails'])) {
  $pageNum_rsStudentDetails = $_GET['pageNum_rsStudentDetails'];
}
$startRow_rsStudentDetails = $pageNum_rsStudentDetails * $maxRows_rsStudentDetails;

$stdUID_rsStudentDetails = "-1";
if (isset($_GET['UID'])) {
  $stdUID_rsStudentDetails = $_GET['UID'];
}
mysql_select_db($database_FGSP, $FGSP);
$query_rsStudentDetails = sprintf("SELECT tblusers.Username, tblusers.Firstname, tblusers.Lastname, tblusers.Email, tblsqapplication.`UID`, tblsqapplication.Seniority, tblsqapplication.Gender, tblsqapplication.Race, tblsqapplication.OverallGPA, tblsqapplication.MajorGPA, tblsqapplication.Credits, tblsqapplication.Graduation, tblsqapplication.PgmYear, tblsqapplication.Citizenship, tblsqapplication.InternshipInterest, tblsqapplication.PastInternLocation, tblsqapplication.InternInFall, tblsqapplication.FallInternLoc, tblsqapplication.OtherComments FROM tblusers INNER JOIN tblsqapplication ON tblusers.UserID=tblsqapplication.UserID WHERE tblsqapplication.UID = %s", GetSQLValueString($stdUID_rsStudentDetails, "int"));
$query_limit_rsStudentDetails = sprintf("%s LIMIT %d, %d", $query_rsStudentDetails, $startRow_rsStudentDetails, $maxRows_rsStudentDetails);
$rsStudentDetails = mysql_query($query_limit_rsStudentDetails, $FGSP) or die(mysql_error());
$row_rsStudentDetails = mysql_fetch_assoc($rsStudentDetails);

if (isset($_GET['totalRows_rsStudentDetails'])) {
  $totalRows_rsStudentDetails = $_GET['totalRows_rsStudentDetails'];
} else {
  $all_rsStudentDetails = mysql_query($query_rsStudentDetails);
  $totalRows_rsStudentDetails = mysql_num_rows($all_rsStudentDetails);
}
$totalPages_rsStudentDetails = ceil($totalRows_rsStudentDetails/$maxRows_rsStudentDetails)-1;

$maxRows_rsStdCitizenship = 5;
$pageNum_rsStdCitizenship = 0;
if (isset($_GET['pageNum_rsStdCitizenship'])) {
  $pageNum_rsStdCitizenship = $_GET['pageNum_rsStdCitizenship'];
}
$startRow_rsStdCitizenship = $pageNum_rsStdCitizenship * $maxRows_rsStdCitizenship;

$colname_rsStdCitizenship = "-1";
if (isset($_GET['UID'])) {
  $colname_rsStdCitizenship = $_GET['UID'];
}
mysql_select_db($database_FGSP, $FGSP);
$query_rsStdCitizenship = sprintf("SELECT tblsqcitizenshipdetails.UID, tblsqcitizenshipdetails.CountryID, tblsqcountry.Country, tblUsers.Username FROM tblsqcountry RIGHT JOIN tblsqcitizenshipdetails ON tblsqcountry.CountryID = tblsqcitizenshipdetails.CountryID LEFT JOIN tblUsers ON tblsqcitizenshipdetails.UID = tblUsers.UID WHERE tblUsers.UID = %s ORDER BY tblsqcountry.Country", GetSQLValueString($colname_rsStdCitizenship, "int"));
$query_limit_rsStdCitizenship = sprintf("%s LIMIT %d, %d", $query_rsStdCitizenship, $startRow_rsStdCitizenship, $maxRows_rsStdCitizenship);
$rsStdCitizenship = mysql_query($query_limit_rsStdCitizenship, $FGSP) or die(mysql_error());
$row_rsStdCitizenship = mysql_fetch_assoc($rsStdCitizenship);

if (isset($_GET['totalRows_rsStdCitizenship'])) {
  $totalRows_rsStdCitizenship = $_GET['totalRows_rsStdCitizenship'];
} else {
  $all_rsStdCitizenship = mysql_query($query_rsStdCitizenship);
  $totalRows_rsStdCitizenship = mysql_num_rows($all_rsStdCitizenship);
}
$totalPages_rsStdCitizenship = ceil($totalRows_rsStdCitizenship/$maxRows_rsStdCitizenship)-1;
?>



<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Student Details</title>
</head>

<body>
 <p><strong>Students Details </strong></p>
 <fieldset>
 <table width="665" height="78" border="0">
   <tbody>
     <tr>
       <td width="518"><table width="505" border="1">
         <tbody>
           <tr>
             <td width="201">Firstname</td>
             <td width="294"><?php echo $row_rsStudentDetails['Firstname']; ?></td>
            </tr>
           <tr>
             <td>Lastname</td>
             <td><?php echo $row_rsStudentDetails['Lastname']; ?></td>
            </tr>
           <tr>
             <td>UID</td>
             <td><?php echo $row_rsStudentDetails['UID']; ?></td>
           </tr>
           <tr>
             <td>Gender</td>
             <td><?php echo $row_rsStudentDetails['Gender']; ?></td>
           </tr>
           <tr>
             <td>Race</td>
             <td><?php echo $row_rsStudentDetails['Race']; ?></td>
           </tr>
           <tr>
             <td>Citizenship</td>
             <td><?php echo $row_rsStudentDetails['Citizenship']; ?></td>
           </tr>
           <tr>
             <td>Email</td>
             <td><?php echo $row_rsStudentDetails['Email']; ?></td>
            </tr>
           <tr>
             <td>Major GPA</td>
             <td><?php echo $row_rsStudentDetails['MajorGPA']; ?></td>
           </tr>
           <tr>
             <td>Overall GPA</td>
             <td><?php echo $row_rsStudentDetails['OverallGPA']; ?></td>
           </tr>
           <tr>
             <td>Credits completed at the end of Spring Semester</td>
             <td><?php echo $row_rsStudentDetails['Credits']; ?></td>
           </tr>
           <tr>
             <td>Expected Graduation Date</td>
             <td><?php echo $row_rsStudentDetails['Graduation']; ?></td>
           </tr>
           <tr>
             <td>Seniority</td>
             <td><?php echo $row_rsStudentDetails['Seniority']; ?></td>
            </tr>
         </tbody>
       </table></td>
       <td width="518">&nbsp;</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
       <td width="137">&nbsp;</td>
     </tr>
     <tr>
       <td><strong>Citizenship Countries</strong></td>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td><?php if ($totalRows_rsStdCitizenship > 0) { // Show if recordset not empty ?>
  <?php do { ?>
    <table width="300" border="1">
      <tbody>
        <tr>
          <td>
            <?php echo $row_rsStdCitizenship['Country']; ?>
            </td>
          </tr>
        </tbody>
    </table>
    <?php } while ($row_rsStdCitizenship = mysql_fetch_assoc($rsStdCitizenship)); ?>
           <?php } // Show if recordset not empty ?>       </tr>
     <tr>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
     </tr>
   </tbody>
 </table>
 </fieldset>
 <p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsStudentDetails);

mysql_free_result($rsStdCitizenship);
?>
