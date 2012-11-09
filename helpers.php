<?php

function http_get_request_body() {
	return @file_get_contents('php://input');
}

?>