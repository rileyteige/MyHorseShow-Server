<?php
require_once('setup.php');
require_once('userfunctions.php');

$email = $_GET[EMAIL_ADDR_PARAM];
$password = $_GET[PASSWORD_PARAM];

if ($email == null || $password == null)
	echo json_encode(invalidLogin());
else
	echo json_encode(getLoginInfo($email, $password));

R::close();
	
?>