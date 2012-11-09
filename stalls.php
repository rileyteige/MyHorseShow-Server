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
?>