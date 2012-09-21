<?php

// use walkmvc\data\Model;

class tests_model extends Model
{
	public function __construct()
	{
		//you need to have tests table in database to use this with
		parent::__construct("tests");
	}
}