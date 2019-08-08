<?php
require_once dirname(__DIR__) . '/lib/init.php';
$home = new Home;
use Carbon\Carbon;

if(isset($_POST['load_intern_summary'])){
	$sql= $home->getInternSummary();

	foreach($sql as $data){
		$data->name = "<span class='d-block'>". fullname($data->name) ."</span><small class='text-muted'>". $data->trainee_id ."</small>";
		$data->school = acronym($data->school);
		$data->course = acronym($data->course);
		$data->office = acronym($data->office);
		$data->date_started = Carbon::createFromFormat('Y-m-d', $data->date_started)->format('M d, Y');
		$data->earned = $home->getHoursEarned($data->trainee_id);
		$data->remaining = $data->hours_required - $data->earned;
	}

	echo json_encode($sql);
}

if (isset($_POST['collegeChart']))
	{
	$sql = $init->getQuery("SELECT DISTINCT school, school_id FROM school WHERE removed = 0");
	$json = array();
	foreach($sql as $data)
		{
		$json['school'][] = acronym($data->school);
		$school_id = $data->school_id;
		$sql = $init->getQuery("SELECT count(*) count FROM trainees WHERE school_id = '$school_id'");
		foreach($sql as $data)
			{
			$json['count'][] = $data->count;
			}
		}

	echo json_encode($json);
	}

if (isset($_POST['officeChart']))
	{
	$sql = $init->getQuery("SELECT DISTINCT office, office_id FROM offices WHERE removed = 0");
	$json = array();
	foreach($sql as $data)
		{
		$json['office'][] = acronym($data->office);
		$office_id = $data->office_id;
		$sql = $init->getQuery("SELECT count(*) count FROM trainees WHERE office_id = '$office_id'");
		foreach($sql as $data)
			{
			$json['count'][] = $data->count;
			}
		}

	echo json_encode($json);
	}

if (isset($_POST['hiddenManageProfile']))
	{
	$fname = $init->inject($_POST['fname']);
	$mname = $init->inject($_POST['mname']);
	$lname = $init->inject($_POST['lname']);
	$office = $init->inject($_POST['office']);
	$position = $init->inject($_POST['position']);
	$username = $init->inject($_POST['username']);
	$json = array();
	if (empty($fname) || empty($mname) || empty($lname) || empty($office) || empty($position) || empty($username))
		{
		}
	  else
		{
		$fullname = $fname . '%' . $mname . '%' . $lname;
		$count = $init->count("SELECT * FROM users WHERE name = '$fullname' OR username = '$username'");
		if ($count > 1)
			{
			$json['bool'] = false;
			$json['message'] = "<b>Error!</b> Name or username is already taken!";
			$json['error'] = $init->error();
			}
		  else
			{
			$sql = $init->query("UPDATE users SET name = '$fullname', office_id = '$office', position = '$position', username = '$username' WHERE user_id = {$_SESSION['user_id']}");
			if ($sql)
				{
				$audit = $init->audit("Updated profile information", $_SESSION['user_id']);
				if ($audit)
					{
					$json['bool'] = true;
					$json['message'] = "<i class='fas fa-thumbs-up fa-lg fa-spin'></i> Successfully updated your profile!";
					$json['error'] = $init->error();
					}
				  else
					{
					$json['bool'] = false;
					$json['message'] = "<b>Error!</b> Failed updating audit trails!";
					$json['error'] = $init->error();
					}
				}
			  else
				{
				$json['bool'] = false;
				$json['message'] = "<b>Error!</b> Failed updating your profile!";
				$json['error'] = $init->error();
				}
			}
		}

	echo json_encode($json);
	}

if (isset($_POST['profile']))
	{
	$sql = $init->getQuery("SELECT * FROM users WHERE user_id = {$_SESSION['user_id']}");
	foreach($sql as $data)
		{
		$name = $data->name;
		$position = strtoupper($data->position);
		}

	$explode = explode('%', $name);
	$json['name'] = $explode[0] . ' ' . $explode[2];
	$json['position'] = $position;
	echo json_encode($json);
	}
