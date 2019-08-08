<?php
require_once dirname(__DIR__) . '/lib/init.php';

if (isset($_POST['editTrainee'])) {
    $hiddenID = $init->inject($_POST['hiddenID']);
    $trainee_id = $init->inject($_POST['trainee_id']);
    $fname = $init->inject($_POST['fname']);
    $mname = $init->inject($_POST['mname']);
    $lname = $init->inject($_POST['lname']);
    $gender = $init->inject($_POST['gender']);
    $school = $init->inject($_POST['school']);
    $course = $init->inject($_POST['course']);
    $office_id = $init->inject($_POST['office']);
    $supervisor = $init->inject($_POST['supervisor']);
    $schedule = $init->inject($_POST['schedule']);
    $required = $init->inject($_POST['required']);
    $json = array();
    if (empty($trainee_id) || empty($fname) || empty($lname) || empty($gender) || empty($school) || empty($course) || empty($office_id) || empty($supervisor) || empty($schedule) || empty($required)) {
        $json['bool'] = false;
        $json['message'] = '<b>Error! </b>Cannot leave empty field!';
        $json['error'] = $init->error();
    } else {
        $fullname = $fname . '%' . $mname . '%' . $lname;
        $sql = $init->query("UPDATE trainees SET trainee_id = '$trainee_id', name = '$fullname', gender = '$gender', school_id = '$school', course = '$course', office_id = '$office_id', supervisor = '$supervisor', schedule = '$schedule', hours_required = '$required' WHERE trainee_id = '$hiddenID'");
        if ($sql) {
            $audit = $init->audit('Updated trainee profile', $hiddenID);
            if ($audit) {
                $json['bool'] = true;
                $json['message'] = '<i class="fas fa-thumbs-up fa-lg fa-spin"></i> Successfully updated trainee profile!';
                $json['error'] = $init->error();
            } else {
                $json['bool'] = false;
                $json['message'] = '<b>Error! </b>Failed updating audit trails!';
                $json['error'] = $init->error();
            }
        } else {
            $json['bool'] = false;
            $json['message'] = '<b>Error! </b>Failed updating trainee profile!';
            $json['error'] = $init->error();
        }
    }
    echo json_encode($json);
}
if (isset($_POST['removeIntern'])) {
    $id = $init->inject($_POST['id']);
    $json = array();
    $sql = $init->query("UPDATE trainees SET removed = 1 WHERE trainee_id = '$id'");
    if ($sql) {
        $audit = $init->audit('Removed intern', $id);
        if ($audit) {
            $json['bool'] = true;
            $json['message'] = '<i class="fas fa-thumbs-up fa-lg fa-spin"></i> Successfully removed trainee!';
            $json['error'] = $init->error();
        } else {
            $json['bool'] = false;
            $json['message'] = '<b>Error!</b> Failed updating audit trails!';
            $json['error'] = $init->error();
        }
    } else {
        $json['bool'] = false;
        $json['message'] = '<b>Error!</b> Failed removing intern!';
    }
    echo json_encode($json);
}
