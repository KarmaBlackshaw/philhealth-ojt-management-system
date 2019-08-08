<?php

class User extends init{
	public function test(){
		return 'test';
	}

	public function getUsers($id = ''){
		$and = empty($id) ? '' : "AND users.user_id = $id";
		return $this->getQuery("
			SELECT * 
			FROM users 
			JOIN offices ON users.office_id = offices.office_id 
			WHERE users.user_id <> {$_SESSION['user_id']} 
			AND users.username <> 'admin'
			AND users.removed = 0
			$and
			");
	}
}