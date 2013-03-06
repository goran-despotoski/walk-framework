<?php
/**
 * Gets the value of the POST variable with index key $field
 * @param string $field
 * @author Goran Despotoski
 */
function _post($varname, $type, $default)
{
	switch($type)
	{
		case "int":
			if(!isset($default))$default = 0;
			return (isset($_POST[$varname]) && (int)$_POST[$varname]>0) ? (int)$_POST[$varname] : $default;
			break;
		case "string":
			if(!isset($default))$default = "";
			if(get_magic_quotes_gpc())
				return (isset($_POST[$varname])) ? trim(stripslashes($_POST[$varname])) : $default;
			else
				return (isset($_POST[$varname])) ? trim($_POST[$varname]) : $default;
			break;
		case "array":
			if(!isset($default))$default = array();
			return (isset($_POST[$varname]) &&  is_array($_POST[$varname]) ? $_POST[$varname] : $default);
			break;
		default:
			break;
	}
}

/**
 * Gets the value of the classic GET variable from query string after ? with index key $field
 * @param string $field
 * @author Goran Despotoski
 */
function _get($varname, $type, $default)
{
	switch($type)
	{
		case "int":
			if(!isset($default))$default = 0;
			return (isset($_GET[$varname]) && (int)$_GET[$varname]>0) ? (int)$_GET[$varname] : $default;
			break;
		case "signed":
			if(!isset($default))$default = 0;
			return (isset($_GET[$varname])) ? (int)$_GET[$varname] : $default;
			break;
		case "string":
			if(!isset($default))$default = "";
			return (isset($_GET[$varname])) ? $_GET[$varname] : $default;
			break;
		case "array":
			if(!isset($default))$default = array();
			return (isset($_GET[$varname]) &&  is_array($_GET[$varname]) ? $_GET[$varname] : $default);
			break;
		default:
			break;
	}
}

/**
 *
 * Checks if page is accessed throug POST method
 * @return bool
 * @author Goran Despotoski
 */
function _isPost()
{
	if(isset($_POST) && count($_POST) > 0)
		return true;
	return false;
}
?>