<?php 

require_once('Connections/NewLogin.php'); 

if (!isset($_SESSION['MM_UserGroup']) || $_SESSION['MM_UserGroup'] != 0) {
	header("Location: login.php");
} else {
	session_start();
}

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