<?php

require('setup.php');

echo json_encode(R::getAll('select * from '.EVENT));

R::close();

?>