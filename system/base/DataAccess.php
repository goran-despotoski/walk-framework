<?php
/**
 * 
 * Enter description here ...
 * @property PDO $db
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
	
	public function & fetch($sql, $params = array())
	{
		try{
			$stmt = $this->db->prepare($sql);
			foreach($params as $v)
				$stmt->bindParam($v["column"], $v["value"]);
			
			if(!$stmt->execute()) throw new Exception("Sql Error:<b>" . $stmt->errorCode() ."</b><br />");
// 			echo $stmt->queryString . "<br />";
// 			echo $stmt->errorCode();
			
		}
		catch (Exception $e)
		{
			trigger_error($e);
		}
		$dar = new DataAccessResult($this, $stmt);
		return $dar;
	}
	
	public function getLastId()
	{
		return $this->db->lastInsertId();
	}
	
	public function isError()
	{
		return $this->db->errorCode();
	}
}

/**
 * 
 * Enter description here ...
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
	
	public function getRow()
	{
		
		if($row = $this->res->fetch(PDO::FETCH_ASSOC))
			return $row;
		else return false;
	}
	
	public function getVar()
	{
		if($row = $this->res->fetch())
			return $row[0];
		else return false;
	}
	
	public function reset()
	{
		if($this->res->rowCount() > 0)
			$this->res->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_FIRST);
	}
	
	public function rowCount(){
		return $this->res->rowCount();
	}
	
	public function isError()
	{
		$error = $this->da->isError();
		if((int)$error != 0)
			return $error;
		else return false;
	}
}

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