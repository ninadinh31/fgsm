<?php
/*
 * Edit existing events in the Federal & Global Fellows Events page
 * By: Cameron Malagar
 * Date: 9/27/2016
 *
 */

require_once('includes/header.php');
?>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
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
				</div>
			</div>
		</div>
	</div>
</body>		
</html>