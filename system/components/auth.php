<?php

class auth 
{
	protected $field;
	protected $user_type;
	protected $name;
	protected $last_name;
	protected $data;
	public $logged_user_id;
	
	public $login_errors=array();
	
	public function __construct() 
	{
		
	}
	
	public function validateLogin($field,$password, $user_type)
	{
		if($field == "")$this->addError('email',$field, "Email полето е задолжително!");
			else $this->data['email'] = $field;
		if($password == "")$this->addError("password", $password, "Лозинката е задолжителна!");
		if(count($this->login_errors) > 0)return false;
		return true;
	}
	
	public function login($id,$user_type,$success_page,$failure_page)
	{
		if($id)
		{
			$_SESSION['ses_user_id'] = $id;
			$_SESSION['ses_user_type'] = $user_type;
			if($user_type == 'admin')
				$_SESSION['IsAuthorized'] = true;//za CKEditor-ot
			redirect($success_page);
		}
		else
		{
			redirect($failure_page);
		}
	}
	
	public function logout($user_type, $logout_page)
	{
		session_destroy();
		redirect($logout_page);
	}
	
	public function authorize($user_type,$failure_page)
	{
		if(!$this->checkAuth($user_type))
		{
			redirect($failure_page);
		}
	}
	
	public function authorize($user_type,$failure_page)
	{
		if(!$this->checkAuth($user_type))
		{
			$res = array("result"=>"logged_out");
			die(json_encode($res));
		}
	}
	
	public function checkAuth($user_type)
	{
		if(!isset($_SESSION['ses_user_id']) && (!isset($_SESSION['ses_user_type']) || $user_type != $_SESSION['ses_user_type']))
		{
			return false;
		}
		$this->logged_user_id = $_SESSION['ses_user_id'];
		return true;
	}
	
	protected function addError($field_name='',$field_value = '', $error_text)
	{
		$this->login_errors[$field_name] = $error_text;
	}
}