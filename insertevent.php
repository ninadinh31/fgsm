<?php
/*
 * Insert events to the Federal & Global Fellows database
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

echo $event_name;

mysql_select_db($database_localhost, $localhost);
$sql = sprintf("INSERT INTO tblEvent
			(EventDate, EventName, EventYear, EventLocation, EventType, EventAttendanceLink,
			 EventStartTime, EventEndTime, EventDescription)
		VALUES 
			('%s', '%s', '2017', '%s', '%s', '%s', '%s', '%s', '%s');",
			$date, $event_name, $location, $event_type, $event_rsvp_link, $start_time, $end_time, $event_description);

$result = mysql_query($sql, $localhost) or die(mysql_error());

?>

	<div class="row">
		<h1><?php echo $event_name?></h1>
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">Add Events</div>
				<div class="panel-body">
					<h3>Event Added Successfully</h3>
					<p><a href="events.php" title="Back to events">Back to events</a></p>
				</div>
			</div>
		</div>
	</div>
</body>
</html>