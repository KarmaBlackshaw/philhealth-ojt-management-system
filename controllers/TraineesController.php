<?php
require_once dirname(__DIR__) . '/lib/init.php';
use Carbon\Carbon;
use Carbon\CarbonPeriod;

$trainee_id = $_SESSION['user_id'];

if(isset($_POST['loadTraineesTable'])){
    $intern = new Intern;
    $sql = $intern->getInterns();
    $now = Carbon::now()->format('Y-m-d');

    $json = [];

    foreach($sql as $data){
        $left = $data->hours_required - ($intern->getTotalTime($data->trainee_id) / 60);

        $data->formatted_name = "
            <a class='d-block' href='intern_information.php?id=$data->trainee_id'>" . fullname($data->name, 'LFM') . "</a>
            <small class='text-muted font-italic'>$data->trainee_id</small>
        ";
        $data->school = acronym($data->school);
        $data->course = acronym($data->course);
        $data->office = acronym($data->office);
        $data->schedule = $data->schedule;
        $data->hours_required = "$data->hours_required hrs.";
        $data->date_started = Carbon::createFromFormat('Y-m-d', $data->date_started)->format('F d, Y');
        $data->status = $left > 0 ? '<a href="javascript:" class="btn btn-primary btn-xs w-100 hvr-grow">On-Going</a>' : '<a href="javascript:" class="btn btn-success btn-xs w-100 hvr-grow">Finished</a>';
        $data->options = "
            <div class='btn-group'>
              <button type='button' class='btn btn-xs btn-outline-dark dropdown-toggle dropdown-toggle-split' data-toggle='dropdown'>
              </button>
              <div class='dropdown-menu'>
                <a class='dropdown-item small border-bottom' href='intern_dtr.php?id=$data->trainee_id'><i class='far fa-clock mr-1'></i> Manage DTR </a>
                <a class='dropdown-item small' target='_blank' href='intern_certificate.php?id=$data->trainee_id'><i class='fas fa-certificate mr-1'></i> Certify </a>
              </div>
            </div>
        ";
    }

    echo json_encode($sql);
}

if(isset($_POST['preview_edit_school'])){
    $intern = new Intern;

    $sql = $intern->getSchoolById($init->inject($_POST['preview_edit_school']));

    echo json_encode($sql);
}

if(isset($_POST['add_trainee'])){
    $id = $init->inject($_POST['id']);
    $fname = $init->inject($_POST['fname']);
    $mname = $init->inject($_POST['mname']);
    $lname = $init->inject($_POST['lname']);
    $school_id = $init->inject($_POST['school_id']);
    $gender = $init->inject($_POST['gender']);
    $course = $init->inject($_POST['course']);
    $office_id = $init->inject($_POST['office_id']);
    $supervisor = $init->inject($_POST['supervisor']);
    $date_started = $init->inject($_POST['date_started']);
    $schedule = $init->inject($_POST['schedule']);
    $required = $init->inject($_POST['required']);

    $fullname = $fname . '%' . $mname . '%' . $lname;

    $validate = validate($_POST);

    if(empty($validate)){
        $exists = $init->getQuery("SELECT COUNT(*) total FROM trainees WHERE trainee_id = '$id'")[0]->total;

        $estimated = Carbon::createFromFormat('Y-m-d', $date_started)->addHours(486 + (486 * 3.5))->format('F Y');

        $sql = $init->query("INSERT INTO trainees(trainee_id, name, gender, school_id, course, office_id, supervisor, date_started, schedule, hours_required, expected_month) VALUES('$id', '$fullname', '$gender', '$school_id', '$course', '$office_id', '$supervisor', '$date_started', '$schedule', '$required', '$estimated')");

        if($sql){
            $audit = $init->audit('Registered an intern', $id);
            $json['alert'] = 'success';
            $json['message'] = '<i class="fas fa-thumbs-up fa-lg fa-spin"></i> ' . $fname . ' ' . $lname . ' is successfully added to database!';
        }
    } else{
        $json['message'] = '<b>Error!</b> Fields cannot be left empty!';
    }

    $json['error'] = $init->error();

    echo json_encode($json);
}

