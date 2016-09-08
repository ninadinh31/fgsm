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
			   EventTime as time
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
</head>	

<body>
	<table class="table table-hover">
		<caption>Events with Federal & Global Fellows</caption>
		<thead>
			<tr>
				<th>Date</th>
				<th>Event</th>
				<th>Location</th>
				<th>Time</th>
				<th>Type</th>
			</tr>
		</thead>
		<tbody>
			<?php while ($rows_event_data = mysql_fetch_assoc($result)) {
			<tr>
				<td><?php $rows_event_data["date"]?></td>
				<td><?php $rows_event_data["name"]?></td>
				<td><?php $rows_event_data["location"]?></td>
				<td><?php $rows_event_data["time"]?></td>
				<td><?php $rows_event_data["type"]?></td>
			</tr>
			} ?>
		</tbody>
	</table>



</body>

</html>