<?php 

require_once('Connections/NewLogin.php'); 

// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['loginUsername'])) {
    $loginUsername=$_POST['loginUsername'];
    ?>
    
    <?php
    $password = sha1(GetSQLValueString($_POST['loginPassword'], "text"));
    //$password = GetSQLValueString(sha1($_POST['Password']), "text);
    
    ?>
    <?php
    $MM_fldUserAuthorization = "UserLevel";
    $MM_redirectLoginSuccess = "events.php";
    $MM_redirectLoginFailed = "Login2.php";
    $MM_redirecttoReferrer = false;
    mysql_select_db($database_localhost, $localhost);
  	
    $LoginRS__query=sprintf("SELECT Username, Password, UserLevel FROM tblUsers WHERE Username=%s AND Password='{$password}'",
    GetSQLValueString($loginUsername, 'text')); 
   
    $LoginRS = mysql_query($LoginRS__query, $localhost) or die(mysql_error());
    $loginFoundUser = mysql_num_rows($LoginRS);
    if ($loginFoundUser) {
    
        $loginStrGroup  = mysql_result($LoginRS,0,'UserLevel');
    
	    if (PHP_VERSION >= 5.1) {
            session_regenerate_id(true);
        } else {
            session_regenerate_id();
        }

        //declare two session variables and assign them
        $_SESSION['MM_Username'] = $loginUsername;
        $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

        if (isset($_SESSION['PrevUrl']) && false) {
            $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
        }
        
        header("Location: " . $MM_redirectLoginSuccess );
    } else {
        header("Location: ". $MM_redirectLoginFailed );
        echo "You are not logged in.<br>";
    }
}

    require_once('includes/header.php');
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Log In</div>
            <div class="panel-body">

                <form action="<?php echo $loginFormAction; ?>" method="post">
                    <div class="form-group">
                        <label for="loginUsername">Username</label>
                        <input type="text" class="form-control" id="loginUsername" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <label for="loginPassword">Password</label>
                        <input type="password" class="form-control" id="loginPassword" placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-default">Log In</button><br />
                    <a href="Registration1.php">New User</a><br />
                    <a href="">Forgot Username</a><br />
                    <a href="">Forgot Password</a>
                </form>


<!-- 
                <form ACTION="<?php $loginFormAction; ?>" id="frmLogin" name="frmLogin" method="POST">
                    <table class="table borderless">
                        <tbody>
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
                                <td>
                                    <a href="Registration1.php">New User</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Forgot your Username or Password?</td>
                            </tr>
                            <tr>
                                <td><input name="Login" type="submit" id="Login" value="Log In"></td>
                            </tr>
                        </tbody>
                    </table>
                </form> -->
            </div>
        </div>
    </div>
</div>


</body>
</html>
