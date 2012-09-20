<?php
class main extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		echo "Hello World, you can now 'walk' with me!";
	}
}
?>