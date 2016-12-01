<?php 

require_once('Connections/NewLogin.php'); 

session_start();

if (!$_SESSION['MM_UserGroup'] != STUDENT) {
	if ($_SESSION['MM_UserGroup'] == ADMIN) {
		$MM_redirectLogin = "admincontrolpanel.php";
	} else if ($_SESSION['MM_UserGroup'] == APPLICANT) {
		$MM_redirectLogin = "applicantcontrolpanel";
	} else {
		$MM_redirectLogin = "login.php";
	}

    header("Location: " . $MM_redirectLogin);
}


require_once('includes/header.php');

?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Student Control Panel</div>
			<div class="panel-body">
				<div>
					<p>Welcome <?php echo $_SESSION['MM_Username']; ?>!</p>
				</div>
				<div>
					<a href="events.php">Federal & Global Fellows Events</a>
				</div>
				<div>
					<a href="">Internship Opportunities</a>
				</div>
			</div>
		</div>
	</div>
</div>

</body>
</html>