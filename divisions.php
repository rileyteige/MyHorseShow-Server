<?php
require_once 'setup.php';

/* Creates a barn and assigns it to the given event. */
function createDivision($eventId, $name) {
	$event = new Event($eventId);
	
	if ($event->GetEvent() == null) {
		throw new Exception('Invalid event id.');
		exit();
	}
	
	$division = R::dispense(DIVISION);
	$division->name = $name;
	
	$id = R::store($division);
	
	if ($id <= 0) {
		throw new Exception('Could not store division.');
		exit();
	}
	
	if ($event->AddDivision($division) < 0) {
		throw new Exception('Could not add division to event.');
		exit();
	}
	
	$event->Save();
	
	return $id;
}

?>