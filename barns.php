<?php
require_once 'setup.php';

/* Creates a barn and assigns it to the given event. */
function createBarn($eventId, $name) {
	$event = new Event($eventId);
	
	if ($event->GetEvent() == null) {
		throw new Exception('Invalid event id.');
		exit();
	}
	
	$barn = R::dispense(BARN);
	$barn->name = $name;
	
	$id = R::store($barn);
	
	if ($event->AddBarn($barn) < 0) {
		throw new Exception('Could not add barn to event.');
		exit();
	}
	
	$event->Save();
	
	return $id;
}
?>