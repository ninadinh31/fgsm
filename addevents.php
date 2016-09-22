<?php
/*
 * Add Events to the Federal & Global Fellows Events page
 * By: Cameron Malagar
 * Date: 9/22/2016
 *
 */

require_once('Connections/FGSP.php');
require_once('includes/header.php');

mysql_select_db($database_FGSP, $FGSP);
$sql = 'INSERT INTO tblEvents
			(EventDate, EventName, EventYear, EventLocation, EventType, EventAttendanceLink,
			 EventStartTime, EventEndTime, EventDescription)
		VALUES 
			();';

// $result = mysql_query($sql, $FGSP) or die(mysql_error());
?>

	<div class="row">
		<div class="panel panel-default">
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
					<label for="event_type">Event Type:</label>
					<input type="text" class="form-control" id="event_type">
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
				<button type="button" class="btn btn-primary js-add-event-button">Add Event</button>
			</div>
		</div>
	</div>
</body>		
</html>