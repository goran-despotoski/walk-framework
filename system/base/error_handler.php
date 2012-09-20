<?php
function customError($errno, $errstr, $errfile,$errline,$errcontext)
{
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
	
	
	$error .= "<br />$errstr<br /><br />";
	
	
	$error .= "<b>In file:</b><br />".$errfile."<br /><br />";
	
	$error .= "<b>On Line:</b><br />".$errline."<br /><br />";
	$error .= "<b>Memory used:</b><br />".(int)(memory_get_usage() / 1024 )."kb<br />";
	
	$error .= "	</div>";
	$error .= "</div>";
	die($error);
}

set_error_handler("customError", E_ALL); 
?>