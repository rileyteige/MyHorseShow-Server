<?php
require_once 'setup.php';

function createOccupation($name, $plural) {
	if ($name == null || $plural == null) {
		return null;
	}
	
	$occupation = R::dispense(OCCUPATION);
	$occupation->name = $name;
	$occupation->plural = $plural;
	return R::store($occupation);
}

function createContact($eventId, $firstname, $lastname, $email, $phone, $occupationId) {
	$event = R::load(EVENT, $eventId);
	if (!$event->id) {
		return null;
	}
	
	$occupation = R::load(OCCUPATION, $occupationId);

	$contact = R::dispense(CONTACT);
	$contact->firstname = $firstname;
	$contact->lastname = $lastname;
	$contact->email = $email;
	$contact->phone = $phone;
	$contact->occupation = $occupation->id ? $occupation : null;
	
	$event->ownContact[] = $contact;
	R::store($event);
	
	return R::store($contact);
}

function getOccupationInfo($occId) {
	if ($occId == null) {
		return null;
	}
	
	$occupation = R::load(OCCUPATION, $occId);
	if (!$occupation->id) {
		return null;
	}
	
	return array(ID => $occupation->id,
				OCCUPATION_NAME => $occupation->name,
				OCCUPATION_PLURAL => $occupation->plural);
}

?>