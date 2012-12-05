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
	
	return getUserEventInfo($email, $eventId);
}

function getUserEvents($userId, $eventIds) {
	if ($eventIds == null)
		return null;
		
	$events = array();
	foreach ($eventIds as $key => $value) {
		$events[] = getEventInfo($userId, $value->id);
	}
	return $events;
}

function getUserEventInfo($email, $eventId) {
	$user = getUserByEmail($email);
	if ($user == null || !$eventId)
		return invalidLogin();
	
	$event = getEventClassInfo($eventId);
	if (!$event)
		return invalidLogin();
	
	$classes = array();
	if ($event[EVENT_DIVISIONS] != null) {

		foreach ($event[EVENT_DIVISIONS] as $division) {
			if ($division[DIVISION_CLASSES] == null)
				continue;
			
			foreach ($division[DIVISION_CLASSES] as $class) {
				if ($class[CLASS_PARTICIPANTS] == null)
					continue;
				
				foreach($class[CLASS_PARTICIPANTS] as $participation) {
					$riderId = $participation[PARTICIPATION_RIDER][ID];

					if ($riderId == $user->id) {
						$classes[] = $class;
						break;
					}
				}
			}
		}
	}
	
	return array( ID => $user->id,
				USER_FNAME => $user->firstname,
				USER_LNAME => $user->lastname,
				USER_EMAIL => $user->email,
				USER_USEF_ID => $user->usefid,
				DIVISION_CLASSES => $classes);
}

function getLoginInfo($email, $password) {
	$user = getUserByEmail($email);
	if ($user == null || $password != $user->password)
		return invalidLogin();
		
	$eventIds = $user->sharedEvent;
	$events = array();
	foreach($eventIds as $key => $value) {
		$events[] = R::exportAll($value);
		$dates[$key] = $value['startdate'];
	}
	
	if (count($events) > 0)
		array_multisort($dates, SORT_ASC, $events);

	return array(ID => $user->id,
				USER_FNAME => $user->firstname,
				USER_LNAME => $user->lastname,
				USER_EMAIL => $user->email,
				USER_USEF_ID => $user->usefid,
				USER_EVENTS => getUserEvents($user->id, $eventIds)); //count($events) > 0 ? $events : null);
}

function getUser($email) {
	return R::findOne(USER, ' email = ? ', array ( $email ));
}

/* Returns basic user info given a pre-loaded user. */
function loadBasicUserInfo($user) {
	return array(ID => $user->id, USER_FNAME => $user->firstname, USER_LNAME => $user->lastname, USER_EMAIL => $user->email, USER_USEF_ID => $user->usefid);
}

/* Returns basic info given an array of user IDs */
function getBasicUserInfo($userIds, $eventId) {
	if ($userIds == null)
		return null;
	
	$users = array();
	foreach ($userIds as $key => $value) {
		$user = R::load(USER, $value->id);
		if (!$user->id) {
			continue;
		}

		$users[] = loadBasicUserInfo($user);
	}
	return $users;
}

function getBasicUserInfoWithClasses($userIds, $eventId) {
	if ($userIds == null)
		return null;
	
	$users = array();
	foreach ($userIds as $key => $value) {
		$user = R::load(USER, $value->id);
		if (!$user->id) {
			continue;
		}
		
		$userInfo = loadBasicUserInfo($user);
		$userEventInfo = getUserEventInfo($user->email, $eventId);
		if ($userEventInfo)
			$userInfo[DIVISION_CLASSES] = $userEventInfo[DIVISION_CLASSES];
		$users[] = $userInfo;
	}
	return $users;
}

?>