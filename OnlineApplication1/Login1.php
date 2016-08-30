<?php require_once('../Connections/FGSP.php'); ?>
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

// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['Username'])) {
  $loginUsername=$_POST['Username'];
  //$password1=$_POST['Password'];
  $password = sha1(GetSQLValueString($_POST['Password'], "text"));
  $MM_fldUserAuthorization = "UserLevel";
  $MM_redirectLoginSuccess = "ApplicationMenu.php";
  $MM_redirectLoginFailed = "Login1.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_FGSP, $FGSP);
  //$loginPass = sha1($password1);
	
  $LoginRS__query=sprintf("SELECT Username, Password, UserLevel FROM tblusers WHERE Username=%s AND Password='{$password}'",
  GetSQLValueString($loginUsername, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $FGSP) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'UserLevel');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
	?> <script>
  		alert('Invalid login credentials. Try again or contact globalsemesterdc@umd.edu.');
        window.location.href='Login1.php';
    </script> <?php
  }
}
?>
<!doctype html>
<html>
<style style="text/css">
	body {
  		background-image: url("images/bigheader.jpg");
  		background-size: cover;
  		background-repeat: no-repeat;
	}
	table {
		margin-left:auto;
		margin-right:auto;
		margin-top: 20%;
	}
</style>
<head>
<meta charset="utf-8">
<title>Online Application Login</title>
</head>

<body>
<form ACTION="<?php echo $loginFormAction; ?>" id="frmLogin" name="frmLogin" method="POST">
  <table width="677" border="1" cellpadding="1">
    <tbody>
      <tr>
        <td width="236" bgcolor="#A3C9C3"><h2 style="text-align: left">Online Application Log In</h2>          <h3>
            <label for="Username">Username:</label>
            <input name="Username" type="text" required id="Username">
        </h3>          <h3>
            <label for="Password">Password:</label>
            <input name="Password" type="password" required id="Password">
        </h3>        <input name="Login" type="submit" id="Login" value="Log In"></td>
        <td width="236" bgcolor='#A3C9C3'><h3>Don't have an account?</h3>          <a href="Registration1.php">Create a new account now</a></td>
      </tr>
      <tr>
        <td colspan="2" bgcolor="#F0F5F7" style="text-align: center; color: rgba(15,15,15,1);">Forgot your <a href="forgotusername.php">Username</a> or <a href="resetpassword.php">Password</a>?</td>
      </tr>
    </tbody>
  </table>
</form>
</body>
</html>
