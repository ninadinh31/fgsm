<?php 

if (!isset($_SESSION['MM_UserGroup']) || $_SESSION['MM_UserGroup'] != 2) {
	header("Location: login.php");
} else {
	session_start();
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
				</div>
			</div>
		</div>
	</div>
</body>
</html>
