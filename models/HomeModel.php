<?php

class Home extends init{
	public function getInternSummary(){
		return $this->getQuery("
			SELECT *
			FROM trainees JOIN offices ON trainees.office_id = offices.office_id
			JOIN school ON school.school_id = trainees.school_id
			WHERE trainees.removed = 0");
	}

	public function myProfile(){
		$json = [];

		$sql = $this->getQuery("
				SELECT *
				FROM users u
				JOIN offices o ON u.office_id = o.office_id
				WHERE u.user_id = {$_SESSION['user_id']}
			");

		if(!empty($sql)){
			$json['name'] = fullname($sql[0]->name);
			$json['position'] = $sql[0]->position;
			$json['office'] = $sql[0]->office;
			$json['office_id'] = $sql[0]->office_id;
			$json['username'] = $sql[0]->username;

			$explode = explode('%', $sql[0]->name);

			$json['fname'] = $explode[0];
			$json['mname'] = $explode[1];
			$json['lname'] = end($explode);
		}
		return $json;
	}

	public function getHoursEarned($id){
		$sql = $this->getQuery("SELECT sum(total) total FROM dtr WHERE trainee_id = '$id'");

		$data = 0;

		if($sql[0]){
			$data = floor($sql[0]->total / 60);
		}

		return $data;
	}

	public function getColleges(){
		return $this->getQuery("SELECT * FROM school WHERE removed = 0");
	}

	public function getOffices(){
		return $this->getQuery("SELECT * FROM offices WHERE removed = 0");
	}
}