<?php require_once('../Connections/FGSP.php'); ?>
<?php
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
  $password=$_POST['Password'];
  $MM_fldUserAuthorization = "UserLevel";
  $MM_redirectLoginSuccess = "ControlPanel2.php";
  $MM_redirectLoginFailed = "Login1.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_FGSP, $FGSP);
  	
  $LoginRS__query=sprintf("SELECT Username, Password, UserLevel FROM tblusers WHERE Username=%s AND Password=%s",
  GetSQLValueString($loginUsername, 'text'), GetSQLValueString($password, 'text')); 
   
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
    header("Location: ". $MM_redirectLoginFailed );
    //echo "You are not logged in.<br>";
  }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
</head>

<body>
<form ACTION="<?php echo $loginFormAction; ?>" id="frmLogin" name="frmLogin" method="POST">
  <table width="677" border="1" align="center" cellpadding="3">
    <tbody>
      <tr>
        <td width="236"><h2 style="text-align: left">Log In</h2></td>
        <td width="236" rowspan="4"><h3>Don't have an account?</h3>          <a href="Registration1.php">Create a new account now</a></td>
      </tr>
      <tr>
        <td><h3>
          <label for="Username">Username:</label>
          <input name="Username" type="text" required id="Username">
        </h3></td>
      </tr>
      <tr>
        <td><h3>
          <label for="Password">Password:</label>
          <input name="Password" type="password" required id="Password">
        </h3></td>
      </tr>
      <tr style="text-align: center">
        <td><input name="Login" type="submit" id="Login" value="Log In"></td>
      </tr>
      <tr>
        <td colspan="2">Forgot your Username or Password?</td>
      </tr>
    </tbody>
  </table>
  <p>&nbsp;</p>
</form>
</body>
</html>
