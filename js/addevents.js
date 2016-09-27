$(document).ready(function() {
	var addEventButton = $(".js-add-event-button");

	addEventButton.click(function() {
		var date = document.getElementById("date"),;
			start_time = document.getElementById("start_time"),
			end_time = document.getElementById("end_time"),
			location = document.getElementById("location"),
			event_type = document.getElementById("event_type"),
			event_name = document.getElementById("event_name"),
			event_description = document.getElementById("event_description"),
			event_rsvp_link = document.getElementById("event_rsvp_link");

		if (!date) {
			date.addClass("warning");
		} 

		if (!start_time) {
			start_time.addClass("warning");
		} 

		if (!end_time) {
			end_time.addClass("warning");
		}

		if (!location) {
			location.addClass("warning");
		} 

		if (!event_type) {
			event_type.addClass("warning");
		} 

		if (!event_name) {
			event_name.addClass("warning");
		} 

		if (!event_description) {
			event_description.addClass("warning");
		} 

		if (!event_rsvp_link) {
			event_rsvp_link.addClass("warning");	
		}

	});

});
