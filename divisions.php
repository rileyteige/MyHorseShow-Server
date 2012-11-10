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

function getDivisionClasses($classIds) {
	if ($classIds == null) {
		return null;
	}
	
	$classes = array();
	foreach ($classIds as $key => $value) {
		$class = R::load(SHOWCLASS, $value->id);
		if (!$class->id) {
			continue;
		}
		
		$classes[] = array(ID => $class->id,
						CLASS_NAME => $class->name,
						CLASS_START_TIME => $class->starttime,
						CLASS_PARTICIPANTS => getBasicUserInfo($class->sharedUser));
	}
	
	return $classes;
}

?>