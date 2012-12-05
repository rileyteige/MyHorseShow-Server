<?php
require_once 'setup.php';

class Event {

	private $_redBeanEvent;

	public function Event($eventId) {
		$this->_redBeanEvent = R::load(EVENT, $eventId);
	}
	
	public function GetEvent() {
		return $this->_redBeanEvent;
	}
	
	private function Verify() {
		return $this->GetEvent() != null;
	}
	
	public function AddRider($rider) {
		if (!$this->Verify()) {
			return -1;
		}
		
		$this->GetEvent()->ownUser[] = $rider;
		return 0;
	}
	
	public function AddClass($class) {
		if (!$this->Verify()) {
			return -1;
		}
		
		$this->GetEvent()->ownClass[] = $class;
		return 0;
	}
	
	public function AddDivision($division) {
		if (!$this->Verify()) {
			return -1;
		}
		
		$this->GetEvent()->ownDivision[] = $division;
		return 0;
	}
	
	public function AddContact($contact) {
		if (!$this->Verify()) {
			return -1;
		}
		
		$this->GetEvent()->ownContact[] = $contact;
		return 0;
	}
	
	public function AddBarn($barn) {
		if (!$this->Verify()) {
			return -1;
		}
		
		$this->GetEvent()->ownBarn[] = $barn;
		return 0;
	}
	
	public function Save() {
		if (!$this->Verify()) {
			return -1;
		}
		
		return R::store($this->GetEvent());
	}
}

function createEvent($adminId, $name, $startdate, $enddate) {
	$event = R::dispense(EVENT);
	
	$admin = R::load(USER, $adminId);
	if (!$admin->id) {
		throw new Exception('Bad admin id: '.$adminId);
	}
	
	$event->admin = $admin;
	$event->name = $name;
	$event->startdate = $startdate;
	$event->enddate = $enddate;
	$event->sharedUser = [];
	$event->ownBarn = [];
	$event->ownContact = [];
	$event->ownDivision = [];
	
	$event->sharedUser[] = $admin;

	return R::store($event);
}

function getEvent($eventId) {
	return R::load(EVENT, $eventId);
}

function getAllEvents() {
	$events = array();
	$redbeanevents = R::getAll('select * from '.EVENT);
	if ($redbeanevents != null) {
		foreach($redbeanevents as $rbEvent) {
			$event = R::load(EVENT, $rbEvent['id']);
			$events[] = R::exportAll($event);
		}
	}
	return $events;
}

function rem_array($array, $str){
	foreach ($array as $key => $value) {
		if ($array[$key][ID] == "$str")
			unset($array[$key]);
	}
	return $array;
}

function getEventClassInfo($eventId) {
	$event = R::load(EVENT, $eventId);
	if (!$event->id)
		return null;
	
	return array(EVENT_DIVISIONS => getEventDivisions($event->ownDivision));
}

function getEventInfo($userId, $eventId) {
	$event = R::load(EVENT, $eventId);
	if (!$event->id) {
		return null;
	}
	
	$admin = R::load(USER, $event->admin_id);
	
	return array(ID => $event->id,
		EVENT_NAME => $event->name,
		EVENT_START_DATE => $event->startdate,
		EVENT_END_DATE => $event->enddate,
		EVENT_ADMIN => $admin->id ? loadBasicUserInfo($admin) : null,
		EVENT_BARNS => getEventBarns($event->ownBarn),
		EVENT_DIVISIONS => getEventDivisions($event->ownDivision),
		EVENT_CONTACTS => getEventContacts($event->ownContact),
		EVENT_PARTICIPANTS => $userId == $event->admin_id ? getBasicUserInfoWithClasses(rem_array($event->sharedUser, $event->admin_id), $eventId) : null);
}

function getEventBarns($barnIds) {
	if ($barnIds == null)
		return null;
	
	$barns = array();
	foreach($barnIds as $key => $value) {
		$barn = R::load(BARN, $value->id);
		
		if (!$barn->id) {
			continue;
		}
		
		$barns[] = array(ID => $barn->id,
						BARN_NAME => $barn->name,
						BARN_STALLS => getBarnStalls($barn->ownStall));
	}
	return $barns;
}

function getEventDivisions($divisionIds) {
	if ($divisionIds == null) {
		return null;
	}
	
	$divisions = array();
	foreach($divisionIds as $key => $value) {
		$division = R::load(DIVISION, $value->id);
		
		if (!$division->id) {
			continue;
		}
		
		$divisions[] = array(ID => $division->id,
							DIVISION_NAME => $division->name,
							DIVISION_CLASSES => getDivisionClasses($division->ownClass));
	}
	return $divisions;
}

function getEventContacts($contactIds) {
	if ($contactIds == null) {
		return null;
	}
	
	$contacts = array();
	foreach ($contactIds as $key => $value) {
		$contact = R::load(CONTACT, $value->id);
		
		if (!$contact->id) {
			continue;
		}
		
		$contacts[] = array(ID => $contact->id,
							CONTACT_FIRST_NAME => $contact->firstname,
							CONTACT_LAST_NAME => $contact->lastname,
							CONTACT_EMAIL => $contact->email,
							CONTACT_PHONE => $contact->phone,
							CONTACT_OCCUPATION => getOccupationInfo($contact->occupation_id));
	}
	return $contacts;
}

?>