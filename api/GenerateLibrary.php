<?php
header('Content-Type: application/json');

//allow up to half hour for this script to run
set_time_limit(1800);
require_once(dirname(__FILE__) . '/../code/Library.class.php');
require_once(dirname(__FILE__) . '/../code/Video.class.php');

$l = new Library();
$result = $l->generate();

echo json_encode($result);
