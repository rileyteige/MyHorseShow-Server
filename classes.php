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

function addRider($eventId, $classId, $riderId, $horseName) {
	$event = R::load(EVENT, $eventId);
	if (!$event->id) {
		throw new Exception('Invalid event id: '.$eventId);
	}
	
	$class = R::load(SHOWCLASS, $classId);
	if (!$class->id) {
		throw new Exception('Invalid class id: '.$classId);
	}
	
	$rider = R::load(USER, $riderId);
	if (!$rider->id) {
		throw new Exception('Invalid rider id: '.$riderId);
	}
	
	$participation = R::dispense(PARTICIPATION);
	$participation->rider = $rider;
	$participation->horse = $horseName;
	R::store($participation);
	
	$class->ownParticipation[] = $participation;
	R::store($class);
	
	$event->sharedUser[] = $rider;
	R::store($event);
	
	return $class->id;
}

function setClassTime($classId, $starttime) {
	$class = R::load(SHOWCLASS, $classId);
	if (!$class->id) {
		throw new Exception('Invalid class id: '.$classId);
	}
	
	$class->starttime = $starttime;
	R::store($class);
	
	return $class->id;
}

function getClassParticipants($participationIds) {
	if ($participationIds == null) {
		return;
	}
	
	$participations = array();
	foreach($participationIds as $key => $value) {
		$participation = R::load(PARTICIPATION, $value['id']);
		if (!$participation->id) {
			continue;
		}
		
		$rider = R::load(USER, $participation->rider_id);
		
		$participations[] = array(ID => $participation->id,
								PARTICIPATION_RIDER => $rider->id ? loadBasicUserInfo($rider) : null,
								PARTICIPATION_HORSE => $participation->horse,
								PARTICIPATION_RANK => $participation->rank);
	}
	return $participations;
}

function postRankings($classId, $rankings) {
	$class = R::load(SHOWCLASS, $classId);
	if (!$class->id) {
		return null;
	}
	
	$participations = R::exportAll($class->ownParticipation);
	if ($participations == null) {
		return null;
	}
	
	foreach($rankings as $key => $value) {
		$riderId = $value->id;
		$rank = $value->rank;
		
		if ($riderId == null || $rank == null) {
			return null;
		}
		
		$rider = R::load(USER, $riderId);
		if (!$rider->id) {
			continue;
		}
		
		foreach($participations as $participationGetter) {
			$participation = R::load(PARTICIPATION, $participationGetter[ID]);
			if ($participation->rider_id == $riderId) {
				$participation->rank = $rank;
				R::store($participation);
				break;
			}
		}
	}
	
	return R::store($class);
}
?>