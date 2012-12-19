<?php
/**
 * @package system\error-handling
 * 
 * 
 */
/**
 * Handles errors thrown by php apps (known)
 * @param int $errno
 * @param string $errstr
 * @param string $errfile
 * @param string $errline
 * @param unknown_type $errcontext
 */

function customError($errno, $errstr, $errfile,$errline,$errcontext)
{
	$global = GlobalRegistry::getInstance();
	$error = "<div style='width:900px;border:1px dotted grey; margin: 0 auto;'>";
	$error .= "	<div style=\"margin:10px;font-size:13px;font-family:'Courier New', Courier, monospace;\">";
	$error .= "<h2>Ooopss...</h2>";
	$error .= "Sorry there's been a mistake, our midgets are working overtime to fix the problem!<br /><br />";
	
	switch ($errno) {
    case E_USER_ERROR:
    	$error .= "<b>Error:</b>";    
        break;

    case E_USER_WARNING:
        $error .= "<b>Warning:</b>";
        break;

    case E_USER_NOTICE:
        $error .= "<b>Notice:</b>";
        break;
  	case E_NOTICE:
        $error .= "<b>Notice:</b>";
        break;
    case E_WARNING:
        $error .= "<b>Warning:</b>";
        break;    
    default:
        $error .= "<b>Unknown error type [$errno]:</b>";
        break;
    }
	
    $error .= "<br /><b>In file:</b><br />".$errfile."<br />";
    
    $error .= "<b>On Line:</b><br />".$errline."<br /><br />";
    
// 	$error .= "<pre>";
	$error .= "<br />".$errstr."<br /><br />";
// 	$error .= "</pre>";
	
	$error .= "<b>Memory used:</b><br />".(int)(memory_get_usage() / 1024 )."kb<br />";
	
	$error .= "	</div>";
	$error .= "</div>";
	
	if($global->environment == "development")
		die($error);
	else
		die("Died for some good oops error message :)");
}

set_error_handler("customError", E_ALL); 
?>