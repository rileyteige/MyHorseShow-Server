<?php

require('setup.php');

$name = $_GET[EVENT_NAME];
if ($name == null)
	return;

$event = R::dispense(EVENT);
$event->name = $name;

$id = R::store($event);

echo json_encode(array(EVENT_ID => $id, EVENT_NAME => $name));

R::close();

?>