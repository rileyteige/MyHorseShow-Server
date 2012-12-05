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

/* GET USER INFO FOR ONE EVENT */
$app->get('/user/:email/events/:eventId', function ($email, $eventId) {
	$user = getUserEventInfo($email, $eventId);
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
			case USER: {
				$inUser = $typeCheck->obj;
				if ($inUser != null) {
					$userId = createUser($inUser->email, $inUser->password, $inUser->firstname, $inUser->lastname, $inUser->usefid);
					if ($userId > 0) {
						$user = R::load(USER, $userId);
						echo json_encode($user->export());
					}
				}
			} break;
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
			case EVENT: {
				$inEvent = $typeCheck->obj;
				if ($inEvent != null) {
					$eventId = createEvent($inEvent->adminId, $inEvent->name, $inEvent->startdate, $inEvent->enddate);
					if ($eventId > 0) {
						$event = R::load(EVENT, $eventId);
						echo json_encode($event->export());
					}
				}
			} break;
			
			/* ADD A BARN TO AN EVENT */
			case BARN: {
				$inBarn = $typeCheck->obj;
				if ($inBarn != null) {
					$barnId = createBarn($inBarn->id, $inBarn->name);
					if ($barnId > 0) {
						$barn = R::load(BARN, $barnId);
						echo json_encode($barn->export());
					}
				}
			} break;
			
			/* ADD A CONTACT TO AN EVENT */
			case CONTACT: {
				$inContact = $typeCheck->obj;
				if ($inContact != null) {
					$contactId = createContact($inContact->id, $inContact->firstname, $inContact->lastname, $inContact->email, $inContact->phone, $inContact->occupationId);
					if ($contactId > 0) {
						$contact = R::load(CONTACT, $contactId);
						echo json_encode($contact->export());
					}
				}
			} break;
		}
	}
});

/* SPECIFIC EVENT */
$app->post('/event/:eventId', function($eventId) {
	$body = http_get_request_body();
	if ($body != null) {
		$typeCheck = json_decode($body);
		switch ($typeCheck->type) {

			/* ADD A DIVISION TO AN EVENT */
			case DIVISION: {
				$inDivision = $typeCheck->obj;
				if ($inDivision != null) {
					$divisionId = createDivision($eventId, $inDivision->name);
					if ($divisionId > 0) {
						$division = R::load(DIVISION, $divisionId);
						echo json_encode($division->export());
					}
				}
			} break;
		
			/* ADD A USER TO AN EVENT */
			case USER: {
				$inUser = $typeCheck->obj;
				if ($inUser != null) {
					$returnCode = giveUserEvent($inUser->email, $eventId);
					if ($returnCode < 0 || $returnCode == null) {
						throw new Exception('Could not give event to user.');
					} else {
						echo json_encode($returnCode);
					}
				}
			} break;
		}
	}
});

/* BARNS */
$app->post('/event/barns/:barnId', function($barnId) {
	$body = http_get_request_body();
	if ($body != null) {
		$typeCheck = json_decode($body);
		switch ($typeCheck->type) {
		
			/* ADD A STALL TO A BARN */
			case STALL: {
				$inStall = $typeCheck->obj;
				if ($inStall != null) {
					$stallId = createStall($barnId, $inStall->name);
					if ($stallId > 0) {
						$stall = R::load(STALL, $stallId);
						echo json_encode($stall->export());
					}
				}
			} break;
		}
	}
});

/* DIVISIONS */
$app->post('/divisions/:divisionId', function($divisionId) {
	$body = http_get_request_body();
	if ($body != null) {
		$typeCheck = json_decode($body);
		switch($typeCheck->type) {
		
			/* ADD A CLASS TO AN EVENT */
			case SHOWCLASS: {
				$inClass = $typeCheck->obj;
				if ($inClass != null) {
					$classId = createClass($divisionId, $inClass->name);
					if ($classId > 0) {
						$class = R::load(SHOWCLASS, $classId);
						echo json_encode($class->export());
					}
				}
			} break;
			
		}
	}
});

/* CLASSES */
$app->post('/events/:eventId/classes/:classId', function($eventId, $classId) {
	$body = http_get_request_body();
	if ($body != null) {
		$typeCheck = json_decode($body);
		switch ($typeCheck->type) {
		
			/* ADD A USER (RIDER) TO A CLASS */
			case USER: {
				$inUser = $typeCheck->obj;
				if ($inUser != null) {
					$returnCode = addRider($eventId, $classId, $inUser->id, $inUser->horse);
					if ($returnCode > 0) {
						$class = R::load(SHOWCLASS, $classId);
						echo json_encode($class->export());
					}
				}
			} break;
			
			/* SET A CLASS TIME */
			case START: {
				$inTime = $typeCheck->obj;
				if ($inTime != null) {
					$returnCode = setClassTime($classId, $inTime->starttime);
					if ($returnCode > 0) {
						$class = R::load(SHOWCLASS, $classId);
						echo json_encode($class->export());
					}
				}
			} break;
			
			/* POST RANKINGS TO A CLASS */
			/* EX: */
			/* { "type":"rankings", "obj":[ { "id":"1", "rank":"1" }, etc. ] }*/
			case RANKINGS: {
				$inRankings = $typeCheck->obj;
				if ($inRankings != null) {
					$classId = postRankings($classId, $inRankings);
					if ($classId > 0) {
						$class = R::load(SHOWCLASS, $classId);
						echo json_encode($class->export());
					}
				}
			}
		}
	}
});

$app->post('/event/barns/stalls/:stallId', function($stallId) {
	$body = http_get_request_body();
	if ($body != null) {
		$typeCheck = json_decode($body);
		switch ($typeCheck->type) {
		
			/* ASSIGN A USER TO A STALL */
			case USER: {
				$inUser = $typeCheck->obj;
				if ($inUser != null) {
					$returnCode = setStallOccupant($stallId, $inUser->id);
					if ($returnCode > 0) {
						$stall = R::load(STALL, $stallId);
						echo json_encode($stall->export());
					}
				}
			}
		}
	}

});

$app->post('/occupation', function() {
	$body = http_get_request_body();
	if ($body != null) {
		$typeCheck = json_decode($body);
		switch($typeCheck->type) {
		
			/* CREATE AN OCCUPATION */
			case OCCUPATION: {
				$inOccupation = $typeCheck->obj;
				if ($inOccupation != null) {
					$occId = createOccupation($inOccupation->name, $inOccupation->plural);
					if ($occId > 0) {
						$occupation = R::load(OCCUPATION, $occId);
						echo json_encode($occupation->export());
					}
				}
			} break;
		}
	}
});

$app->run();
?>