$(document).ready(function() {
	var addEventButton = $(".js-add-event-button");
	var addEventPanel = $(".js-add-event-panel");	

	addEventButton.click(function() {
		addEventPanel.removeClass("hidden");
	});

});
