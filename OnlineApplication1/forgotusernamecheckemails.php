<body>
<?php
//require_once('../Connections/FGSP.php'); 
	if ($_POST["email"] != $_POST["emailconfirm"]) {
		echo "<script type='text/javascript'>alert('Emails do not match!');window.location.href='forgotusername.php'</script>";
	} else {
		$con = mysql_connect("localhost","root","");	
		if (mysql_errno()) {
			printf("Connect failed :%s\n", mysql_error);
			exit();
		}
	}
?>

<?php
	
	if ($_POST["email"] == $_POST["emailconfirm"]) {
		mysql_select_db("fgsp", $con);
		if ($result = mysql_query("SELECT * FROM tblusers WHERE Email='cmalagar5@gmail.com'", $con)) {
			if (mysql_num_rows($result) > 0) {
				$rows = mysql_fetch_assoc($result);
				echo "Email will be sent to " . $rows["Email"] . " shortly. If none received, please contact
				the Federal/Global Semester office at federalsemesterdc@umd.edu. "; 
				mail("cmalagar5@gmail.com", "Forgot Username - Federal Semester",
				"Dear ". $rows["Firstname"] . ", \n Your username for Federal Semester is ". 
				$rows["Username"]. ". If you have further questions, please contact federalsemesterdc@umd.edu.");
			} else {
				echo "No Email Exists. Please try again or contact the Federal/Global Semester Office at 
				federalsemesterdc@umd.edu";
			}
			mysql_free_result($result);
		} else {
			echo "no email exists";
		}
		mysql_close($con);
	} else {
		header("forgotusername.php");
	}
?>

</body>
