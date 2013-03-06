<?php
/**
 * @package system\request-handling
 *  
 */

/**
 * Dispatches all the requests to controllers and actions
 * Returns an error message to the user if the controller or action don't exist
 * @package system\request-handling 
 * @author Goran Despotoski
 *
 */
class Dispatcher
{
	public function __construct()
	{
		$global = GlobalRegistry::getInstance();
		$uri = (isset($_GET["uri"]) && trim($_GET["uri"]) != "")?$_GET["uri"]:$global->defaultController;
		if($uri != "")
		{
			$uri_chunks = explode('/',$uri); //get chunks from uri, traverse through them and find the 
			
			$tmp = "";
			$controller_file = "main";
			$action = "";
			$found_controller_file = false;
			$controller_class = ""; 
			$iterated_uri_chunks = array();
			$i = 0;
			foreach ($uri_chunks as $v)
			{
				$i++;
				$tmp .= $v . "/";
				$controller_file = rtrim($tmp,"/");
				$controller_class = $v;
				$iterated_uri_chunks[] = $v; 
				
				if(is_file($global->controllerPath . $controller_file . ".php"))
				{
					$found_controller_file = true;
					break;
				}
			}
			
			$rebuilt_uri_arr_without_controller = array_diff($uri_chunks, $iterated_uri_chunks);
			$action = (isset($rebuilt_uri_arr_without_controller[$i]) && $rebuilt_uri_arr_without_controller[$i] != "") ? $rebuilt_uri_arr_without_controller[$i] : $global->defaultAction; //get the action from the uri, if it doesn't  exist get the default
			
			if(isset($rebuilt_uri_arr_without_controller[$i]))unset($rebuilt_uri_arr_without_controller[$i]); //remove the action parameter to prepare for the params part for the uri
			$params = $rebuilt_uri_arr_without_controller;
			
			if(!$found_controller_file) //we didn't find controller file specified, try default controller in this folder
			{
				if(is_file($global->controllerPath . $controller_file . "/". $global->defaultController .".php"))
				{
					$controller_file .= "/" . $global->defaultController;
					$controller_class = $global->defaultController;
					$found_controller_file = true;
				}
			}
			
			if(!$found_controller_file) //we didn't find any controller file, trigger an error 
			{
				trigger_error("Controller file ". $global->controllerPath .  $controller_file ." not found", E_USER_NOTICE);
			}else{
				include $global->controllerPath . $controller_file . ".php";
				if(class_exists($controller_class))
				{
					$controller_instance = new $controller_class();
					if(method_exists($controller_instance,$action))
					{
						$global->controller = $controller_file;
						$global->action = $action;
						call_user_func_array(array($controller_instance, $action),$params);
					}else{
						trigger_error("Action " . $action . " not found", E_USER_NOTICE);
					}
				}
				else
					trigger_error("Controller class " . $controller_class . " was not found", E_USER_NOTICE);
			}
		}
	}
}