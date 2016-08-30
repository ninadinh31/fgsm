<body>
<h1>Reset Password</h1>
<?php 
  //require_once('../Connections/FGSP.php'); 
	$con = mysql_connect("localhost","root","");	
	if (mysql_errno()) {
		printf("Connect failed :%s\n", mysql_error);
		exit();
	}
	mysql_select_db("fgsp", $con);
	$sql = "SELECT * FROM tblusers WHERE Username='" . $_POST["username"]. "';";
	if ($result = mysql_query($sql, $con)) {	
		if (mysql_num_rows($result) > 0){
			$rows = mysql_fetch_assoc($result);
			echo $rows["Username"]. " is an existing user!";
		} else {
			mysql_close($con);
			echo "<script type='text/javascript'>alert('Not an existing user!');
					window.location.href='resetpassword.php'</script>";
		}
	}

	# need to write a code that sets session to username
	# this depends on how you are implementing the sessions
?>
<p>Reenter your username and create a new password for your account login.</p>
<form action="resetpasswordform2.php" method="POST">
        Username: <br><input type="text" name="username1"><br>
		New Password: <br><input type="password" name="password"><br>
		Confirm New Password: <br><input type="password" name="passwordconfirm"><br><br>
		<input type="submit" value="Submit">
		<?php header("resetpasswordform2.php"); ?>
</form>

</body>
