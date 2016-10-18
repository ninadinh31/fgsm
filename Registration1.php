<?php

require_once('Connections/FGSP.php'); ?>
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

// *** Redirect if username or UID or email exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="Login1.php";
  $loginUsername = $_POST['txtUsername'];
  $loginUID = $_POST['txtUID'];
  $loginEmail = $_POST['txtEmail'];
  $LoginRS__query = sprintf("SELECT Username FROM tblusers WHERE Username=%s OR UID=%s OR Email=%s", 
  							GetSQLValueString($loginUsername, "text"),
							GetSQLValueString($loginUID, "text"),
							GetSQLValueString($loginEmail, "text"));
  mysql_select_db($database_FGSP, $FGSP);
  $LoginRS=mysql_query($LoginRS__query, $FGSP) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username/uid was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
	    ?>
	<script>
  		alert('Username, UID, or Email Address exists. Try again or contact globalsemesterdc@umd.edu for more information');
        window.location.href='Registration1.php';
    </script>
    <?php 
	// header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}
$editFormAction = htmlspecialchars($_SERVER['PHP_SELF']);
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$errormsg = "Sorry, the following errors were found, please edit before submitting your form:";

if (!isset($_POST["Register"])){
	$firstname = $lastname = $username = $password = $cpassword = $uid = $email = ""; 
	$fail = "";
	$errormsg = "";
}

//if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frmRegister")) 

if (isset($_POST["Register"])){
  
  if (isset($_POST['txtFirstname']))
  	$firstname = $_POST['txtFirstname'];
  if (isset($_POST['txtLastname']))
  	$lastname = $_POST['txtLastname'];
  if (isset($_POST['txtUsername']))
  	$username = $_POST['txtUsername'];
  if (isset($_POST['txtPassword']))
  	$password = $_POST['txtPassword'];
  if (isset($_POST['txtConfirmPassword'])) {
  	if ($password == $_POST['txtConfirmPassword']) {
  		$cpassword = $_POST['txtConfirmPassword'];
	} 
	else {
	?>
    <script>
  		alert("Password did not match. Try again or contact globalsemesterdc@umd.edu for more information");
        window.location.href='Registration1.php';
    </script>
<?php 
  }
  }
  if (isset($_POST['txtUID']))
  	$uid = $_POST['txtUID'];
  if (isset($_POST['txtEmail']))
  	$email = $_POST['txtEmail'];
	
  $fail  = validate_firstname($firstname);
  $fail .= validate_lastname($lastname);
  $fail .= validate_username($username);
  $fail .= validate_password($password);
  $fail .= validate_cpassword($cpassword);
  $fail .= validate_uid($uid);
  $fail .= validate_email($email);
  
  if (!$fail == "") {
	echo $errormsg; 
  }
  
  if ($fail == "")
  {
	echo "</head><body>Registration data successfully validated:
	$firstname, $lastname, $username, $password, $cpassword, $uid, $email.</body></html>";
  
	 $password = sha1(GetSQLValueString($_POST['txtPassword'], "text"));
     $cpassword = sha1(GetSQLValueString($_POST['txtConfirmPassword'], "text"));
	
   //$insertPass = sha1(GetSQLValueString($_POST['txtPassword'], "text"));
  //$insertConFPass = sha1(GetSQLValueString($_POST['txtConfirmPassword'], "text"));
	
  $insertSQL = sprintf("INSERT INTO tblusers (Firstname, Lastname, Email, Username, Password, ConfirmPassword, `UID`) VALUES (%s, %s, %s, %s, '{$password}', '{$cpassword}', %s)",
                       GetSQLValueString($_POST['txtFirstname'], "text"),
                       GetSQLValueString($_POST['txtLastname'], "text"),
                       GetSQLValueString($_POST['txtEmail'], "text"),
                       GetSQLValueString($_POST['txtUsername'], "text"),
                       GetSQLValueString($_POST['txtUID'], "int"));

  mysql_select_db($database_FGSP, $FGSP);
  $Result1 = mysql_query($insertSQL, $FGSP) or die(mysql_error());
  
  //$insertGoTo = "Login1.php";
  //if (isset($_SERVER['QUERY_STRING'])) {
  //  $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
 //   $insertGoTo .= $_SERVER['QUERY_STRING'];
 // }
 // header(sprintf("Location: %s", $insertGoTo));
}
}
mysql_select_db($database_FGSP, $FGSP);
$query_rstRegistration = "SELECT * FROM tblusers";
$rstRegistration = mysql_query($query_rstRegistration, $FGSP) or die(mysql_error());
$row_rstRegistration = mysql_fetch_assoc($rstRegistration);
$totalRows_rstRegistration = mysql_num_rows($rstRegistration);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style>
.registration {
	border: 1px solid #999999;
	font: normal 14px helvetica; color:#444444;
}
</style>
<script>

