$(document).ready(function() {

	var username = $('.js-username');
	var usergroup = $('.js-usergroup').html();
	var addEvents = $('.js-add-events');
	var editEvents = $('.js-edit-events');


	alert(usergroup.text());

	if (usergroup.text() === '1') {
		addEvents.show();
		editEvents.show();
		alert('cameron');
	} else {
		alert('hello');
	}

});