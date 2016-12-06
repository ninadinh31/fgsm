<?php
/*
 * Add Events to the Federal & Global Fellows Events page
 * By: Cameron Malagar
 * Date: 9/22/2016
 *
 */

session_start();

// confirms that the user is logged in
if (isset($_SESSION('MM_Username'))) {
	$MM_redirectLogin = "login.php";
    header("Location: " . $MM_redirectLogin);
}

// confirms that the user is authorized to access this page
if ($_SESSION['MM_UserGroup'] != 1) {
	if ($_SESSION['MM_UserGroup'] == 0) {
		$MM_redirectLogin = "studentcontrolpanel.php";
	} else if ($_SESSION['MM_UserGroup'] == 2) {
		$MM_redirectLogin = "applicantcontrolpanel.php";
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
				<div class="panel-heading">Add Event</div>
				<div class="panel-body">
					<form action="insertevent.php" method="post">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="date">Date:</label>
									<input type="date" name="date" class="form-control" id="date">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="start_time">Start Time:</label>
									<input type="time" name="start_time" class="form-control" id="start_time">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="end_time">End Time:</label>
									<input type="time" name="end_time" class="form-control" id="end_time">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="location">Location:</label>
									<input type="text" name="location" class="form-control" id="location">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="event_name">Event Name:</label>
							<input type="text" name="event_name" class="form-control" id="event_name">
						</div>
						<div class="form-group">
							<label for="event_type">Event Type:</label>
							<input type="text" name="event_type" class="form-control" id="event_type">
						</div>
						<div class="form-group">
							<label for="event_description">Description:</label>
							<input type="text" name="event_description" class="form-control" id="event_description">
						</div>	
						<div class="form-group">
							<label for="event_rsvp_link">RSVP Link:</label>
							<input type="text" name="event_rsvp_link" class="form-control" id="event_rsvp_link">
						</div>
						<button type="submit" class="btn btn-primary js-add-event-button">Add Event</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>		
</html>