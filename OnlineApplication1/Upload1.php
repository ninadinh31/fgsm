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

$colname_rstViewRegistration = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rstViewRegistration = $_SESSION['MM_Username'];
}
mysql_select_db($database_FGSP, $FGSP);
$query_rstViewRegistration = sprintf("SELECT * FROM tblusers WHERE Username = %s", GetSQLValueString($colname_rstViewRegistration, "text"));
$rstViewRegistration = mysql_query($query_rstViewRegistration, $FGSP) or die(mysql_error());
$row_rstViewRegistration = mysql_fetch_assoc($rstViewRegistration);
$totalRows_rstViewRegistration = mysql_num_rows($rstViewRegistration);

mysql_select_db($database_FGSP, $FGSP)or die(mysql_error());
$folder='uploads/';
$targetfile = $folder . basename($_FILES['file']['name']);
$documentType = pathinfo($targetfile, PATHINFO_EXTENSION);
$uploadok = 1;

$UID = $row_rstViewRegistration['UID'];
$PgmYear = 2017; //$row_rstViewRegistration['PgmYear']; 
$selDocCategory = $_POST['selDocCategory'];
$myname = $row_rstViewRegistration['Firstname'] . ' ' . $row_rstViewRegistration['Lastname'];

if(isset($_POST['btn-upload']))
{    
//check is a file was uploaded

if(!is_uploaded_file($_FILES['file']['tmp_name'])){
echo "You did not select a file.<script type='text/javascript'>alert('Please ensure a file is selected');window.location.href='Documents1.php'</script>";
$uploadOk = 0;
}
else {
$file_loc = $_FILES['file']['tmp_name'];

$file = rand(1000,100000)."-".$_FILES['file']['name']."-".$myname;
 //Check if file is larger than 500kb
if ($_FILES['file']['size'] > 500000) {
    echo "Sorry, your file is too large.<script type='text/javascript'>alert('Please ensure file size is smaller than 500kb');window.location.href='Documents1.php'</script>";
    $uploadOk = 0;
}
else {
 $file_size = $_FILES['file']['size'];
 
 //Check for file type
 if($documentType != "docx" && $documentType != "pdf")
 {
	 echo "Sorry, only docx. and pdf. filetype are allowed";
	 $uploadok = 0;
 }
 if ($uploadok == 0) {
	 echo "<script type='text/javascript'>alert('Your files were not uploaded!');window.location.href='Documents1.php'</script>";
 }
 else {
	 
 $file_type = $_FILES['file']['type'];
 //$folder='uploads/';
 
 // to open the content and assign to $content variable
 $fp = fopen($file_loc, 'r');
 $content = fread($fp, filesize($file_loc));
 $content = addslashes($content);
 fclose($fp);
 
 // new file size in KB
 $new_size = $file_size/1024;  
 // new file size in KB
 
 // make file name in lower case
 $new_file_name = strtolower($file);
 // make file name in lower case
 
 $final_file=str_replace(' ','-',$new_file_name);
 
 //$txtUID = $_POST['txtUID'];
// $txtPgmYear = $_POST['txtPgmYear'];
 
 
 //GetSQLValueString($colname_rstViewRegistration, "text"));
 //GetSQLValueString($_POST['txtOtherMarketing'], "text"));
 if(move_uploaded_file($file_loc,$folder.$final_file))
 {
  
// $sql="INSERT INTO tblsqdocuments(docName,docType,docSize,docContent,UID,PgmYear,docCategory) //VALUES('$final_file','$file_type','$new_size','$content','$UID','$PgmYear', '$selDocCategory')";
 //mysql_query($sql) or die('Error, query failed');
  
  $insertSQL = sprintf("INSERT INTO tblsqdocuments(docName,docType,docSize,docContent,UID,PgmYear,docCategory)
  VALUES (%s, %s, %d, %s, %d, %s, %s)",
  					   GetSQLValueString($final_file, "text"),
                       GetSQLValueString($file_type, "text"),
                       GetSQLValueString($new_size, "int"),
					   GetSQLValueString($content, "int"),
                       GetSQLValueString($UID, "int"),
                       GetSQLValueString($PgmYear, "date"),
					   GetSQLValueString($selDocCategory, "text"));
   
  mysql_select_db($database_FGSP, $FGSP);
  $sql = mysql_query($insertSQL, $FGSP) or die(mysql_error());
 //mysql_query($sql) or die('Error, query failed');
  ?>
  <script>
  alert('Your file was uploaded successfully');
        window.location.href='ControlPanel2.php?success';
        </script>
  <?php 
 }
 else
 {
  ?>
  <script>
  alert('There was an error while uploading file');
        window.location.href='Documents1.php?fail';
        </script>
  <?php
 }
}
}
}
}
mysql_free_result($rstViewRegistration);
?>