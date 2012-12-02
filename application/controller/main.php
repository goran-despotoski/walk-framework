<?php
// use walkmvc\request\Controller;
/**
 * 
 * @property tests_model $tests_model
 * @author goran
 *
 */
class main extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load_model("tests_model");
	}
	
	public function index()
	{
		$this->data["res"] = $this->tests_model->getAll();
		echo "Hello World from controller, you can now 'walk' with me through the view:<br />";
		//the second and third parameters are not required and are to include top and bottom files  
		$this->load_view(array("test","test1"), "top","bottom");  
	}
	
	public function parameters_test($a = 0,$b = 0)
	{
		echo "Change the first and second parameters ";
		echo "a=" . $a . " and b = " . $b;
	}
}
?>