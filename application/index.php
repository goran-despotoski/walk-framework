<?php
if($_SERVER["DOCUMENT_ROOT"] != dirname(__FILE__)."/")
	$appSubFolder = basename(__DIR__)."/";

$appPath = dirname(__FILE__)."/";
$corePath = $appPath . "../core/";

require_once $corePath . 'base/error_handler.php';
require_once $corePath . 'base/GlobalRegistry.php';

$global = GlobalRegistry::getInstance();

require_once $appPath . "env/env.inc.php";
require_once $corePath . "env/env.inc.php";
require_once $appPath . "env/db.inc.php";

require_once $corePath . 'bootstrap.php';
?>