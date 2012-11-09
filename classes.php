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
?>