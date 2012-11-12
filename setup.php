<?php

require_once 'rb.php';
require_once 'globals.php';
include_once 'users.php';
include_once 'events.php';
include_once 'barns.php';
include_once 'divisions.php';
include_once 'classes.php';
include_once 'helpers.php';
include_once 'stalls.php';
require_once 'Slim/Slim/Slim.php';

\Slim\Slim::registerAutoloader();
R::setup('mysql:host=localhost;dbname=myhorseshow', 'root', '');
?>