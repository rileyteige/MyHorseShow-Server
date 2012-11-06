<?php

require('setup.php');

$codeword = $_GET['codeword'];

if ($codeword == 'blowitaway') {
	R::nuke();
	echo 'Success.';
}

?>