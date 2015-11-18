<?php
session_start();
require_once('controller/webBookingController.php');
require_once('view/layoutView.php');

$WBController = new webBookingController();

$view =$WBController->handleCrawling();

$htmlView = new layoutView();
$htmlView->render($view);

// Music group?
//https://en.wikipedia.org/wiki/Peter,_Paul_and_Mary





