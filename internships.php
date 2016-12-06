<?php
/*
 * Internships with Federal & Global Fellows
 * By: Cameron Malagar
 * Date: 9/8/2016
 *
 */

session_start();

// confirms that the user is logged in
if (isset($_SESSION('MM_Username'))) {
	$MM_redirectLogin = "login.php";
    header("Location: " . $MM_redirectLogin);
}

// confirms that the user is authorized to access this page
if ($_SESSION['MM_UserGroup'] != 1 || $_SESSION['MM_UserGroup'] == 0) {
	if ($_SESSION['MM_UserGroup'] == 2) {
		$MM_redirectLogin = "applicantcontrolpanel.php";
	} else {
		$MM_redirectLogin = "login.php";
	}

    header("Location: " . $MM_redirectLogin);
}

require_once('Connections/NewLogin.php');
require_once('includes/header.php');

mysql_select_db($database_localhost, $localhost);
$sql = 'SELECT * 
		FROM tblInternships';

$result = mysql_query($sql, $localhost) or die(mysql_error());
$num_rows = mysql_num_rows($result);

?>

	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">Events with Federal & Global Fellows</div>
				<div class="panel-body">
					<table class="table table-hover js-events-table">
						<thead>
							<tr>
								<th class="col-md-2">Organization</th>
								<th class="col-md-1">Position</th>
								<th>Term</th>
								<th>Deadline for Application</th>
								<th>Organizational Focus</th>
								<th>Mandatory Hours</th>
								<th>Mandatory Requirements</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($rows_event_data = mysql_fetch_assoc($result)) { ?>
							<tr>
								<td><?php echo date("M d, Y", strtotime($rows_event_data["date"]))?></td>
								<td><?php echo date("g:ia", strtotime($rows_event_data["start_time"]))?></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>

			<div class="panel panel-default js-add-event-panel hidden">
				<div class="panel-heading">Add Event</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="date">Date:</label>
								<input type="date" class="form-control" id="date">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="start_time">Start Time:</label>
								<input type="time" class="form-control" id="start_time">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="end_time">End Time:</label>
								<input type="time" class="form-control" id="end_time">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="location">Location:</label>
								<input type="text" class="form-control" id="location">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="event_name">Event Name:</label>
						<input type="text" class="form-control" id="event_name">
					</div>
					<div class="form-group">
						<label for="event_description">Description:</label>
						<input type="text" class="form-control" id="event_description">
					</div>	
					<div class="form-group">
						<label for="event_rsvp_link">RSVP Link:</label>
						<input type="text" class="form-control" id="event_rsvp_link">
					</div>
					<button type="button" class="btn btn-primary js-final-add-event-button">Add Event</button>
					<button type="button" class="btn btn-danger js-cancel-add-event-button">Cancel</button>
				</div>
			</div>

			<div class="panel panel-default js-edit-event-panel hidden">
				<div class="panel-heading">Edit Event</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="date">Date:</label>
								<input type="date" class="form-control" id="date">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="start_time">Start Time:</label>
								<input type="time" class="form-control" id="start_time">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="end_time">End Time:</label>
								<input type="time" class="form-control" id="end_time">
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="location">Location:</label>
								<input type="text" class="form-control" id="location">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="event_name">Event Name:</label>
						<input type="text" class="form-control" id="event_name">
					</div>
					<div class="form-group">
						<label for="event_description">Description:</label>
						<input type="text" class="form-control" id="event_description">
					</div>	
					<div class="form-group">
						<label for="event_rsvp_link">RSVP Link:</label>
						<input type="text" class="form-control" id="event_rsvp_link">
					</div>
					<button type="button" class="btn btn-primary js-final-edit-event-button">Add Event</button>
					<button type="button" class="btn btn-danger js-cancel-edit-event-button">Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>

</body>

</html>