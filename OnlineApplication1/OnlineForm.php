<?php 
  
  require_once('../Connections/FGSP.php'); 
  
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

  $MM_restrictGoTo = "Login1.php";
  if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
    $MM_qsChar = "?";
    $MM_referrer = $_SERVER['PHP_SELF'];
    if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
    if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
    $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
    $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
    echo "You don't have access to this page.";
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
 if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "frmForm")) {
    $updateSQL = sprintf("UPDATE tblsqapplication SET UserID=%s, InternshipInterest=%s, PastInternLocation=%s, InternInFall=%s, FallInternLoc=%s, OtherComments=%s WHERE `UID`=%s",
                         GetSQLValueString($_POST['UserID'], "int"),
                         GetSQLValueString($_POST['txtEditGenInternship'], "text"),
                         GetSQLValueString($_POST['txtEditPastInternship'], "text"),
                         GetSQLValueString($_POST['BoxEditInterningInFall'], "text"),
                         GetSQLValueString($_POST['txtEditFallIntLocation'], "text"),
                         GetSQLValueString($_POST['txtEditOtherComment'], "text"),
                         GetSQLValueString($_POST['UID'], "int"));

    mysql_select_db($database_FGSP, $FGSP);
    $Result1 = mysql_query($updateSQL, $FGSP) or die(mysql_error());

    $updateGoTo = "OnlineForm.php";
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

//Code for Major

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

//Code to add new major


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmMajor")) {
  $insertSQL = sprintf("INSERT INTO tblsqprogramdetails (`UID`, MID, Type) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['txtUID'], "int"),
                       GetSQLValueString($_POST['selMajors'], "int"),
                       GetSQLValueString($_POST['selCategory1'], "text"));

  mysql_select_db($database_FGSP, $FGSP);
  $Result1 = mysql_query($insertSQL, $FGSP) or die(mysql_error());

  $insertGoTo = "OnlineForm.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_FGSP, $FGSP);
$query_rsProgramList = "SELECT * FROM tblsqprogramlist ORDER BY Programs ASC";
$rsProgramList = mysql_query($query_rsProgramList, $FGSP) or die(mysql_error());
$row_rsProgramList = mysql_fetch_assoc($rsProgramList);
$totalRows_rsProgramList = mysql_num_rows($rsProgramList);

$colname_rstViewRegistration = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rstViewRegistration = $_SESSION['MM_Username'];
}

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Online Form</title>
<script type="text/javascript">
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
</script>
<style type="text/css">
body {
	background-color: #B1CDD9;
	font-size: 18;
}
body,td,th {
	font-size: 18px;
}
</style>
</head>

<body>
<form action= "<?php echo $editFormAction;?>" id="frmForm" name="frmForm" method="post">
  <div>
  <h3>Background: 
    <input name="EditBackground" type="button" class="btn btn-primary" id="EditBackground" onClick="MM_goToURL('parent','EditBackground1.php');return document.MM_returnValue" value="Edit Background">
  </h3>
            <table border="1" align="left" class="table">
              <tbody>
                <tr style="font-family: Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif">
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
                <tr style="font-family: Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif">
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
                <tr style="font-family: Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif">
                  <td><strong>Ethnicity</strong></td>
                  <td>
                    <?php 
                      echo $row_rsApplication['Race']; 
                    ?>
                  </td>
                </tr>
                <tr style="font-family: Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif">
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
                <tr style="font-family: Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif">
                  <td><strong>Major GPA</strong></td>
                  <td>
                    <?php 
                      printf("%.2f", $row_rsApplication['MajorGPA']); 
                    ?>
                  </td>
                </tr>
                <tr style="font-family: Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif">
                  <td><strong>Credits</strong></td>
                  <td>
                    <?php 
                      echo $row_rsApplication['Credits']; 
                    ?>
                  </td>
                  <td><strong>Interning in Fall 2017?</strong></td>
                  <td>
                    <?php 
                      echo $row_rsApplication['InternInFall']; 
                    ?>
                  </td>
                </tr>
                <tr style="font-family: Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', serif">
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
  </div>
  <p>
    <input type="hidden" name="MM_update" value="frmForm">
  </p>
</form>

<form action="<?php echo $editFormAction; ?>" method="POST" name="frmMajor" id="frmMajor">
  <h4 align="center" style="text-align: left">Please add all your Majors and Minors
    <input type="submit" name="SubmitMajors" id="SubmitMajors" value="Submit">
  </h4>
  <div>
    <table width="290" border="1" align="left">
    <tbody>
      <tr>
        <td width="45">Major</td>
        <td width="83">Type</td>
        <td width="140">UID</td>
      </tr>
      <tr>
        <td><select name="selMajors" required id="selMajors">
          <?php
do {  
?>
          <option value="<?php echo $row_rsProgramList['MID']?>"><?php echo $row_rsProgramList['Programs']?></option>
          <?php
} while ($row_rsProgramList = mysql_fetch_assoc($rsProgramList));
  $rows = mysql_num_rows($rsProgramList);
  if($rows > 0) {
      mysql_data_seek($rsProgramList, 0);
	  $row_rsProgramList = mysql_fetch_assoc($rsProgramList);
  }
?>
        </select></td>
        <td><select name="selCategory1" required id="selCategory1">
          <option>Major</option>
          <option>Minor</option>
        </select></td>
        <td><input name="txtUID" type="text" id="txtUID" value="<?php echo $row_rstViewRegistration['UID']; ?>"></td>
      </tr>
    </tbody>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  </div>
  <table width="643" border="1" align="left">
    <tbody>
      <tr>
        <td width="386"><strong>Program
          <input name="UID" type="hidden" id="UID" value="<?php echo $row_rsMajor['UID']; ?>">
          <input name="MID" type="hidden" id="MID" value="<?php echo $row_rsMajor['MID']; ?>">
        </strong></td>
        <td width="129"><strong>Type</strong></td>
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
  
  <p>&nbsp;</p>
  <p>
    <input type="hidden" name="MM_insert" value="frmMajor">
  </p>
  <p>&nbsp;</p>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>