if(isset($_POST['loadDTRTable'])){
    $intern = new Intern;

    // Request
    $month = $init->inject($_POST['month']);
    $year = $init->inject($_POST['year']);
    $trainee_id = $init->inject($_POST['trainee_id']);

    // Dates
    $dates = $intern->getDtrDates($year, $month);

    // Ifexist
    $exists = $intern->dtrExists($year, $month, $trainee_id);

    if($exists){
        $dates = $init->getQuery("SELECT * FROM dtr WHERE month(dtr_date) = '$month' AND year(dtr_date) = '$year' AND trainee_id = '$trainee_id' AND removed = 0");
    }

    foreach($dates as $data) :
        $raw_date = ($exists) ? $data->dtr_date : $data;
        $raw_date = Carbon::createFromFormat('Y-m-d', $raw_date);
        $min = ($exists) ? $data->morning_in : '';
        $mout = ($exists) ? $data->morning_out : '';
        $ain = ($exists) ? $data->afternoon_in : '';
        $aout = ($exists) ? $data->afternoon_out : '';

        ?>

        <tr class="small <?= $raw_date->isWeekend() ? 'bg-light' : ''; ?>" >
          <td class="text-center align-middle"><?= $raw_date->day; ?></td>
          <td class="text-center align-middle"><span id="day_<?= $raw_date->day; ?>"><?= $raw_date->englishDayOfWeek; ?></span></td>
          <td>
            <div class="input-group">
              <input type="number" class="form-control form-control-sm" min="7" max="12" id="morning_in_hr_<?= $raw_date->day ?>" oninput="total(<?= $raw_date->day; ?>)" name="morning_in_hr[]" value="<?= $exists ? Intern::getTime($data->morning_in, 'hr') : ''; ?>">
              <input type="number" class="form-control form-control-sm" min="0" max="59" id="morning_in_min_<?= $raw_date->day ?>" oninput="total(<?= $raw_date->day; ?>)" name="morning_in_min[]" value="<?= $exists ? Intern::getTime($data->morning_in, 'min') : ''; ?>">
            </div>
          </td>
          <td>
            <div class="input-group">
              <input type="number" class="form-control form-control-sm" min="7" max="12" id="morning_out_hr_<?= $raw_date->day ?>" oninput="total(<?= $raw_date->day; ?>)" name="morning_out_hr[]" value="<?= $exists ? Intern::getTime($data->morning_out, 'hr') : ''; ?>">
              <input type="number" class="form-control form-control-sm" min="0" max="59" id="morning_out_min_<?= $raw_date->day ?>" oninput="total(<?= $raw_date->day; ?>)" name="morning_out_min[]" value="<?= $exists ? Intern::getTime($data->morning_out, 'min') : ''; ?>">
            </div>
          </td>
          <td>
            <div class="input-group">
              <input type="number" class="form-control form-control-sm" min="1" max="9" id="afternoon_in_hr_<?= $raw_date->day ?>" oninput="total(<?= $raw_date->day; ?>)" name="afternoon_in_hr[]" value="<?= $exists ? Intern::getTime($data->afternoon_in, 'hr') : ''; ?>">
              <input type="number" class="form-control form-control-sm" min="0" max="59" id="afternoon_in_min_<?= $raw_date->day ?>" oninput="total(<?= $raw_date->day; ?>)" name="afternoon_in_min[]" value="<?= $exists ? Intern::getTime($data->afternoon_in, 'min') : ''; ?>">
            </div>
          </td>
          <td>
            <div class="input-group">
              <input type="number" class="form-control form-control-sm" min="1" max="9" id="afternoon_out_hr_<?= $raw_date->day ?>" oninput="total(<?= $raw_date->day; ?>)" name="afternoon_out_hr[]" value="<?= $exists ? Intern::getTime($data->afternoon_out, 'hr') : ''; ?>">
              <input type="number" class="form-control form-control-sm" min="0" max="59" id="afternoon_out_min_<?= $raw_date->day ?>" oninput="total(<?= $raw_date->day; ?>)" name="afternoon_out_min[]" value="<?= $exists ? Intern::getTime($data->afternoon_out, 'min') : ''; ?>">
            </div>
          </td>
          <input type="hidden" id="hidden_date_<?= $raw_date->day ?>" value="<?= $raw_date->format('Y-m-d'); ?>">
          <td class="text-center align-middle"><span id="total_<?= $raw_date->day ?>"><?= $exists ? Intern::toTimeString($data->total) : ''; ?></span></td>
          <td class="text-center align-middle" id="remarks_<?= $raw_date->day ?>">
            <?php if($exists) : ?>
                <span><?= $data->remarks == 'Absent' ? "<b class='text-danger'><i class='far fa-times-circle '></i> " . $data->remarks . "</b>" : $data->remarks; ?></span>
            <?php endif; ?>
          </td>
        </tr>

    <?php endforeach;
}

