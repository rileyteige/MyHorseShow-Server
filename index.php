<?php
require_once 'setup.php';

$app = new \Slim\Slim();

/* GET USER LOGIN INFO */
$app->get('/user/:email/:password', function ($email, $password) {
	$user = getLoginInfo($email, $password);
	if ($user != null) {
		echo json_encode($user);
	}
});

/* GET ALL EVENTS */
$app->get('/events/all', function() {
	echo json_encode(getAllEvents());
});

/* USERS */
$app->post('/user', function () {
	$body = http_get_request_body();
	if ($body != null) {
		$typeCheck = json_decode($body);
		switch($typeCheck->type) {
		
			/* CREATE A USER */
			case 'user': {
				$inUser = typeCheck->user;
				if ($inUser != null) {
					$userId = createUser($inUser->email, $inUser->password, $inUser->firstname, $inUser->lastname, $inUser->usefid);
					if ($userId > 0) {
						$user = R::load(USER, $userId);
						echo json_encode($user->export());
					}
				}
			} break;
			
			/* LINK A USER TO AN EVENT */
			case 'event': {
				$inEvent = typeCheck->event;
				if ($inEvent != null) {
					$returnCode = giveUserEvent($inEvent->email, $inEvent->eventId);
					if ($returnCode < 0) {
						throw new Exception('Could not give event to user.');
					}
				}
			}
		}
	}
});

/* EVENTS */
$app->post('/event', function () {
	$body = http_get_request_body();
	if ($body != null) {
		$typeCheck = json_decode($body);
		switch($typeCheck->type) {
		
			/* CREATE AN EVENT */
			case 'event': {
				$inEvent = $typeCheck->event;
				if ($inEvent != null) {
					$eventId = createEvent($inEvent->aid, $inEvent->name, $inEvent->startdate, $inEvent->enddate);
					if ($eventId > 0) {
						$event = R::load(EVENT, $eventId);
						echo json_encode($event->export());
					}
				}
			} break;
			
			/* ADD A BARN TO AN EVENT */
			case 'barn': {
				$inBarn = $typeCheck->barn;
				if ($inBarn != null) {
					$barnId = createBarn($inBarn->eid, $inBarn->name);
					if ($barnId > 0) {
						$barn = R::load(BARN, $barnId);
						echo json_encode($barn->export());
					}
				}
			} break;
			
			/* ADD A DIVISION TO AN EVENT */
			case 'division': {
				$inDivision = $typeCheck->division;
				if ($inDivision != null) {
					$divisionId = createDivision($inDivision->eid, $inDivision->name);
					if ($divisionId > 0) {
						$division = R::load(DIVISION, $divisionId);
						echo json_encode($division->export());
					}
				}
			} break;
			
			/* ADD A CLASS TO AN EVENT */
			case 'class': {
				$inClass = $typeCheck->showclass;
				if ($inClass != null) {
					$classId = createClass($inClass->did, $inClass->name);
					if ($classId > 0) {
						$class = R::load(SHOWCLASS, $classId);
						echo json_encode($class->export());
					}
				}
			} break;
		}
	}
});

$app->run();
?>