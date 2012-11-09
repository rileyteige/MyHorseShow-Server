<?php
require_once 'setup.php';

/* Creates a stall and assigns it to the given barn. */
function createStall($barnId, $name) {
	$barn = R::load(BARN, $barnId);
	if (!$barn->id) {
		throw new Exception('Could not load barn.');
	}
	
	$stall = R::dispense(STALL);
	$stall->name = $name;
	
	$id = R::store($stall);
	
	$barn->ownStall[] = $stall;
	R::store($barn);
	
	return $id;
}

/* Assigns an occupant to a stall. */
function setStallOccupant($stallId, $riderId) {
	$stall = R::load(STALL, $stallId);
	if (!$stall->id) {
		throw new Exception('Could not load stall.');
	}
	
	$rider = R::load(USER, $riderId);
	if (!$rider->id) {
		throw new Exception('Could not load rider.');
	}
	
	$stall->occupant = $rider;
	R::store($stall);
	
	return $stall->id;
}
?>