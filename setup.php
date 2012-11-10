<?php

require_once 'rb.php';
include_once 'users.php';
include_once 'events.php';
include_once 'barns.php';
include_once 'divisions.php';
include_once 'classes.php';
include_once 'helpers.php';
include_once 'stalls.php';

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

define('ID', 'id');

define('USER_ID', 'uid');
define('USER_FNAME', 'firstname');
define('USER_LNAME', 'lastname');
define('USER_EMAIL', 'email');
define('USER_EVENTS', 'events');
define('USER_USEF_ID', 'usefid');

define('EVENT_ID', 'eid');
define('EVENT_NAME', 'name');
define('EVENT_START_DATE', 'startdate');
define('EVENT_END_DATE', 'enddate');
define('EVENT_ADMIN', 'admin');
define('EVENT_BARNS', 'ownBarn');
define('EVENT_DIVISIONS', 'ownDivision');

define('ADMIN_ID', 'aid');

define('BARN', 'barn');
define('BARN_NAME', 'name');
define('BARN_STALLS', 'ownStall');

define('DIVISION', 'division');
define('DIVISION_NAME', 'name');
define('DIVISION_CLASSES', 'ownClass');

define('SHOWCLASS', 'class');
define('CLASS_NAME', 'name');
define('CLASS_START_TIME', 'starttime');
define('CLASS_PARTICIPANTS', 'sharedUser');

define('STALL', 'stall');
define('STALL_NAME', 'name');
define('STALL_OCCUPANT', 'occupant');

R::setup('mysql:host=localhost;dbname=myhorseshow', 'root', '');
?>