<?php
/*
 * Add Events to the Federal & Global Fellows Events page
 * By: Cameron Malagar
 * Date: 9/22/2016
 *
 */

require_once('Connections/NewLogin.php');
require_once('includes/header.php');

$date = mysql_real_escape_string($_POST['date']);
$start_time = mysql_real_escape_string($_POST['start_time']);
$end_time = mysql_real_escape_string($_POST['end_time']);
$location = mysql_real_escape_string($_POST['location']);
$event_name = mysql_real_escape_string($_POST['event_name']);
$event_type = mysql_real_escape_string( $_POST['event_type']);
$event_description = mysql_real_escape_string($_POST['event_description']);
$event_rsvp_link = mysql_real_escape_string($_POST['event_rsvp_link']);

mysql_select_db($database_localhost, $localhost);
$sql = "INSERT INTO tblEvents
			(EventDate, EventName, EventYear, EventLocation, EventType, EventAttendanceLink,
			 EventStartTime, EventEndTime, EventDescription)
		VALUES 
			('" . $date . "', '" . $event_name . "', '2017', '" . $location . "', '" . $event_type . 
			"', '" . $event_rsvp_link . "', '" . $start_time . "', '" . $end_time . "', '" . $event_description . "');";

$result = mysql_query($sql, $localhost) or die(mysql_error());

?>

	<div class="row">
		<div class="panel panel-default">
			<div class="panel-heading">Add Events</div>
			<div class="panel-body">
				<h3>Event Added Successfully</h3>
			</div>
		</div>
	</div>
</body>
</html>