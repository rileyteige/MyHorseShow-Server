<?php
require_once('setup.php');

function invalidLogin() {
	return array(USER_ID => INVALID_LOGIN_CODE, USER_FNAME => null, USER_LNAME => null);
}

function getLoginInfo($email, $password) {
	$user = R::findOne(USER, ' email = ? ', array ( $email ));
	if ($user == null || $password != $user->password)
		return invalidLogin();
		
	$eventIds = R::related($user, EVENT);
	$events = array();
	foreach($eventIds as $key => $value) {
		echo $value;
		$event = R::findOne(EVENT, ' id = ? ', array ( $value['id'] ));
		$events[] = $event;
	}
		
	return array(USER_ID => $user->id,
				USER_FNAME => $user->firstname,
				USER_LNAME => $user->lastname,
				USER_EVENTS => $events);
}

function getUser($email) {
	return R::findOne(USER, ' email = ? ', array ( $email ));
}

?>