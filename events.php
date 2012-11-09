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

function createEvent($admin, $name, $startdate, $enddate) {
	$event = R::dispense(EVENT);
	$event->aid = $admin;
	$event->name = $name;
	$event->startdate = $startdate;
	$event->enddate = $enddate;
	$event->sharedUser = [];
	$event->ownBarn = [];
	$event->ownContact = [];
	$event->ownDivision = [];

	return R::store($event);
}

function getEvent($eventId) {
	return R::load(EVENT, $eventId);
}

function getAllEvents() {
	return R::getAll('select * from '.EVENT);
}

?>