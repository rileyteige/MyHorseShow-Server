<?php
require_once('setup.php');
require_once('userfunctions.php');

$eventId = $_GET[EVENT_ID];
$email = $_GET[EMAIL_ADDR_PARAM];
if ($eventId == null || $email == null)
	return;
	
$event = R::load(EVENT, $eventId);
$user = getUser($email);

if ($user->id <= 0 || !$event->id)
	return;

$user->sharedEvent[] = $event;

$id = R::store($user);

echo 'Success.';

R::close();

?>