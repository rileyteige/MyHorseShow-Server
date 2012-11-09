<?php
require_once 'setup.php';

/* Creates a barn and assigns it to the given event. */
function createClass($divId, $className) {
	
	$division = R::load(DIVISION, $divId);
	if ($division->id == 0) {
		throw new Exception('Invalid division id.');
		exit();
	}
	
	$class = R::dispense(SHOWCLASS);
	$class->name = $className;
	
	$id = R::store($class);
	
	$division->ownClass[] = $class;
	R::store($division);
	
	return $id;
}

function addRider($classId, $riderId) {
	$class = R::load(SHOWCLASS, $classId);
	if (!$class->id) {
		throw new Exception('Invalid class id: '.$classId);
	}
	
	$rider = R::load(USER, $riderId);
	if (!$rider->id) {
		throw new Exception('Invalid rider id: '.$riderId);
	}
	
	$class->sharedUser[] = $rider;
	R::store($class);
	
	return $class->id;
}

function setClassTime($classId, $time) {
	$class = R::load(SHOWCLASS, $classId);
	if (!$class->id) {
		throw new Exception('Invalid class id: '.$classId);
	}
	
	$class->time = $time;
	R::store($class);
	
	return $class->id;
}
?>