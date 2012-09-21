<?php
/**
 * @package system\data-handling
 */
// namespace walkmvc\data;
/**
 * GlobalRegistry class is used for storage of global variables in order to prevent interruptions in the global 
 * variable workspace and use its attributes in functions and classes easily 
 * 
 * @property string $siteUrl
 * @property string $fileSystemPath
 * @property string $defaultController
 * @property string $defaultAction
 * @property string $userFriendlyUrls
 * @property string $md4Salt
 * @property array $db
 * @property array $viewData
 * @property array $libraries
 * 
 * @package system\data-handling
 * @author Goran Despotoski
 *
 */
class GlobalRegistry
{
	static $_instance;
	/**
	 * Empty constructor: this is due to the fact that it is a singleton object so no objects are created
	 */
	private function __construct(){}
	/**
	 * Clone function is empty so that an object can't be cloned.
	 */
	private function __clone(){}
	
	/**
	 * Function for getting an instance of the global registry variable
	 */
	public static function getInstance()
	{
		if(! (self::$_instance instanceof self))
		{
			self::$_instance=new self();
		}
		return self::$_instance;
	}
	
	/**
	 * Magic getter for GlobalRegistry attributes
	 * @param $key
	 */
	public function __get($key)
	{
		return $this->$key;
	}
	/**
	 * Magic setter for GlobalRegistry attributes
	 * @param unknown_type $key
	 * @param unknown_type $value
	 */
	public function __set($key,$value)
	{
		$this->$key=$value;
	}
}
?>