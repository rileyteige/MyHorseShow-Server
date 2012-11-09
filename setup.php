<?php

require_once 'rb.php';
include_once 'users.php';
include_once 'events.php';
include_once 'barns.php';
include_once 'divisions.php';
include_once 'classes.php';
include_once 'helpers.php';

require 'Slim/Slim/Slim.php';
\Slim\Slim::registerAutoloader();

define('USER', 'user');
define('EVENT', 'event');
define('INVALID_LOGIN_CODE', '-1');

define('EMAIL_ADDR_PARAM', 'addr');
define('PASSWORD_PARAM', 'p');
define('FNAME_PARAM', 'f');
define('LNAME_PARAM', 'l');
define('USEF_ID_PARAM', 'u');

define('USER_ID', 'uid');
define('USER_FNAME', 'firstname');
define('USER_LNAME', 'lastname');
define('USER_EVENTS', 'events');

define('EVENT_ID', 'eid');
define('EVENT_NAME', 'name');
define('EVENT_START_DATE', 'sdate');
define('EVENT_END_DATE', 'edate');

define('ADMIN_ID', 'aid');

define('BARN', 'barn');
define('DIVISION', 'division');
define('SHOWCLASS', 'class');

R::setup('mysql:host=localhost;dbname=rb', 'root', '');
?>