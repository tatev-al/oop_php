<?php

class Db extends mysqli
{
	private $where_condition = '';
		
	//create connection
	function __construct()
	{
		include "db_config.php";
		parent::__construct($servername, $username, $password, $db_name);
		if (mysqli_connect_errno()) {
            die("Connection failed: " . mysqli_connect_error());
        }
	}

	//close connection
	public function __destruct()
	{
		parent::close();
	}

	//clear queries
	private function clear($text)
	{
		$text = trim($text);
		$text = stripslashes($text);
		$text = htmlspecialchars($text);
		$text = parent::real_escape_string($text);
		return $text;
	}

	public function select($sql, $all = true)
	{
		if($all != true)
		{
			return parent::query($sql)->fetch_assoc();
		}
		$array = [];
		$result = parent::query($sql);
		while($row = $result->fetch_assoc())
		{
			$array[] = $row;
		}
		return $array;
	}
	public function insert($tbl_name, $data)
	{
		$values = '';
		foreach($data as $key => $value) {  
			$values .= "'".$this->clear($value)."', ";  
		}
		$values = substr($values, 0, -2);
		$sql = parent::query("INSERT INTO $tbl_name (" . implode(",", array_keys($data)) . ") VALUES ($values)");
		return $sql;
	}

	public function update($tbl_name, $new_data)
	{
		$set_data = '';
		foreach($new_data as $key => $value) {  
			$set_data .= $key . "='".$this->clear($value)."', ";  
		}
		$set_data = substr($set_data, 0, -2);
		$sql = "UPDATE $tbl_name SET $set_data ".$this->where_condition."";
		$this->where_condition = '';
		return parent::query($sql);
	}

	public function delete($tbl_name)
	{
		$sql = "DELETE FROM `$tbl_name` ".$this->where_condition."";
		$this->where_condition = '';
		return parent::query($sql);
	}

	public function where($key, $value, $oper = '=')
	{
		$this->clear($value);
		if($this->where_condition != '')
		{
			$this->where_condition .= " AND $key $oper '$value'";
		}
		else
		{
			$this->where_condition = "WHERE $key $oper '$value'";
		}
		return $this;
	}

	public function orWhere($key, $value, $oper = '=')
	{
		$this->clear($value);
		if($this->where_condition != '')
		{
			$this->where_condition .= " OR $key $oper '$value'";
		}
		else
		{
			$this->where_condition = "WHERE $key $oper '$value'";
		}
		return $this;
	}
}