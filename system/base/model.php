<?php
class Model extends Dao
{
	protected $db;
	private $table_name;
	private $sql = "";
	
	protected $page_count;
	
	public function __construct($table_name)
	{
		$this->table_name = $table_name;
		parent::__construct();
	}
	
	protected function getLimitStart($count_sql,$params, $page, $page_size) 
	{
		if((int)$page < 1) $page = 1;
		
		$result = $this->retrieve($count_sql,$params);
		$row = $result->getRow();

		$this->page_count = (int) ceil($row['cnt'] / $page_size);
		$limit = ((int)$page-1) * $page_size;
		
		$this->global->pages = $this->page_count;
		$this->global->current_page = $page;
		return (int)$limit;
	}

// 	@TODO DO THESE BELONG HERE?  
// 	single row operations 
	/**
	 * 
	 * Gets row by id(or whatever the key column's name is by changing $id_column value to corresponding column name)
	 * @param int $id
	 * @param string $id_column
	 * return DataAccessResult
	 * @author Goran Despotoski
	 */
	public function getById($id, $id_column="id")
	{
		$this->sql = "SELECT * FROM `".$this->table_name . "` WHERE `" . $id_column."`= :" . $id_column;
		$params = array(
				array("column"=>$id_column,
						"value"=>$id)
				);
		return parent::retrieve($this->sql, $params);
	}
	
	/**
	 * 
	 * Updates table if $key[1] is > 0 and if $key[1] == 0 inserts a new row 
	 * @param array $key Structure $key = array($key_name, $key_value) 
	 * @param array $fields
	 * @return DataAccessResult|false
	 * @author Goran Despotoski
	 */
	public function save($key = array("id",0), $fields = array())
	{
		$this->sql = "";
		if($key[1] == 0)
			$this->sql .= "INSERT INTO " . $this->table_name ." SET ";
		else
			$this->sql .= "UPDATE " . $this->table_name ." SET ";
		
		$params = array();
		$i = 0;
		foreach($fields as $v)
		{
			if(!isset($v["type"]))
			{
				$this->sql .= "`" . $v["column"] . "` = :" . $v["column"] . ",";
				$params[$i]["column"] = $v["column"];
				$params[$i]["value"] = $v["value"];
			}else{
					$this->sql .= "`" . $v["column"] . "` = " . $v["value"] . ",";
			}
			$i++;
		}
		$this->sql  = rtrim($this->sql,", ");
		
		if($key[1] != 0)
		{
			$this->sql .= " WHERE ".$key[0] ." = :".$key[0] ."";
			$params[$i]["column"] = $key[0];
			$params[$i]["value"] = $key[1];
		}
		return parent::update($this->sql, $params);
	}
	
	/**
	 * 
	 * Delete rows by id(you can delete multiple if you change $column to other value based on your table layout (1:m relation))
	 * @param int $id
	 * @param string $column
	 * @return DataAccessResult 
	 * @author Goran Despotoski
	 */
	public function deleteById($id = 0,$column = "id")
	{
		$this->sql = "DELETE FROM `".$this->table_name."` WHERE ". $column ." = :".$column;
		$params = array(array("column"=>$column,"value"=>$id));
		return parent::update($this->sql, $params);
	}
	
	//collections' operations
	/**
	 * 
	 * Gets all rows in table
	 * @param string $select_columns comma separated string injected in select part of query
	 * @author Goran Despotoski
	 */
	public function getAll($select_columns = "*")
	{
		$this->sql = "SELECT ".$select_columns." FROM `".$this->table_name."`";
		return parent::retrieve($this->sql);
	}
	
	
	/**
	 * 
	 * Gets all rows filtered
	 * 
	 * $filters[row] = array(	"column"=>"which column",
	 * 							"operation"=>"value like = < <>", 
	 * 							"value"=>"compare to what value")
	 * 
	 * @param array $filters
	 * @param string $select_columns
	 * @return Ambigous <boolean, DataAccessResult>
	 * @author Goran Despotoski
	 */
	public function getAllFiltered($filters = array(), $select_columns = "*")
	{
		$this->sql = "SELECT ".$select_columns." FROM `".$this->table_name."`";
		$tmp = "";
		$i = 0;
		foreach($filters as $v)
		{
			$tmp .= "`" . $v["column"] . "` " . $v["operation"] . " " . ":".$v["column"]." AND ";
		}
		
		if($tmp != "")
		{
			$tmp = substr($tmp, 0, -4);
			$this->sql .= "WHERE " . $tmp;
		}
		return parent::retrieve($this->sql, $filters);
	}
	
	public function getAllPaged($select_columns = "*", $page = 1, $col = 'id',$sort = 'ASC', $page_size = 1)
	{
		$count_sql = "SELECT COUNT(" . $col . ") AS cnt FROM `".$this->table_name . "`";
		
		$this->global->col = $col;
		$this->global->sort = $sort;
		$start = $this->getLimitStart($count_sql,array(), $page, $page_size);
		
		$this->sql = "SELECT ".$select_columns." FROM `".$this->table_name . "` 
				ORDER BY ". $col ." ". $sort ."
				 LIMIT ". $start . ", ". $page_size;
		return parent::retrieve($this->sql);
	}
	
	public function getAllPagedFiltered($filters = array(), $select_columns="*", $page = 1, $col = 'id',$sort = 'ASC', $page_size = 1)
	{
		$this->sql ="";
		$tmp = "";
		$params = array();
		$i = 0;
		foreach($filters as $v)
		{
			$tmp .= " `".$v["column"] . "` ".$v["operation"]." " . ":".$v["column"]." AND";
			$params[$i]["column"] = $v["column"];
			$params[$i]["value"] = $v["value"];
		}
		
		$count_sql = "SELECT COUNT(" . $col . ") AS cnt FROM `".$this->table_name . "` ";
		if($tmp != "")
		{
			$tmp = substr($tmp, 0, -4);
			$count_sql .= "WHERE " . $tmp;
		}
	
		$this->global->col = $col;
		$this->global->sort = $sort;
		$start = $this->getLimitStart($count_sql, $params, $page, $page_size);
	
		$this->sql = "SELECT ".$select_columns." FROM `".$this->table_name . "`
		". (($tmp != "")?"WHERE ":"") . $tmp ."
		ORDER BY ". $col ." ". $sort ."
		LIMIT ". $start . ", ". $page_size;

		return parent::retrieve($this->sql,$params);
	}
	
	public function getLastId()
	{
		return parent::getLastId();
	}
}