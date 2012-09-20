<?php
session_start();
date_default_timezone_set('Europe/Skopje');
error_reporting(E_ALL);

require_once 'system/base/error_handler.php';
//configuration
require_once 'system/base/GlobalRegistry.php';
require_once 'application/config/config.php'; //config uses GlobalRegistry so this should always be below includin GlobalRegistry.php
//basic functionality
require_once 'system/base/DataAccess.php';
require_once 'system/base/controller.php';
require_once 'system/base/model.php';
require_once 'system/base/dispatcher.php';

$dispatcher_instance = new Dispatcher(); 
?>