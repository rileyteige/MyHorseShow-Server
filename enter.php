<?php
require('setup.php');
function invalidLogin() {
	return array(USER_ID => INVALID_LOGIN_CODE, USER_FNAME => null, USER_LNAME => null);
}

function getLoginInfo($email, $password) {
	$user = R::findOne(USER, ' email = ? ', array ( $email ));
	if ($user == null || $password != $user->password)
		return invalidLogin();
		
	return array(USER_ID => $user->id, USER_FNAME => $user->firstname, USER_LNAME => $user->lastname);
}

$email = $_GET[EMAIL_ADDR_PARAM];
$password = $_GET[PASSWORD_PARAM];

if ($email == null || $password == null)
	echo json_encode(invalidLogin());
else
	echo json_encode(getLoginInfo($email, $password));

	R::close();
	
?>