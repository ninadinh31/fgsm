<?php 

require_once('Connections/NewLogin.php'); 

define("STUDENT", 0);
define("ADMIN", 1);
define("APPLICANT", 2);

session_start();

// *** Validate request to login to this site.
if (isset($_SESSION['MM_UserGroup'])) {
    
    // This is an Accepted Student 
    if ($_SESSION['MM_UserGroup'] == STUDENT) {
        $MM_redirectLoginSuccess = "studentcontrolpanel.php";

    // This is an Admin user
    } else if ($_SESSION['MM_UserGroup'] == ADMIN) {
        $MM_redirectLoginSuccess = "admincontrolpanel.php";

    // This is an applicant
    } else if ($_SESSION['MM_UserGroup'] == APPLICANT) {
        $MM_redirectLoginSuccess = "application/";
    }

    header("Location: " . $MM_redirectLoginSuccess);
}

if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
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

$loginFormAction = $_SERVER['PHP_SELF'];

if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST["loginUsername"])) {
    $loginUsername = $_POST["loginUsername"];
    
    $password = sha1(GetSQLValueString($_POST['loginPassword'], "text"));
    
    $MM_fldUserAuthorization = "UserLevel";
    $MM_redirectLoginSuccess = "controlpanel.php";
    $MM_redirectLoginFailed = "login.php";
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
        
        // This is an Accepted Student 
        if ($_SESSION['MM_UserGroup'] == STUDENT) {
            $MM_redirectLoginSuccess = "studentcontrolpanel.php";

        // This is an Admin user
        } else if ($_SESSION['MM_UserGroup'] == ADMIN) {
            $MM_redirectLoginSuccess = "admincontrolpanel.php";

        // This is an applicant
        } else if ($_SESSION['MM_UserGroup'] == APPLICANT) {
            $MM_redirectLoginSuccess = "application/";
        }
 
        header("Location: " . $MM_redirectLoginSuccess);
    } else {
        // if login fails 
        header("Location: " . $MM_redirectLoginFailed);
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
                        <input type="text" class="form-control" name="loginUsername" id="loginUsername" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <label for="loginPassword">Password</label>
                        <input type="password" class="form-control" name="loginPassword" id="loginPassword" placeholder="Password" required>
                    </div>
                    <input value="Log In" type="submit" class="btn btn-default"><br />
                    <a href="Registration1.php">New User</a><br />
                    <a href="">Forgot Username</a><br />
                    <a href="">Forgot Password</a>
                </form>
            </div>
        </div>
    </div>
</div>


</body>
</html>
