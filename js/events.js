$(document).ready(function() {
	var addEventButton = $(".js-add-event-button");
	var editEventButton = $(".js-edit-event-button");
	var addEventPanel = $(".js-add-event-panel");	
	var editEventPanel = $(".js-edit-event-panel");
	var cancelAddEventButton = $(".js-cancel-add-event-button");
	var cancelEditEventButton = $(".js-cancel-edit-event-button");
	var finalAddEventButton = $(".js-final-add-event-button");

	addEventButton.click(function() {
		addEventPanel.removeClass("hidden");
	});

	editEventButton.click(function() {
		editEventPanel.removeClass("hidden");
	});

	cancelAddEventButton.click(function() {
		addEventPanel.addClass("hidden");
	});

	cancelEditEventButton.click(function() {
		editEventPanel.addClass("hidden");	
	});

	finalAddEventButton.click(function() {
		alert("Event added!");
	});

});
