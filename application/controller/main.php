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
		echo "Hello World from controller, you can now 'walk' with me through the view:";
		$this->load_view("test");
	}
}
?>