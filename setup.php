<?php
require('rb.php');

define('USER', 'user');
define('INVALID_LOGIN_CODE', '-1');

define('EMAIL_ADDR_PARAM', 'addr');
define('PASSWORD_PARAM', 'p');
define('FNAME_PARAM', 'f');
define('LNAME_PARAM', 'l');
define('USEF_ID_PARAM', 'u');

define('USER_ID', 'id');
define('USER_FNAME', 'firstname');
define('USER_LNAME', 'lastname');

R::setup('mysql:host=localhost;dbname=rb', 'root', '');
?>