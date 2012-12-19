<?php

// use walkmvc\data\Model;

class tests_model extends Model
{
	public function __construct()
	{
		//you need to have tests table in database to use this with it
		parent::__construct("tests");
	}
	
	public function retrieveShowcase()
	{
		$params = array(array("column"=>":title","value"=>"yi%"));
		$sql = "SELECT *
				FROM tests WHERE title LIKE :title ;";
		return $this->retrieve($sql,$params);
	}
}