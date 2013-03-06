<?php
	function standardTime($time)
	{
		$global = GlobalRegistry::getInstance();
		return date($global->timeFormat,strtotime($time));
	}
?>