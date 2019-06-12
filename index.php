<?php
echo'<h1>INDEX</h1>';
include 'Controller/Controller.php';

$controller = new Controller();
$controller->index();