function validate(form)
{
	password1 = form.txtPassword.value;
	password2 = form.txtConfirmPassword.value;
	
	fail = validateFirstname(form.txtFirstname.value)
	fail += validateLastname(form.txtLastname.value)
	fail += validateUsername(form.txtUsername.value)
	fail += validatePassword(password1, password2)
	//fail += validateConfirmPassword(form.txtConfirmPassword.value)
	fail += validateUID(form.txtUID.value)
	fail += validateEmail(form.txtEmail.value)
	
	if (fail == "") return true
	else {alert(fail); return false }
}

function validateFirstname(field)
{
	return (field == "") ? "No Firstname was entered.\n" : ""
}
function validateLastname(field)
{
	return (field == "") ? "No Lastname was entered.\n" : ""
}
function validateUsername(field)
{
	return (field == "") ? "No Username was entered.\n" : ""
}
function validatePassword()
{
	if (password1 == "" || password2 == "") return "Please add Password and ensure Confirm Password was entered.\n"
	else if (password1 !== password2) return "Password did not match.\n"
	else if (password1.length && password2.length < 6)
	 return "Password must be at least 6 characters.\n"
	else if (!/[a-z]/.test(password1, password2) || !/[A-Z]/.test(password1, password2) || !/[0-9]/.test(password1, password2))
	 return "Password require one each of a-z, A-Z, and 0-9 characters.\n"
return ""
}
//function validateConfirmPassword(field)
//{
//	if (field == "") return "Confirm Password was not entered.\n"
//	else if (field.length < 6)
//	 return "Password must be at least 6 characters.\n"
//	else if (!/[a-z]/.test(field) || !/[A-Z]/.test(field) || !/[0-9]/.test(field))
//	 return "Password require one each of a-z, A-Z, and 0-9 characters.\n"
//return ""
//}
function validateUID(field)
{
	if (field == "") return "No UID was entered.\n"
return ""
}
function validateEmail(field)
{
	if (field == "") return "No email was entered.\n"
	else if (!((field.indexOf(".") > 0) && (field.indexOf("@") > 0)) || /[^a-zA-Z0-9.@_-]/.test(field))
	 return "The email address is invalid.\n"
	return ""
}
</script>
<title>Registration</title>
</head>
<form action="<?php echo $editFormAction; ?>" onSubmit="return validate(this)" id="frmRegister" name="frmRegister" method="POST">
<body>
  <h2>Create an Account</h2>
  <h3>Please provide the requested information to create your account</h3>
  <table width="600" border="0" cellpadding="2" cellspacing="5" bgcolor="#eeeeee">
  <th colspan="2" align="center">Registration</th>
  <tr><td colspan="2"><font color="#BE0F30"><?php if (!$fail == "") echo $errormsg; ?><br>
  <p><font color="#BE0F30" size=2><i><?php echo $fail; ?></i></font></p>
  </td></tr>
    <tbody>
      <tr>
        <td width="193"><h4>* UID</h4></td>
        <td width="391"><input name="txtUID" type="text" maxlength="20" id="txtUID" value="<?php echo $uid; ?>" ></td>
      </tr>
      <tr>
        <td width="193"><h4>* First Name</h4></td>
        <td><input name="txtFirstname" type="text" maxlength="32" id="txtFirstname" value="<?php echo $firstname; ?>"></td>
      </tr>
      <tr>
        <td><h4>* Last Name</h4></td>
        <td><input name="txtLastname" type="text" maxlength="32" id="txtLastname" value="<?php echo $lastname; ?>"></td>
      </tr>
      <tr>
        <td><h4>* Username</h4></td>
        <td><input name="txtUsername" type="text" maxlength="16" id="txtUsername" value="<?php echo $username; ?>"></td>
      </tr>
      <tr>
        <td><h4>*
            <label for="txtPassword">Password</label>
        </h4>          <label for="txtPassword"></label></td>
        <td><input name="txtPassword" type="password" maxlength="12" id="txtPassword" value="<?php echo $password; ?>"></td>
      </tr>
      <tr>
        <td><h4>* Confirm Password</h4></td>
        <td><input name="txtConfirmPassword" type="password" maxlength="12" id="txtConfirmPassword" value="<?php echo $cpassword; ?>"></td>
      </tr>
      <tr>
        <td><h4>* Email</h4></td>
        <td><input name="txtEmail" type="email" maxlength="64" id="txtEmail" value="<?php echo $email; ?>"></td>
      </tr>
      <tr>
        <td><h5>* Required Fields</h5></td>
      </tr>
      <tr>
        <td><input type="submit" align="center" colspan="2" name="Register" id="Register" value="Submit"></td>
      </tr>
      <tr>
        <td><input type="hidden" name="RegisterDate" id="RegisterDate">
        <input type="hidden" name="Userlevel" id="Userlevel">
        <input type="hidden" name="UserID" id="UserID"></td>
      </tr>
    </tbody>
  </table>
  <input type="hidden" name="MM_insert" value="frmRegister">
