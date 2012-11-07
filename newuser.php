<?php

require_once('setup.php');

function verify_user($user) {
	return $user->email != null &&
		$user->firstname != null &&
		$user->lastname != null &&
		$user->password != null;
}

function getUserByEmail($email) {
	return R::findOne(USER, ' email = ? ', array ( $email ));
}

$email_address = $_GET[EMAIL_ADDR_PARAM];
$firstname = $_GET[FNAME_PARAM];
$lastname = $_GET[LNAME_PARAM];
$password = $_GET[PASSWORD_PARAM];
$usef_id = $_GET[USEF_ID_PARAM];

$user = R::dispense(USER);
$user->email = $email_address;
$user->firstname = $firstname;
$user->lastname = $lastname;
$user->password = $password;
$user->usefid = $usef_id;
$user->sharedEvent = [];

if (!verify_user($user))
	return;
	
if (getUserByEmail($email_address) != null)
	throw new Exception('Email already registered.');
	
$id = R::store($user);
$rb_user = R::load(USER, $id);

echo json_encode(array(USER_ID => $rb_user->id, USER_FNAME => $rb_user->firstname, USER_LNAME => $rb_user->lastname));

R::close();

?>
