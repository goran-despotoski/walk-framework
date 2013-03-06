<?php
session_start();
date_default_timezone_set('Europe/Skopje');

require_once 'base/GlobalRegistry.php';

$global = GlobalRegistry::getInstance();

$global->appPath 		=	$appPath; //taken from the application index file
$global->corePath		=	$corePath; //taken from the application index file

require_once 'config/config.php'; //config uses GlobalRegistry so this should always be below includin GlobalRegistry.php
require_once 'base/DataAccess.php';
require_once 'base/controller.php';
require_once 'base/model.php';
require_once 'base/dispatcher.php';

$dispatcher_instance = new Dispatcher(); 
?>