(function(global){	
	global.sched = {};

	var event_id = document.getElementById("event_id");
	var event_name = document.getElementById('event_name');
	var event_date = document.getElementById('event_date');
	var event_time = document.getElementById('event_time');
	var location = document.getElementById('location');
	var notes = document.getElementById('notes');

	
	sched.displayEvent = function(row) {
		stringID = row.id;
		var lastChar = stringID.slice(5);
		event_id.value = Number(lastChar);
		event_name.value = row.children[0].innerHTML;
		location.value = row.children[3].innerHTML;
		notes.value = row.children[4].innerHTML;
	}

	sched.clearAll = function() {
		event_name.value = "";
		event_date.value = "";
		event_time.value = "";
		location.value = "";
		notes.value = "";

	}



}(window))
