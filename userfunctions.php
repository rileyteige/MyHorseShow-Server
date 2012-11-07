<?php
require_once('setup.php');

function invalidLogin() {
	return array(USER_ID => INVALID_LOGIN_CODE, USER_FNAME => null, USER_LNAME => null);
}

function getLoginInfo($email, $password) {
	$user = R::findOne(USER, ' email = ? ', array ( $email ));
	if ($user == null || $password != $user->password)
		return invalidLogin();
		
	$eventIds = $user->sharedEvent;
	$events = array();
	foreach($eventIds as $key => $value) {
		$event = R::findOne(EVENT, ' id = ? ', array ( $value['id'] ));
		$addedEvent = array ('id' => $event->id,
							'name' => $event->name,
							'sdate' => $event->startdate,
							'edate' => $event->enddate);
		$events[] = $addedEvent;
		$dates[$key] = $addedEvent['sdate']; // sdate index
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