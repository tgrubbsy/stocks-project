<?php
require_once 'config.php';

class connection
{
	public static $PDO = null;

	public static function get()
	{
		try
		{
			$PDO = new PDO('mysql:host=' . Config::$db_server . ';dbname=' . Config::$db_name, Config::$db_user, Config::$db_pass);
			return $PDO;
		}
		catch(PDOException $e)
		{
			die($e->getMessage());
		}	
	}
}
?>