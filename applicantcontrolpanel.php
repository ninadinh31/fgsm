<?php 

session_start();

// confirms that the user is logged in
if (isset($_SESSION('MM_Username'))) {
	$MM_redirectLogin = "login.php";
    header("Location: " . $MM_redirectLogin);
}

// confirms that the user is authorized to access this page
if ($_SESSION['MM_UserGroup'] != 2) {
	if ($_SESSION['MM_UserGroup'] == 1) {
		$MM_redirectLogin = "admincontrolpanel.php";
	} else if ($_SESSION['MM_UserGroup'] == 0) {
		$MM_redirectLogin = "studentcontrolpanel.php";
	} else {
		$MM_redirectLogin = "login.php";
	}

    header("Location: " . $MM_redirectLogin);
}

require_once('Connections/NewLogin.php'); 
require_once('includes/header.php');

?>

	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">Applicant Control Panel</div>
				<div class="panel-body">
					<div>
						<p>Welcome <?php echo $_SESSION['MM_Username']; ?></p>
					</div>
					<div>
						<a href="">Federal & Global Fellows Application</a>
					</div>
					<div class="row">
						<div class="col-md-11"></div>
						<div class="col-md-1">
							<a href="logout.php">Logout</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
