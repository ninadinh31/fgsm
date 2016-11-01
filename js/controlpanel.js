$(document).ready(function() {

	var username = $('js-username');
	var usergroup = $('js-usergroup');
	var addEvents = $('js-add-events');
	var editEvents = $('js-edit-events');

	if (usergroup.text() === "1") {
		addEvents.show();
		editEvents.show();
	}

});