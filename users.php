<?php
require_once 'setup.php';

function invalidLogin() {
	return array(USER_ID => INVALID_LOGIN_CODE, USER_FNAME => null, USER_LNAME => null);
}

function getUserByEmail($email) {
	return R::findOne(USER, ' email = ? ', array ( $email ));
}

function createUser($email, $password, $firstname, $lastname, $usefId) {
	if (getUserByEmail($email) != null)
		throw new Exception('Email already registered.');

	$user = R::dispense(USER);
	$user->email = $email;
	$user->password = $password;
	$user->firstname = $firstname;
	$user->lastname = $lastname;
	$user->usefid = $usefId;
	$user->sharedEvent = [];
		
	$id = R::store($user);

	return $id;
}

function giveUserEvent($email, $eventId) {
	$event = R::load(EVENT, $eventId);
	$user = getUserByEmail($email);

	if ($user->id <= 0 || !$event->id)
		return -1;

	$user->sharedEvent[] = $event;

	$id = R::store($user);
	
	return 0;
}

function getLoginInfo($email, $password) {
	$user = R::findOne(USER, ' email = ? ', array ( $email ));
	if ($user == null || $password != $user->password)
		return invalidLogin();
		
	$eventIds = $user->sharedEvent;
	$events = array();
	foreach($eventIds as $key => $value) {
		$events[] = $value->export();
		$dates[$key] = $value['startdate'];
	}
		
	array_multisort($dates, SORT_ASC, $events);
		
	return array(USER_ID => $user->id,
				USER_FNAME => $user->firstname,
				USER_LNAME => $user->lastname,
				USER_EVENTS => $events);
}

function getUser($email) {
	return R::findOne(USER, ' email = ? ', array ( $email ));
}

?>