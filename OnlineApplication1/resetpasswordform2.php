<?php require_once('../Connections/FGSP.php'); ?>
<body>
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
?>
<?php
//require_once('../Connections/FGSP.php'); 
	# ensures that password and passwordconfirm are the same thing
	$password = sha1(GetSQLValueString($_POST['password'], "text"));
	$confirmpassword = sha1(GetSQLValueString($_POST['passwordconfirm'], "text"));
	
	if ($password != $confirmpassword) {
		echo "<script type='text/javascript'>alert('Passwords do not match! Please put valid entries.');
				window.location.href='forgotusername.php'</script>";
	} //else {
	//	$con = mysql_connect("localhost","root","");	
	//	if (mysql_errno()) {
		//	printf("Connect failed :%s\n", mysql_error);
	//		exit();
	//	}
	//}
	mysql_select_db($database_FGSP, $FGSP);
	$sql = "SELECT * FROM tblusers WHERE Username='" . $_POST["username1"]. "';";
	if ($result = mysql_query($sql, $FGSP)) {
		# if the result is > 0, then the username is in the database, or else, 
		# the username does not exist
		if (mysql_num_rows($result) > 0) {
			$sql = sprintf("UPDATE tblusers SET Password='{$password}', ConfirmPassword='{$confirmpassword}'
					WHERE Username=%s", GetSQLValueString($_POST["username1"], "text"));
					
			# again this part will be adjusted based on your implementation of 
			# sessions because I am just passing the username from the user input.
			mysql_query($sql, $FGSP);						
			echo "<script type='text/javascript'>alert('You have reset your password. You can now sign onto your account from the login page :).');
					window.location.href='Login1.php'</script>";
			# need the name of the login php page so that they can redirect there after
			# they finish successfully reseetting their password
		} else {
			echo "<script type='text/javascript'>alert('Username does not exist. Please use your email to acquire your existing username, create a new account, or contact an administrator.');
					window.location.href='resetpassword.php'</script>";
		}
		mysql_free_result($result);
	}
	mysql_close($FGSP);
?>

</body>

