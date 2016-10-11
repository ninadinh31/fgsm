<?php 

require_once('/Connections/FGSP.php'); 
require_once('/includes/header.php');

// // *** Validate request to login to this site.
// if (!isset($_SESSION)) {
//   session_start();
// }

// $loginFormAction = $_SERVER['PHP_SELF'];
// if (isset($_GET['accesscheck'])) {
//   $_SESSION['PrevUrl'] = $_GET['accesscheck'];
// }

// if (isset($_POST['Username'])) {
//   $loginUsername=$_POST['Username'];
//   $password=$_POST['Password'];
//   $MM_fldUserAuthorization = "UserLevel";
//   $MM_redirectLoginSuccess = "ControlPanel2.php";
//   $MM_redirectLoginFailed = "Login1.php";
//   $MM_redirecttoReferrer = false;
//   mysql_select_db($database_FGSP, $FGSP);
  	
//   $LoginRS__query=sprintf("SELECT Username, Password, UserLevel FROM tblusers WHERE Username=%s AND Password=%s",
//   GetSQLValueString($loginUsername, 'text'), GetSQLValueString($password, 'text')); 
   
//   $LoginRS = mysql_query($LoginRS__query, $FGSP) or die(mysql_error());
//   $loginFoundUser = mysql_num_rows($LoginRS);
//   if ($loginFoundUser) {
    
//     $loginStrGroup  = mysql_result($LoginRS,0,'UserLevel');
    
// 	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
//     //declare two session variables and assign them
//     $_SESSION['MM_Username'] = $loginUsername;
//     $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

//     if (isset($_SESSION['PrevUrl']) && false) {
//       $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
//     }
//     header("Location: " . $MM_redirectLoginSuccess );
//   }
//   else {
//     header("Location: ". $MM_redirectLoginFailed );
//     //echo "You are not logged in.<br>";
//   }
// }

?>

<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">Log</div>
            <div class="panel-body">
                <form ACTION="<?php echo $loginFormAction; ?>" id="frmLogin" name="frmLogin" method="POST">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>
                                    <h2>Log In</h2>
                                </td>
                                <td>
                                    <h3>Don't have an account?</h3>          
                                    <a href="Registration1.php">Create a new account now</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h3>
                                        <label for="Username">Username:</label>
                                        <input name="Username" type="text" required id="Username">
                                    </h3>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h3>
                                        <label for="Password">Password:</label>
                                        <input name="Password" type="password" required id="Password">
                                    </h3>
                                </td>
                            </tr>
                            <tr>
                                <td><input name="Login" type="submit" id="Login" value="Log In"></td>
                            </tr>
                            <tr>
                                <td>Forgot your Username or Password?</td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>


</body>
</html>
