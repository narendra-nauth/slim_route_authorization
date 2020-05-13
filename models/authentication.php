<?php
	require_once('database.php');

    class apiAuthentication{
		public static function authenticeUser(){
			$db 	= DBHandler::getInstance();
			
			$req    = $db->prepare("
				SELECT 
					users.username,
					users.password
				FROM users 
				WHERE users.active = :active
			");
			$params = array(
				'active' 	=> '1'
			);
			
			if(!$req->execute($params)){
				echo "Failure";
			}
			$results = $req->fetchAll(PDO::FETCH_ASSOC);
			
			$users = [];
			foreach($results as $result){
				$users[$result['username']] = $result['password'];
			}
			
			return $users;
		}

		public static function getUserLevel($username){
			$db 	= DBHandler::getInstance();
			
			$req    = $db->prepare("
				SELECT 
					users.username,
					users.level
				FROM users 
				WHERE users.active = :active
				AND users.username = :username
			");
			$params = array(
				'active' 	=> '1',
				'username'	=> $username
			);

			if(!$req->execute($params)){
				echo "Failure";
			}
			$results = $req->fetch(PDO::FETCH_ASSOC);

			return $results;
		}
	}
?>
