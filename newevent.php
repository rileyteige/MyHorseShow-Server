<?php

require_once('setup.php');

$name = $_GET[EVENT_NAME];
$startdate = $_GET[EVENT_START_DATE];
$enddate = $_GET[EVENT_END_DATE];

if ($name == null || $startdate == null || $enddate == null)
	return;

$event = R::dispense(EVENT);
$event->name = $name;
$event->startdate = $startdate;
$event->enddate = $enddate;
$event->sharedUser = [];

$id = R::store($event);

echo json_encode(array(EVENT_ID => $id, EVENT_NAME => $name, EVENT_START_DATE => $startdate, EVENT_END_DATE => $enddate));

R::close();

?>