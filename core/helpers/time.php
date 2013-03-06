<?php
function standardTime($time)
{
	$global = GlobalRegistry::getInstance();
	return date($global->timeFormat,strtotime($time));
}

function standardDate($time)
{
	$global = GlobalRegistry::getInstance();
	return date($global->dateFormat,strtotime($time));
}
	
?>