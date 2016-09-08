$(document).ready(function() {
	var addEventButton = $(".js-add-event-button");
	var addEventPanel = $(".js-add-event-panel");	
	var cancelAddEventButton = $(".js-cancel-add-event-button");

	addEventButton.click(function() {
		addEventPanel.removeClass("hidden");
	});

	cancelAddEventButton.click(function() {
		addEventPanel.addClass("hidden");
	});

});