</form>
</body>
</html>

<?php
function validate_firstname($field)
{
	return ($field == "") ? "No firstname was entered<br>": "";
}
function validate_lastname($field)
{
	return ($field == "") ? "No lastname was entered<br>": "";
}
function validate_username($field)
{
	if ($field == "") return "No Username was entered<br>";
	else if (strlen($field) < 5)
		return "Username must be at least 5 characters<br>";
	else if (preg_match("/[^a-zA-Z0-9_-]/", $field))
	 	return "Only letters, numbers, - and _ allowed in Usernames<br>";
	return "";
}
function validate_password($field)
{
	if ($field == "") return "No Password was entered<br>";
	else if (strlen($field) < 6)
		return "Password must be at least 6 characters<br>";
	else if (!preg_match("/[a-z]/", $field) || !preg_match("/[A-Z]/", $field) || !preg_match("/[0-9]/", $field))
		return "Password require 1 each of a-z, A-Z and 0-9<br>";
	return "";
}
function validate_cpassword($field)
{
	if ($field == "") return "No Confirm Password was entered<br>";
	else if (strlen($field) < 6)
		return "Confirm Password must be at least 6 characters<br>";
	else if (!preg_match("/[a-z]/", $field) || !preg_match("/[A-Z]/", $field) || !preg_match("/[0-9]/", $field))
		return "Confirm Password require 1 each of a-z, A-Z and 0-9<br>";
	return "";
}
function validate_uid($field)
{
	return ($field == "") ? "No UID was entered<br>": "";
}
function validate_email($field)
{
	if ($field == "") return "No Email was entered<br>";
	 else if (!((strpos($field, ".") > 0) &&
	 			 (strpos($field, "@") > 0))	|| preg_match("/[^a-zA-Z0-9.@_-]/", $field))
		return "The Email address is invalid<br>";
	return "";
}
mysql_free_result($rstRegistration);
?>
