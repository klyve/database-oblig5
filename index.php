<?php

require_once 'mvc/bootstrap.php';
// require_once 'app/model/Skiers.php';
require_once 'app/controller/XMLController.php';



$controller = new XMLController();
$controller->parseFile('SkierLogs.xml');
