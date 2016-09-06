<?php  

require_once('Connections/FGSP.php');

mysql_select_db($database_FGSP, $FGSP);
$sql = 'SELECT EventDate as date, 
			   EventName as name, 
			   EventYear as school_year, 
			   EventLocation as location, 
			   EventType as type, 
			   EventPOCID as poc_id, 
			   EventAttendanceListLink as attendance_link
		FROM tblevents';

$result = mysql_query($sql, $FGSP) or die(mysql_error());
$rows_event_data = mysql_fetch_assoc($result);

echo $rows_event_data['date'];

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
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>September 6, 2016</td>
				<td>Website Work</td>
				<td>Marie Mount Hall</td>
				<td>12:00pm</td>
				<td>You have work today!</td>
			</tr>
		</tbody>
	</table>



</body>

</html>