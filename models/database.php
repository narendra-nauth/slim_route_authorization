<?php 
	class DBHandler{
		private static $host      = "localhost";
		private static $dbname    = "tregon_slim";
		private static $username  = "root";
		private static $password  = "";
		
		private static $instance  = NULL;
		
		private function __construct(){}
		private function __clone(){}
		
		public static function getInstance(){
			if(!isset(self::$instance)){
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				self::$instance 				= new PDO('mysql:host='.self::$host.';dbname='.self::$dbname, self::$username, self::$password, $pdo_options);
			}
			return self::$instance;
		}
	}
?>