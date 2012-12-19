<?php
/**
 * @package system\data-handling
 */
// namespace walkmvc\data;

// use \PDO;
// use \Exception;
/**
 * Data Access Object plus
 * @property PDO $db
 * @package system\data-handling
 * @author Goran Despotoski
 *
 */
class DataAccess
{
	private $db;
	
	public function __construct($host, $user, $pass, $db)
	{
// 		$this->db = mysql_connect($host, $user, $pass);
// 		mysql_select_db($db,$this->db);
// 		mysql_query('SET NAMES utf8');

// 		$this->db = new mysqli($host, $user, $pass, $db);
// 		$this->db->query('SET NAMES utf8');
// 		if($this->db->connect_errno)
// 			trigger_error($this->db->error);
		try {
			$this->db = new PDO("mysql:dbname=".$db.";host=" . $host, $user, $pass,
    			array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		}catch (Exception $e)
		{
			trigger_error($e);
		}
		
	}
	
	/**
	 * 
	 * Returns result from a given sql query with given parameters
	 *  
	 * @param string $sql Sql query
	 * @param array $params 
	 * @throws Exception If there is an error with db connection, error is shown and page is aborted
	 * @return \walkmvc\data\DataAccessResult
	 */
	public function & fetch($sql, $params = array())
	{
		try{
			$stmt = $this->db->prepare($sql);
			foreach($params as $v)
				$stmt->bindParam($v["column"], $v["value"]);
			
			if(!$stmt->execute()) throw new Exception("Sql Error:<b>" . $stmt->errorCode() ."</b><br />");
		}
		catch (Exception $e)
		{
			$global = GlobalRegistry::getInstance();
			$str = "<b>Sql error:</b> " . $stmt->errorCode();
			$str .="<br /><b>Query string</b>: " . $stmt->queryString. "<br />";
			//var_dump();
			$str .="<br /><b>Query params</b>: <br />";
			foreach($params as $v)
				$str .= "&nbsp;&nbsp;" . $v["column"] . ": ~" . $v["value"] . "~ <br />";
			$str .= "<br />";
// 			$str .= "<b>Exception that occured</b>: <pre>".$e ."</pre>";
			$trace = debug_backtrace();
			$errfile = $trace[1]["file"];
			$errline = $trace[1]["line"];
			customError(E_USER_ERROR, $str, $errfile, $errline, '');
		}
		$dar = new DataAccessResult($this, $stmt);
		return $dar;
	}
	
	/**
	 * Gets the last insert id after inserting operation
	 */
	public function getLastId()
	{
		return $this->db->lastInsertId();
	}
	
	/**
	 * Gets the error code
	 */
	public function isError()
	{
		return $this->db->errorCode();
	}
}

/**
 * 
 * Provides interface for most common methods for result manipulation
 * @property PDOStatement $res
 * @property DataAccess $da
 * @author Goran Despotoski
 *
 */
class DataAccessResult
{
	private $da;
	private $res;
	
	public function __construct(& $da, $res)
	{
		$this->da = & $da;
		$this->res = $res;
	} 
	
	/**
	 * Gets a new row from the result set or returns
	 * @return array|false Returns result row on true, and false when there are no rows found
	 * @author Goran Despotoski
	 */
	public function getRow()
	{
		if($row = $this->res->fetch(PDO::FETCH_ASSOC))
			return $row;
		else return false;
	}
	
	/**
	 * Gets the first element of the first row
	 * @return string|false Returns first element from result row or false when there are no rows found
	 * @author Goran Despotoski
	 */
	public function getVar()
	{
		if($row = $this->res->fetch())
			return $row[0];
		else return false;
	}
	
	/**
	 * Resets the result set to first row
	 * @author Goran Despotoski 
	 */
	public function reset()
	{
		$this->res->closeCursor();
		$this->res->execute();
	}
	
	/**
	 * Gets the row count of result set
	 * @return number Row count of result set
	 * @author Goran Despotoski
	 */
	public function rowCount(){
		return $this->res->rowCount();
	}
	
	/**
	 * Checks if query returned an error and returns an error, otherwise returns false
	 * @return string|false Returns string for error code or false if no error code was found
	 * @author Goran Despotoski
	 */
	public function isError()
	{
		$error = $this->da->isError();
		if((int)$error != 0)
			return $error;
		else return false;
	}
}

/**
 * 
 * Provides basic methods for executing update/insert commands and retrieving from select commands
 * @author Goran Despotoski
 *
 */
class Dao
{
	private $da;
	
	protected $global;
	
	public function __construct()
	{
		$this->global = GlobalRegistry::getInstance();
		$this->DataAccessFactory(); 
	}
	/**
	 * 
	 * Executes query and returns DataAccessResult
	 * @param string $sql
	 * @param array $params
	 * @return boolean|DataAccessResult
	 * @author Goran Despotoski
	 */
	protected function & retrieve ($sql,$params=array()) 
	{
        $result=& $this->da->fetch($sql,$params);
        $error = $result->isError();
        if ($error) 
        {
            trigger_error($error);
            return false;
        } 
        else 
        {
            return $result;
        }
    }
    
    protected function update ($sql, $params) 
    {
        $result=$this->da->fetch($sql, $params);
        if ($error = $result->isError()) 
        {
            trigger_error($error);
            return false;
        } else {
            return $result;
        }
    }
    
    protected function getLastId()
    {
    	 return $this->da->getLastId();
    }
    
    private function DataAccessFactory()
    {
		$da = new DataAccess($this->global->db['host'],$this->global->db['user'],$this->global->db['password'],$this->global->db['database']);
		$this->da = $da;
// 		var_dump($this->da);
    }
}




?>