<?php

require_once(dirname(__FILE__) . '/../code/LibraryGenerator.class.php');

$videoSourceId = isset($_GET['videoSourceId']) ? $_GET['videoSourceId'] : null;
$path = isset($_GET['path']) ? $_GET['path'] : null;
LibraryGenerator::AddNewMediaItem($videoSourceId, $path);
?>