<?php

class Db 
{
	private $name;
	private $surname;
	private $email;
		
	//create connection
	function __construct()
	{
		include "db_config.php";
		$this->connection = new mysqli($servername, $username, $password, $db_name);
		if ($this->connection->connect_error) 
		{
			die("Connection failed: " . $this->connection->connect_error);
		}
	}

	//close connection
	public function __destruct()
	{
		$this->connection->close();
	}

	//clear queries
	private function clear($text)
	{
		$text = trim($text);
		$text = stripslashes($text);
		$text = htmlspecialchars($text);
		return $text;
	}

	public function select($sql)
	{
		$sql = $this->clear($sql);
		$result = $this->connection->query($sql);
		if($result->num_rows == 0)
		{
			exit("The table is empty");
		}
		return $result->fetch_all();
	}
	public function insert($tbl_name, $data)
	{
		echo "insert<br>";
		$name = $data['name'];
		$surname = $data['surname'];
		$email = $data['email'];
		$sql = "INSERT INTO `$tbl_name` (`name`, `surname`, `email`) VALUES ('$name','$surname','$email')";
		var_dump($sql);
		return $this->connection->query($sql);
	}

	public function update($tbl_name, $old_data, $new_data)
	{
		$old_name = $old_data['name'];
		$old_surname = $old_data['surname'];
		$old_email = $old_data['email'];
		$new_name = $new_data['name'];
		$new_surname = $new_data['surname'];
		$new_email = $new_data['email'];
		$sql = "UPDATE `$tbl_name` SET `name`='$new_name',`surname`='$new_surname',`email`='$new_email' 
				WHERE `name`='$old_name' AND `surname`='$old_surname' AND `email`='$old_email'";
		return $this->connection->query($sql);
	}
	public function delete($tbl_name, $data)
	{
		$name = $data['name'];
		$surname = $data['surname'];
		$email = $data['email'];
		$sql = "DELETE FROM `$tbl_name` WHERE `name`='$name' AND `surname`='$surname' AND `email`='$email'";
		return $this->connection->query($sql);
	}
}
