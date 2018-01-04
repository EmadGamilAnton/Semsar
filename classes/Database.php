<?php

class Database
{
	public $connect;

	public function __construct()
	{
		$dsn='mysql:host=localhost;dbname=id2635100_maskan_db';
		$user='id2635100_root';
		$pass='010101019m';
		$options= array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' );

		try
		{
			$this->connect = new PDO($dsn,$user,$pass,$options);
			$this->connect ->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function query($sql)
	{
		$stmt = $this->connect->prepare($sql);
		$stmt->execute();
		return $stmt;
	}
}
$database = new Database();
?>