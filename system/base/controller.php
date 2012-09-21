<?php
/**
 * @package system\request-handling
 */
// namespace walkmvc\request;

// use walkmvc\data\GlobalRegistry;
/**
 * Controller class
 * @property GlobalRegistry $global
 * @property array $post;
 * @property array $data;
 * @property array $input_errors;
 * @property string $message;
 * @property string $message_type;
 * 
 * @package system\request-handling
 * @author Goran Despotoski
 *
 */
class Controller
{
	protected $global;
	protected $post;
	protected $data = array();
	protected $message; 
	protected $message_type;
	protected $components;

	/**
	 * Initialize the controller, get the global registry. Set default title. Load a library.
	 * @author Goran Despotoski 
	 */
	public function __construct()
	{
		$this->message = '';
		$this->message_type = '';
		$this->global = GlobalRegistry::getInstance();
		$this->post = $_POST;
		$this->data['site_title'] = $this->global->viewData['site_title'];
		$this->data['meta_description'] = $this->global->viewData['meta_description'];
		
		if(count($this->global->helpers) > 0)
		{
			foreach ($this->global->helpers as $k=>$v)
			{
				$this->load_helper($v); 
			}
		}
	}

	/**
	 * Loads a model with $model name, after which it is invoked like this: $this->$model->method()
	 * @param string $model
	 * @author Goran Despotoski
	 */
	protected function load_model($model)
	{
		if(is_file( $this->global->modelPath.$model.".php"))
		{
			include_once($this->global->modelPath. $model.".php");
			$model_instance = new $model();
			$this->$model = $model_instance;
		}
		else 
		{
			trigger_error("Model file ".$this->global->modelPath . $model.".php does not exist!",E_USER_WARNING);
		}
	}

	/**
	 * Loads a view into controller with array $data full of data that is needed in the view
	 * @param string $view view that is to be called
	 * @param string $top top file that is to be called if there is common top or bottom
	 * @param string $bottom bottom file that is to be called if there is common top or bottom
	 * @author Goran Despotoski
	 */
	protected function load_view($view , $top='' , $bottom='')
	{
		extract($this->data);
		empty($this->data);
		if($top != '')
		if(file_exists( $this->global->viewPath . $top.".php"))
			include_once($this->global->viewPath. $top.".php");
		else trigger_error("View top file '".$this->global->viewPath. $top.".php' does not exist!",E_USER_WARNING);
		
		if($this->global->under_construction)
		{
			include_once($this->global->viewPath."/under_construction.php");
		}
		elseif (file_exists( $this->global->viewPath . $view.".php"))
		{
			include_once($this->global->viewPath. $view.".php");
		}
		else 
		{
			trigger_error("View file ".$this->global->viewPath . $view.".php does not exist!",E_USER_WARNING);
		}
		
		if($bottom != '')
		if(file_exists( $this->global->viewPath . $bottom.".php") )
			include_once($this->global->viewPath . $bottom.".php");
		else 
			trigger_error("View bottom file '".$this->global->viewPath . $bottom.".php' does not exist!",E_USER_WARNING);
	}
	
	/**
	 * 
	 * Loads helper for using
	 * @param $helper
	 * @author Goran Despotoski
	 */
	protected function load_helper($helper)
	{
		if(file_exists( $this->global->fileSystemPath . "system/helpers/".$helper.".php") )
		{
			include_once($this->global->fileSystemPath . "system/helpers/".$helper.".php");
		}
		else trigger_error("Helper file '".$this->global->fileSystemPath . "system/helper/".$helper.".php' does not exist!", E_USER_WARNING);
	}
	
	protected function load_component($component, $type = "object")
	{
		if(file_exists( $this->global->fileSystemPath . "system/components/".$component.".php") )
		{
			include_once($this->global->fileSystemPath . "system/components/".$component.".php");
			if($type == "object")
				$this->$component = new $component();
		}
		else 
			trigger_error("Component file '".$this->global->fileSystemPath . "system/components/".$component.".php' does not exist!", E_USER_WARNING);
	}
	
	/**
	 *
	 * Loads library for using class in the library
	 * If library is in subfolder, include the local path from libraries  
	 * Example call: $this->load_library("auth/auth.php");
	 * calling the class would be: $obj = new auth();
	 * @param $library
	 * @author Goran Despotoski
	 */
	protected function load_library($library)
	{
		if(file_exists( $this->global->fileSystemPath . "system/libraries/".$library.".php") )
		{
			include_once($this->global->fileSystemPath . "system/libraries/".$library.".php");
		}
		else trigger_error("Library file '".$this->global->fileSystemPath . "system/libraries/".$library.".php' does not exist!", E_USER_WARNING);
	}
	
	/**
	 * Gets the value of the POST variable with index key $field
	 * @param string $field
	 * @author Goran Despotoski
	*/
	protected function _post($varname, $type, $default)
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
	protected function _get($varname, $type, $default)
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
	protected function _isPost()
	{
		if(isset($_POST) && count($_POST) > 0)
			return true;
		return false;
	}
}
?>