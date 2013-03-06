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
		if(!$this->global->under_construction)
		{
			if($top != '')
			if(file_exists( $this->global->viewPath . $top.".php"))
				include_once($this->global->viewPath. $top.".php");
			else trigger_error("View top file '".$this->global->viewPath. $top.".php' does not exist!",E_USER_WARNING);

			if(!is_array($view))
			{
				if (file_exists( $this->global->viewPath . $view.".php"))
				{
					include_once($this->global->viewPath. $view.".php");
				}
				else 
				{
					trigger_error("View file ".$this->global->viewPath . $view.".php does not exist!",E_USER_WARNING);
				}
			}else 
			{
				foreach($view as $views_v)
				{
					if (file_exists( $this->global->viewPath . $views_v . ".php"))
					{
						include_once($this->global->viewPath . $views_v . ".php");
					}
					else
					{
						trigger_error("View file ".$this->global->viewPath . $views_v . ".php does not exist!",E_USER_WARNING);
					}	
				}
			}
			
			if($bottom != '')
			if(file_exists( $this->global->viewPath . $bottom.".php") )
				include_once($this->global->viewPath . $bottom.".php");
			else 
				trigger_error("View bottom file '".$this->global->viewPath . $bottom.".php' does not exist!",E_USER_WARNING);
		} else {
			include_once($this->global->viewPath."under_construction.php");
		}
	}
	
	/**
	 * 
	 * Loads helper for using
	 * @param $helper
	 * @author Goran Despotoski
	 */
	protected function load_helper($helper)
	{
		if(file_exists( $this->global->corePath . "helpers/".$helper.".php") )
		{
			require_once($this->global->corePath . "helpers/".$helper.".php");
		}
		else trigger_error("Helper file '".$this->global->corePath . "helpers/".$helper.".php' does not exist!", E_USER_WARNING);
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
		if(file_exists( $this->global->corePath . "libraries/".$library.".php") )
			include_once($this->global->corePath. "libraries/".$library.".php");
		else trigger_error("Library file '".$this->global->corePath . "libraries/".$library.".php' does not exist!", E_USER_WARNING);
	}
}
?>