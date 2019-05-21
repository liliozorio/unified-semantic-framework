<?php
echo'<h1>INDEX</h1>';
include 'Controller/DefaultController.php';

$controller = new DefaultController();
$controller->index();