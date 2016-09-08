<?php  

require_once('Connections/FGSP.php');

mysql_select_db($database_FGSP, $FGSP);
$sql = 'SELECT EventID as id,
			   EventDate as date, 
			   EventName as name, 
			   EventYear as school_year, 
			   EventLocation as location, 
			   EventType as type, 
			   EventPOCID as poc_id,
			   EventAttendanceListLink as attendance_link,
			   EventStartTime as start_time,
			   EventEndTime as end_time,
			   Description as description
		FROM tblevents';

$result = mysql_query($sql, $FGSP) or die(mysql_error());
$num_rows = mysql_num_rows($result);

?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>
		Federal & Global Fellows Events
	</title>
	<link href="css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <script src="js/jquery-1.11.3.min.js"></script>
    <script src="js/events.js"></script>
</head>	

<body>
	<div class="row">
		<div class="col-md-1"></div>
		<div class="col-md-10">
			<div class="panel panel-default">
				<div class="panel-heading">Events with Federal & Global Fellows</div>
				<div class="panel-body">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>Date</th>
								<th>Start Time</th>
								<th>End Time</th>
								<th>Event</th>
								<th>Location</th>
								<th>Description</th>
								<th>RSVP</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($rows_event_data = mysql_fetch_assoc($result)) { ?>
							<tr>
								<td><?php echo $rows_event_data["date"]?></td>
								<td><?php echo $rows_event_data["start_time"]?></td>
								<td><?php echo $rows_event_data["end_time"]?></td>
								<td><?php echo $rows_event_data["name"]?></td>
								<td><?php echo $rows_event_data["location"]?></td>
								<td><?php echo $rows_event_data["description"]?></td>
								<td><?php echo $rows_event_data["attendance_link"]?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<button type="button" class="btn btn-primary js-add-event-button">Add Event</button>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">Add Event</div>
				<div class="panel-body">
					
				</div>
			</div>
		</div>
	</div>


</body>

</html>