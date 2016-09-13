<?php  

/*
 * Events with Federal & Global Fellows
 * By: Cameron Malagar
 * Date: 9/8/2016
 *
 */

require_once('Connections/FGSP.php');

if (!isset($_SESSION)) {
    session_start();
}
  
$MM_authorizedUsers = "0,1";
$MM_donotCheckaccess = "false";

if (!$MM_authorizedUsers) {
	echo "No authorized";
}

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
    // For security, start by assuming the visitor is NOT authorized. 
    $isValid = False; 

    // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
    // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
    if (!empty($UserName)) { 
    	// Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    	// Parse the strings into arrays. 
    	$arrUsers = Explode(",", $strUsers); 
    	$arrGroups = Explode(",", $strGroups); 
    	if (in_array($UserName, $arrUsers)) { 
       		$isValid = true; 
    	} 
    	// Or, you may restrict access to only certain users based on their username. 
    	if (in_array($UserGroup, $arrGroups)) { 
      		$isValid = true; 
    	} 
    	if (($strUsers == "") && false) { 
        	$isValid = true; 
    	} 
    } 
    return $isValid; 
}

// $MM_restrictGoTo = "Login1.php";
// if (!((isset($_SESSION['MM_Username'])) && 
// 		isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup']))) {   
//     $MM_qsChar = "?";
//     $MM_referrer = $_SERVER['PHP_SELF'];
//     if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
//     if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
//     	$MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
//    	$MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
//     echo "You don't have access to this page.";
//     header("Location: ". $MM_restrictGoTo); 
//     exit;
// }

echo $_SESSION;

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
					<button type="button" class="btn btn-primary js-add-event-button">add</button>
					<button type="button" class="btn btn-warning js-edit-event-button">edit</button>
					<button type="button" class="btn btn-danger js-delete-event-button">delete</button>
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


</body>

</html>