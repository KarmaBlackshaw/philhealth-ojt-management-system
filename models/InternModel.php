<?php

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use NumberToWords\NumberToWords;

class Intern extends init{


	public function test(){
		return 'test';
	}

	public function getSchoolById($id){
		return $this->getQuery("SELECT school, school_address FROM school WHERE school_id = {$id}");
	}

	public function getHolidays(){
		return $this->getQuery("SELECT * FROM holidays WHERE removed = 0 ORDER BY holiday_id DESC");
	}

	public function getInternProfile($id){
		$sql = $this->getQuery("
			SELECT *
			FROM trainees t
			JOIN offices o ON t.office_id = o.office_id
			JOIN school c ON t.school_id = c.school_id
			WHERE t.trainee_id = '$id'");

		$json = [];

		if($sql[0]){
			$json['trainee_id'] = $sql[0]->trainee_id;
			$json['name'] = fullname($sql[0]->name);
			$json['gender'] = $sql[0]->gender;
			$json['course'] = $sql[0]->course;
			$json['supervisor'] = $sql[0]->supervisor;
			$json['date_started'] = Carbon::createFromFormat('Y-m-d', $sql[0]->date_started)->toDayDateTimeString();
			$json['date_start_raw'] = $sql[0]->date_started;
			$json['schedule'] = $sql[0]->schedule;
			$json['hours_required'] = $sql[0]->hours_required;
			$json['expected_month'] = $sql[0]->expected_month;
			$json['office'] = $sql[0]->office;
			$json['school'] = $sql[0]->school;
			$json['school_address'] = $sql[0]->school_address;
			$json['school_id'] = $sql[0]->school_id;
			$json['office_id'] = $sql[0]->office_id;
			$json['date_finished'] = $sql[0]->finished_date;

			$explode = explode('%', $sql[0]->name);
			$json['fname'] = $explode[0];
			$json['mname'] = $explode[1];
			$json['lname'] = end($explode);

			$json['title'] = '';
			$json['indefinite_pronouns'] = '';
			$json['personal_pronouns'] = '';

			if($sql[0]->gender == 'female'){
				$json['title'] = 'Ms. ';
				$json['indefinite_pronoun'] = 'her';
				$json['personal_pronouns'] = 'she';
			}

			if($sql[0]->gender == 'male'){
				$json['title'] = 'Mr. ';
				$json['indefinite_pronouns'] = 'his';
				$json['personal_pronouns'] = 'he';
			}

			unset($sql[0]->id);
			unset($sql[0]->removed);
			unset($sql[0]->school_address);
		}

		return $json;
	}

	public function getInterns(){
		return $this->getQuery("SELECT * FROM trainees JOIN offices ON trainees.office_id = offices.office_id JOIN school ON trainees.school_id = school.school_id WHERE trainees.removed = 0 ORDER BY trainees.id DESC");
	}

	public function internExists($id){
		$sql = $this->getQuery("SELECT COUNT(*) x FROM trainees WHERE trainee_id = '$id'");

		return $sql[0]->x == 0 ? false : true;
	}

	public function getTotalHours($id){
		$sql = $this->getQuery("SELECT sum(total) total FROM dtr WHERE trainee_id = '$id' AND removed = 0");
		return floor($sql[0]->total / 60);
	}

	public static function toWords($number){
		$numberToWords = new NumberToWords();
		$numberTransformer = $numberToWords->getNumberTransformer('en');

		if(empty($number)){
			return 'Zero';
		}

		$explode_1 = explode(' ', $numberTransformer->toWords($number));
		$raw_1 = array_map('ucfirst', $explode_1);
		$implode_1 = implode(' ', $raw_1);

		$explode_2 = explode('-', $implode_1);
		$raw_2 = array_map('ucfirst', $explode_2);
		return  implode('-', $raw_2);

	}

	public static function toTimeString($time, $format = '%02d hrs %02d mins') {
	    if ($time < 1) {
	        return 0;
	    }

	    $hours = ceil($time / 60);
	    $minutes = ($time % 60);
	    return sprintf($format, $hours, $minutes);
	}

	function isHoliday($date) {
	    $sql = $this->getQuery("SELECT COUNT(*) total FROM holidays WHERE holidayDate = '$date' AND removed = 0");

	    return $sql[0]->total > 0 ? true : false;
	}

	public function getDTRSummary($id){
		$sql = $this->getQuery("SELECT DISTINCT monthname(dtr_date) month, month(dtr_date) month_number, year(dtr_date) year FROM dtr WHERE trainee_id = '$id' AND removed = 0");

		foreach($sql as $data){
			$date = $data->year .'-'. str_pad($data->month_number, 2, '0', STR_PAD_LEFT);

			$sql1 = $this->getQuery("SELECT SUM(total) total FROM dtr WHERE dtr_date LIKE '%$date%' AND trainee_id = '$id' AND removed = 0");
			// $data->total = Intern::toTimeString($sql1[0]->total);
			$data->total = round($sql1[0]->total / 60);
			$data->minutes = $sql1[0]->total;
			$data->month_year = "$data->month $data->year";
		}

		return $sql;
	}

	public static function getDtrDates($year, $month){
		$raw = "$year-$month-01";
		$date = Carbon::createFromFormat('Y-m-d', $raw);

		$period = CarbonPeriod::create($raw, $date->endOfMonth());
		$json = [];

		foreach ($period as $key => $date) {
		    $json[] = $date->format('Y-m-d');
		}

		return $json;
	}

	function dtrExists($year, $month, $trainee_id) {
		$date = "$year-$month";
	    $sql = $this->getQuery("SELECT COUNT(*) total FROM dtr WHERE dtr_date LIKE '%$date%' AND trainee_id = '$trainee_id' AND removed = 0");

	    if ($sql[0]->total > 0) {
	        return 1;
	    } else {
	        return 0;
	    }
	}

	public static function getTime($time, $format){
		if(!empty($time)){
			$explode = explode(':', $time);

			if($format == 'hr'){
				return $explode[0];
			}

			if($format == 'min'){
				return $explode[1];
			}

		}

		return;
	}

	public static function isLate($morning_in){
		return $minutes > 480 ? 1 : 0;
	}

	public static function convertOvertime($min, $mout, $ain, $aout, $date){
		$min = empty($min) ? 0 : $min <= 480 && $min > 0 ? 480 : $min;
		$mout = empty($mout) ? 0 : $mout >= 720 ? 720 : $mout;
		$ain = empty($ain) ? 0 : $ain;
		$aout = empty($aout) ? 0 : $aout;

		$min = (int) $min;
		$mout = (int) $mout;
		$ain = (int) $ain;
		$aout = (int) $aout;

		$isWeekend = Carbon::createFromFormat('Y-m-d', $date)->isWeekend();

		$morning = $mout - $min; // Morning should be 240 for overtime
        $afternoon = $aout - $ain; // Afternoon should be atleast 330 for overtime
        $total = $morning + $afternoon;

        // If overtime
        if($morning == 240 && $afternoon >= 330){
        	$overtime = $total - 510; // 570 is the usual day time;

        	if($isWeekend){
        		$overtime = ($overtime * 1.5);
        	} else{
        		$overtime = ($overtime * 1.25);
        	}

        	return round($overtime + 510);
        }

        if($total > 480 && $total < 570){
        	 return 480;
        }

		return $total;

    }

	public static function hasTimeError($min, $mout, $ain, $aout){
		$min = empty($min) ? 0 : $min <= 480 && $min > 0 ? 480 : $min;
		$mout = empty($mout) ? 0 : $mout >= 720 ? 720 : $mout;
		$ain = empty($ain) ? 0 : $ain;
		$aout = empty($aout) ? 0 : $aout;

		$min = (int) $min;
		$mout = (int) $mout;
		$ain = (int) $ain;
		$aout = (int) $aout;

		$morning = $mout - $min; // Morning should be 240 for overtime
        $afternoon = $aout - $ain; // Afternoon should be atleast 330 for overtime
        $total = $morning + $afternoon;

        if($total == 0){
        	return false;
        } elseif((!empty($min) && empty($mout)) || (empty($min) && !empty($mout))){
			return true;
		} elseif((!empty($ain) && empty($aout)) || (empty($ain) && !empty($aout))){
			return true;
		} elseif($total <= 0){
			return true;
		} elseif(empty($min) && empty($mout) && empty($ain) && empty($aout)) {
			return false;
		} else{
			if($min > $mout){
			    return true;
			} elseif($ain > $aout){
			    return true;
			} else{
				return false;
			}
		}
	}
	public static function getRemarks($min, $mout, $ain, $aout, $date, $finished = 0){
		$intern = new Intern;
		$min = empty($min) ? 0 : $min <= 480 && $min > 0 ? 480 : $min;
		$mout = empty($mout) ? 0 : $mout >= 720 ? 720 : $mout;
		$ain = empty($ain) ? 0 : $ain;
		$aout = empty($aout) ? 0 : $aout;

		$min = (int) $min;
		$mout = (int) $mout;
		$ain = (int) $ain;
		$aout = (int) $aout;

		$morning = $mout - $min; // Morning should be 240 for overtime
        $afternoon = $aout - $ain; // Afternoon should be atleast 330 for overtime
        $total = $morning + $afternoon;

        $isWeekend = Carbon::createFromFormat('Y-m-d', $date)->isWeekend();

        if(Intern::hasTimeError($min, $mout, $ain, $aout)){
        	return "Error";
        } else{

	        if($isWeekend){
				return $total > 0 ? 'Overtime' : 'Weekend';
			} elseif($intern->isHoliday($date)){
				return $total > 0 ? 'Overtime' : 'Holiday';
			} else{
				if($min > 480 && $total < 480 || $min == 0 && $total < 480 && $total > 0){
					return 'Tardy & Undertime';
				}

				if($min == 480 && $total < 480){
					return 'Undertime';
				}

				if($min > 480 && $total >= 480){
					return 'Tardy';
				}

				if($morning == 240 && $afternoon >= 330){
					return 'Overtime';
				}

				if($morning == 240 && $afternoon >= 240 && $afternoon < 330){
					return 'Present';
				}

				if($total == 0){
					return 'Absent';
				}
			}
        }
	}

	public static function count_remarks($data, $remarks){
		return stristr($data, $remarks) ? 1 : 0;
	}

	public static function toMinutes($hour, $minute = 0){
		$hour = (int) $hour;
		$minute = (int) $minute;

		return ($hour * 60) + $minute;
	}

	public function dtr_exists($year, $month, $trainee_id){
		$sql = $this->getQuery("SELECT COUNT(*) total FROM dtr WHERE month(dtr_date) = '$month' AND year(dtr_date) = '$year' AND trainee_id = '$trainee_id'");

		return $sql[0]->total > 0 ? true : false;
	}

	public function getTotalTime($id){
		$sql = $this->getQuery("SELECT SUM(total) total FROM dtr WHERE trainee_id = '$id' AND removed = 0");

		return $sql[0]->total;
	}
}