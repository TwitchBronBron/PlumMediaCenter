<?php

include("code/Page.class.php");
global $title;
$p = new Page(__FILE__);
$m = $p->getModel();
$m->title = "Plum Video Player";
$p->show();
?>