if(isset($_POST['get_total_time'])){
    $intern = new Intern;

    $date = $init->inject($_POST['date']);
    $min  = $init->inject($_POST['min']);
    $mout  = $init->inject($_POST['mout']);
    $ain  = $init->inject($_POST['ain']);
    $aout  = $init->inject($_POST['aout']);

    $json['alert'] = "danger";
    $json['message'] = "<b>Error!</b> Something went wrong! Please contact the administrator!";

    $sql = Intern::hasTimeError($min, $mout, $ain, $aout);

    if(Intern::hasTimeError($min, $mout, $ain, $aout)){
        $date_formatted = Carbon::createFromFormat('Y-m-d', $date)->format('l, M d, Y');
        $json['message'] = "<b>Error!</b> Time is invalid on <b>$date_formatted</b>!";
    } else{
        $json['alert'] = 'success';
        $json['total'] = Intern::convertOvertime($min, $mout, $ain, $aout, $date);
        $json['time'] = date('H:i', mktime(0,$json['total']));
    }

    $json['remarks'] = Intern::getRemarks($min, $mout, $ain, $aout, $date);

    echo json_encode($json);
}

if(isset($_POST['form_dtr'])){
    $intern = new Intern;

    $month = $init->inject($_POST['select_month']);
    $year = $init->inject($_POST['select_year']);

    $alert_date = Carbon::createFromFormat("Y-m-d", "$year-$month-01")->format('F Y');

    $trainee_id = $init->inject($_POST['trainee_id']);

    $morning_in_hr = $_POST['morning_in_hr'];
    $morning_in_min = $_POST['morning_in_min'];
    $morning_out_hr = $_POST['morning_out_hr'];
    $morning_out_min = $_POST['morning_out_min'];
    $afternoon_in_hr = $_POST['afternoon_in_hr'];
    $afternoon_in_min = $_POST['afternoon_in_min'];
    $afternoon_out_hr = $_POST['afternoon_out_hr'];
    $afternoon_out_min = $_POST['afternoon_out_min'];

    $json['alert'] = 'danger';
    $json['message'] = "<b>Error!</b> Something went wrong! Please contact the administrator!";

    $dtr_exists = $intern->dtr_exists($year, $month, $trainee_id);

    foreach ($morning_in_hr as $day => $value){
        $date_day = $day + 1;
        $date = "$year-$month-$date_day";

        $min = (int) Intern::toMinutes($morning_in_hr[$day], $morning_in_min[$day]);
        $mout = (int) Intern::toMinutes($morning_out_hr[$day], $morning_out_min[$day]);
        $ain = (int) Intern::toMinutes($afternoon_in_hr[$day], $afternoon_in_min[$day]);
        $aout = (int) Intern::toMinutes($afternoon_out_hr[$day], $afternoon_out_min[$day]);

        $total = 0;
        $remark = Intern::getRemarks($min, $mout, $ain, $aout, $date);

        $hasError = Intern::hasTimeError($min, $mout, $ain, $aout);

        $total = Intern::convertOvertime($min, $mout, $ain, $aout, $date);

        $left = $init->getQuery("
                SELECT t.hours_required - (sum(dtr.total) / 60) total
                FROM trainees t
                JOIN dtr ON t.trainee_id = dtr.trainee_id
                WHERE dtr.removed = 0
                AND t.trainee_id = '$trainee_id'
            ")[0]->total;

        if($left < 1){
            $finish_date = $init->getQuery("SELECT finished_date FROM trainees WHERE trainee_id = '$trainee_id'")[0]->finished_date;

            if($dtr_exists){
                $date_finished = Carbon::now()->format('Y-m-d');
                $sql = $init->query("UPDATE trainees SET finished_date = '$date_finished' WHERE trainee_id = '$trainee_id'");
            }
        } else{
            $sql = $init->query("UPDATE trainees SET finished_date = '0000-00-00' WHERE trainee_id = '$trainee_id'");
        }

        $minTime = $init->toTime($min);
        $moutTime = $init->toTime($mout);
        $ainTime = $init->toTime($ain);
        $aoutTime = $init->toTime($aout);

        if(!$dtr_exists){
            $sql = $init->query("INSERT INTO dtr (dtr_date, trainee_id, morning_in, morning_out, afternoon_in, afternoon_out, total, remarks) VALUES ('$date', '$trainee_id', '$minTime', '$moutTime', '$ainTime', '$aoutTime', '$total', '$remark')");

            if($sql){
                $json['alert'] = 'success';
                $json['message'] = "Successfully registered DTR for <b>$trainee_id</b> on <b>$alert_date</b>";
            }
        } else{
            $sql = $init->query("UPDATE dtr SET morning_in = '$minTime', morning_out = '$moutTime', afternoon_in = '$ainTime', afternoon_out = '$aoutTime', total = '$total', remarks = '$remark', removed = 0 WHERE dtr_date = '$date'  AND trainee_id = '$trainee_id'");

            if($sql){
                $json['alert'] = 'success';
                $json['message'] = "Successfully updated DTR for <b>$trainee_id</b> on <b>$alert_date</b>";
            }
        }
    }

    $json['error'] = $init->error();

    echo json_encode($json);
}

if(isset($_POST['load_table_dtr_summary'])){
    $intern = new Intern;
    $id = $init->inject($_POST['load_table_dtr_summary']);
    $sql = $intern->getDTRSummary($id);

    foreach($sql as $data){
        $data->total = $intern->toTimeString($data->minutes);
        $data->options = "
            <button class='btn btn-outline-danger btn-sm remove_dtr'
            data-month='$data->month'
            data-year='$data->year'
            data-trainee_id='$id'
            >
                <i class='fas fa-trash'></i>
            </button>
        ";
    }

    echo json_encode($sql);
}

if(isset($_POST['load_total_hours'])){
    $intern = new Intern;

    $id = $init->inject($_POST['load_total_hours']);

    $sql = $intern->getDTRSummary($id);

    $total = 0;
    foreach($sql as $data){
        $total += $data->minutes;
    }

    $json['total'] =$intern->toTimeString($total);

    echo json_encode($json);
}

// if(isset($_GET['id'])){
//     $trainee_id = $init->inject($_GET['id']);
//     $internName = "";
//     $sql = $init->getQuery("SELECT * FROM trainees WHERE trainee_id = '$trainee_id'");
//     foreach ($sql as $data){
//         $explode = explode('%', $data->name);
//         foreach ($explode as $data){
//             $internName.= $data . " ";
//         }
//     }
// }

if(isset($_POST['removeDTRDate'])){
    $month = $init->inject($_POST['month']);
    $year = $init->inject($_POST['year']);
    $trainee_id = $init->inject($_POST['trainee_id']);

    $json['alert'] = 'danger';
    $json['message'] = "<b>Error!</b> Something went wrong! Please contact the administrator!";

    $sql = $init->query("UPDATE dtr SET removed = 1 WHERE monthname(dtr_date) = '$month' AND year(dtr_date) = '$year' AND trainee_id = '$trainee_id'");

    if($sql){
        $audit = $init->audit("Removed DTR dated on " . date('F Y', strtotime('01-' . $month . '-' . $year)), $trainee_id);

        if($audit){
            $json['alert'] = 'success';
            $json['message'] = "<i class='fas fa-thumbs-up fa-lg fa-spin'></i> Successfully removed " . date('F Y', strtotime('01-' . $month . '-' . $year));
        }
    }

    $json['error'] = $init->error();

    echo json_encode($json);
}

// Holidays
if(isset($_POST['add_holiday'])){
    $holiday = $init->inject($_POST['addHolidayName']);
    $date = $init->inject($_POST['addHolidayDate']);

    $json['alert'] = "danger";
    $json['message'] = "<b>Error!</b> Something went wrong! Please contact the administrator!";

    $count = $init->getQuery("SELECT COUNT(*) x FROM holidays WHERE name = '$holiday' AND holidayDate = '$date'");

    if($count[0]->x > 0){
        $json['message'] = "<b>$holiday</b> already exists!";
    } else{
        $sql = $init->query("INSERT INTO holidays(name, holidayDate) VALUES ('$holiday', '$date')");

        if($sql){
            $id = $init->insert_id();

            $audit = $init->audit("Added new holiday", $id);
            $json['alert'] = "success";
            $json['message'] = "<i class='fas fa-thumbs-up fa-lg fa-spin'></i> Successfully registered a holiday!";
        }
    }

    $json['error'] = $init->error();
    echo json_encode($json);
}

if(isset($_POST['loadHolidayTable'])){
    $intern = new Intern;
    $sql = $intern->getHolidays();
    foreach ($sql as $data): ?>
      <tr>
        <td>
            <span class="d-block"><?= $data->name; ?></span>
            <small class="text-muted"><?= Carbon::createFromFormat('Y-m-d', $data->holidayDate)->format('M d, Y'); ?></small>
            </td>
        <td>
            <div class="btn-group">
              <button type="button" class="btn btn-xs btn-outline-dark dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="sr-only">Toggle Dropdown</span>
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item small call_modal_edit_holiday border-bottom" href="javascript:" data-holiday_id="<?=$data->holiday_id; ?>" data-holiday="<?= $data->name; ?>" data-date="<?= $data->holidayDate; ?>"><i class="far fa-edit mr-1"></i> Edit </a>
                <a class="dropdown-item small call_modal_remove_holiday" href="javascript:" data-holiday_id="<?=$data->holiday_id; ?>" data-holiday="<?= $data->name; ?>"><i class="far fa-trash-alt mr-1"></i> Delete </a>
              </div>
            </div>
        </td>
      </tr>
  <?php endforeach;
}

if(isset($_POST['edit_holiday'])){
    $holiday = $init->inject($_POST['holiday']);
    $date = $init->inject($_POST['holiday_date']);
    $id = $init->inject($_POST['holiday_id']);

    $json['alert'] = "danger";
    $json['message'] = "<b>Error!</b> Something went wrong! Please contact the administrator!";

    $validate = validate($_POST);

    if(empty($validate)){
        $count = $init->getQuery("SELECT COUNT(*) x FROM holidays WHERE name = '$holiday' AND holidayDate = '$date'");

        if($count[0]->x > 0){
            $json['message'] = "<b>Error!</b> Holiday already exists!";
        } else{
            $sql = $init->query("UPDATE holidays SET name = '$holiday', holidayDate = '$date' WHERE holiday_id = '$id'");
            if($sql){
                $audit = $init->audit("Updated holiday", $id);
                $json['alert'] = "success";
                $json['message'] = "<i class='fas fa-thumbs-up fa-lg fa-spin'></i> Successfully updated holiday!";
            }
        }
    } else{
        $json['message'] = "<b>Error!</b> Fields cannot be left empty!";
    }

    $json['error'] = $init->error();
    echo json_encode($json);
}

if(isset($_POST['remove_holiday'])){
    $holiday_id = $init->inject($_POST['holiday_id']);

    $json['alert'] = "danger";
    $json['message'] = "<b>Error!</b> Something went wrong! Please contact the administrator!";

    $sql = $init->query("UPDATE holidays SET removed = 1 WHERE holiday_id = '$holiday_id'");

    if($sql){
        $audit = $init->audit("Removed holiday", $holiday_id);

        $json['alert'] = "success";
        $json['message'] = "<i class='fas fa-thumbs-up fa-lg fa-spin'></i> Successfully removed holiday!";
    }

    $json['error'] = $init->error();
    echo json_encode($json);
}
// End Holidays

if(isset($_POST['loadTableAbsents'])){
    $id = $init->inject($_POST['id']);
    $json = [];

    $sql = $init->getQuery("SELECT DISTINCT monthname(dtr_date) month, year(dtr_date) year FROM dtr WHERE trainee_id = '$id' AND removed = 0 ORDER BY month(dtr_date) ASC");
    foreach ($sql as $data){
        $month = $data->month;
        $year = $data->year;
        $json['date'][] = Carbon::createFromFormat('F', $data->month)->format('M') . ' ' . $data->year;
        $overtime = $undertime = $tardy = $absent = $present = $weekend = $holiday = 0;

        $sql = $init->getQuery("SELECT remarks FROM dtr WHERE removed = 0 AND monthname(dtr_date) = '$month' AND year(dtr_date) = '$year' AND trainee_id = '$id'");

        foreach($sql as $data){
            $overtime += Intern::count_remarks($data->remarks, 'overtime');
            $undertime += Intern::count_remarks($data->remarks, 'Undertime');
            $tardy += Intern::count_remarks($data->remarks, 'tardy');
            $absent += Intern::count_remarks($data->remarks, 'absent');
            $present += Intern::count_remarks($data->remarks, 'present');
            $weekend += Intern::count_remarks($data->remarks, 'weekend');
            $holiday += Intern::count_remarks($data->remarks, 'holiday');
        }

        $json['overtime'][] = $overtime;
        $json['undertime'][] = $undertime;
        $json['tardy'][] = $tardy;
        $json['absents'][] = $absent;
        $json['present'][] = $present;
        $json['weekend'][] = $weekend;
        $json['holiday'][] = $holiday;
    }

    echo json_encode($json);
}

// School
if(isset($_POST['loadTableSchool'])){
    $sql = $init->getQuery("SELECT * FROM colleges WHERE removed = 0");
    foreach ($sql as $data): ?>
    <tr>
      <td><?=$data->school; ?></td>
      <td>
        <center>
            <div class="btn-group">
              <button type="button" class="btn btn-xs btn-outline-dark dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="sr-only">Toggle Dropdown</span>
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item call_modal_edit_school small border-bottom" href="javascript:" data-school_id="<?=$data->school_id; ?>"><i class="far fa-edit mr-1"></i> Edit </a>
                <a class="dropdown-item call_modal_remove_school small" href="javascript:" data-school_id="<?=$data->school_id; ?>" data-school="<?= $data->school ?>"><i class="far fa-trash-alt mr-1"></i> Remove </a>
              </div>
            </div>
        </center>
        </div>
      </td>
    </tr>
  <?php
    endforeach;
}

if(isset($_POST['add_school'])){
    $school = $init->inject($_POST['school']);
    $address = $init->inject($_POST['school_address']);

    $json['alert'] = "danger";
    $json['message'] = "<b>Error!</b> Something went wrong! Please contact the administrator!";

    $validate = validate($_POST);

    if(empty($validate)){
        $count = $init->getQuery("SELECT COUNT(*) x, removed, school_id FROM colleges WHERE school = '$school'");

        if($count[0]->x > 0){
            if($count[0]->removed == 1){
                $sql = $init->query("UPDATE colleges SET removed = 0 WHERE school_id = {$count[0]->school_id}");

                if($sql){
                    $json['alert'] = "success";
                    $json['message'] = "<i class='fas fa-thumbs-up fa-lg fa-spin'></i> Successfully restored school!";
                }
            } else{
                $json['message'] = "<b>Error!</b> The school already exists!";
            }
        } else{
            $sql = $init->query("INSERT INTO colleges(school, school_address) VALUES('$school', '$address')");

            if($sql){
                $id = $init->insert_id();
                $audit = $init->audit('Added school', $id);

                $json['alert'] = "success";
                $json['message'] = "<i class='fas fa-thumbs-up fa-lg fa-spin'></i> Successfully added school!";
            }
        }
    }

    $error = $init->error();

    echo json_encode($json);
}

if(isset($_POST['remove_school'])){
    $school_id = $init->inject($_POST['school_id']);

    $json['alert'] = 'danger';
    $json['message'] = "<b>Error!</b> Something went wrong! Please contact the administrator!";

    $sql = $init->query("UPDATE colleges SET removed = 1 WHERE school_id = '$school_id'");

    if($sql){
        $audit = $init->audit("Removed school", $school_id);

        $json['alert'] = 'success';
        $json['message'] = "<i class='fas fa-thumbs-up fa-lg fa-spin'></i> Successfully removed school!";
    }

    echo json_encode($json);
}

if(isset($_POST['edit_school'])){
    $school_id = $init->inject($_POST['school_id']);
    $name = $init->inject($_POST['school_name']);
    $address = $init->inject($_POST['school_address']);

    $json['alert'] = "danger";
    $json['message'] = '<b>Error!</b> Something went wrong! Please contact the administrator~';

    $count = $init->getQuery("SELECT COUNT(*) x FROM colleges WHERE BINARY school = '$name' and BINARY school_address = '$address'");

    if($count[0]->x > 0){
        $json['message'] = '<b>Error!</b> School already exists!';
    } else{
        $sql = $init->query("UPDATE colleges SET school = '$name', school_address = '$address' WHERE school_id = '$school_id'");
        if($sql){
            $audit = $init->audit("Updated school", $school_id);
            if($audit){
                $json['alert'] = "success";
                $json['message'] = "<i class='fas fa-thumbs-up fa-lg fa-spin'></i> Successfully updated school!";
            }
        }
    }

    $json['error'] = $init->error();

    echo json_encode($json);
}

if(isset($_POST['searchSchool'])){
    $search = $init->inject($_POST['search']);
    $sql = $init->getQuery("SELECT * FROM colleges WHERE school LIKE '%$search%' AND removed = 0");
    foreach ($sql as $data): ?>
  <tr>
    <td><?=$data->school; ?></td>
    <td>
      <div class="btn-group">
        <button class="btn btn-outline-primary btn-sm" data-target="#modalUpdateSchool_<?=$data->school_id; ?>" data-toggle="modal">Edit</button>
        <div class="modal fade" id="modalUpdateSchool_<?=$data->school_id; ?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <div class="modal-title h4">Update <?=$data->school; ?></div>
                <button class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <div class="form-row mb-1">
                  <div class="col-lg-2"><label>School</label></div>
                  <div class="col-lg-10"><input type="text" class="form-control" placeholder="Name of College/Institution" id="updateSchoolName_<?=$data->school_id; ?>" value="<?=$data->school; ?>" required></div>
                </div>
                <div class="form-row mb-1">
                  <div class="col-lg-2"><label>Address</label></div>
                  <div class="col-lg-10"><input type="text" class="form-control" placeholder="College/Institution address" id="updateSchoolAddress_<?=$data->school_id; ?>" value="<?=$data->school_address; ?>" required></div>
                </div>
              </div>
              <div class="modal-footer">
                <button class="btn btn-primary btn-sm" type="button" onclick="updateSchool(<?=$data->school_id; ?>)" data-dismiss="modal">Save</button>
                <button class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Delete -->
        <button class="btn btn-outline-danger btn-sm" data-target="#modalRemoveSchool_<?=$data->school_id; ?>" data-toggle="modal">Remove</button>
        <div class="modal fade" id="modalRemoveSchool_<?=$data->school_id; ?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <div class="modal-title h4">Remove <?=$data->school; ?></div>
                <button class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body h6">
                Are you sure to remove <b><?=$data->school; ?></b> ?
              </div>
              <div class="modal-footer">
                  <button class="btn btn-primary btn-sm" type="button" onclick="removeSchool(<?=$data->school_id; ?>)" data-dismiss="modal">Yes</button>
                  <button class="btn btn-secondary btn-sm" data-dismiss="modal">No</button></div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </td>
  </tr>
  <?php
    endforeach;
}
// End School

// Office
if(isset($_POST['add_office'])){
    $office = $init->inject($_POST['office']);

    $json['alert'] = 'danger';
    $json['message'] = "<b>Error!</b> Something went wrong! Please contact the administrator!";

    $count = $init->getQuery("SELECT COUNT(*) x, removed, office_id FROM offices WHERE office = '$office'");

    if($count[0]->x > 0){
        if($count[0]->removed == 1){
            $sql = $init->query("UPDATE offices SET removed = 0 WHERE office_id = {$count[0]->office_id}");

            if($sql){
                $json['alert'] = 'success';
                $json['message'] = "<i class='fas fa-thumbs-up fa-lg fa-spin'></i> Successfully restored office!";
            }
        } else{
            $json['message'] = "<b>Error!</b> Office already exists!";
        }
    } else{
        $sql = $init->query("INSERT INTO offices(office) VALUES ('$office')");

        if($sql){
            $id = $init->insert_id();
            $audit = $init->audit("Added new office", $id);

            $json['alert'] = 'success';
            $json['message'] = "<i class='fas fa-thumbs-up fa-lg fa-spin'></i> Successfully added office!";
        }
    }

    $json['error'] = $init->error();

    echo json_encode($json);
}

if(isset($_POST['loadofficeTable'])){
    $sql = $init->getQuery("SELECT * FROM offices WHERE removed = 0");
    foreach ($sql as $data): ?>
    <tr>
      <td><?=$data->office; ?></td>
      <td>
        <center>
            <div class="btn-group">
              <button type="button" class="btn btn-xs btn-outline-dark dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="sr-only">Toggle Dropdown</span>
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item call_modal_edit_office small border-bottom" href="javascript:" data-office_id="<?= $data->office_id; ?>" data-office="<?= $data->office ?>"><i class="far fa-edit mr-1"></i> Edit </a>
                <a class="dropdown-item call_modal_remove_office small" href="javascript:" data-office_id="<?= $data->office_id; ?>" data-office="<?= $data->office ?>"><i class="far fa-trash-alt mr-1"></i> Remove </a>
              </div>
            </div>
        </center>
      </td>
    </tr>
  <?php
    endforeach;
}

if(isset($_POST['edit_office'])){
    $office = $init->inject($_POST['office']);
    $office_id = $init->inject($_POST['office_id']);

    $json['alert'] = 'danger';
    $json['message'] = "<b>Error!</b> Something went wrong! Please contact the administrator!";

    $validate = validate($_POST);

    if(empty($validate)){
        $count = $init->getQuery("SELECT COUNT(*) x FROM offices WHERE BINARY office = '$office'");

        if($count[0]->x > 0){
            $json['message'] = "<b>Error!</b> Office already exists!";
        } else{
            $sql = $init->query("UPDATE offices SET office = '$office' WHERE office_id = '$office_id'");

            if($sql){
                $audit = $init->audit("Updated office", $office_id);

                $json['alert'] = 'success';
                $json['message'] = "<i class='fas fa-thumbs-up fa-lg fa-spin'></i> Successfully updated office!";
            }
        }
    } else{
        $json['message'] = "<b>Error!</b> Fields cannot be left empty!";
    }

    $json['error'] = $init->error();

    echo json_encode($json);
}

if(isset($_POST['remove_office'])){
    $id = $init->inject($_POST['office_id']);

    $json['alert'] = 'danger';
    $json['message'] = "<b>Error!</b> Something went wrong! Please contact the administrator!";

    $sql = $init->query("UPDATE offices SET removed = 1 WHERE office_id = '$id'");

    if($sql){
        $audit = $init->audit("Removed office", $id);

        $json['alert'] = 'success';
        $json['message'] = "<i class='fas fa-thumbs-up fa-lg fa-spin'></i> Successfully removed office!";
    }

    $json['error'] = $init->error();

    echo json_encode($json);
}
// End Offic

if(isset($_POST['refresh'])){
    $intern = new Intern;
    $sql = $intern->getInterns();
    $now = Carbon::now()->format('Y-m-d');

    $json = [];

    foreach($sql as $key => $data){
        $left = $data->hours_required - ($intern->getTotalTime($data->trainee_id) / 60);

        if($left < 0){
            if($data->date_finished == 0){
                $init->query("UPDATE trainees SET date_finished = '$now'");
            }
        }
    }
}