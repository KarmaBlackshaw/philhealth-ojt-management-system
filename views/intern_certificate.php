<?php  

ob_start ();

require_once dirname(__DIR__) . '/lib/init.php';
require_once abs_sessions('administrator');
use Carbon\Carbon;
$intern = new Intern;

if(!isset($_GET['id'])){
	header('Location: intern.php');
}

// Header
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Karma Blackshaw');
$pdf->SetTitle('Intern Certification');
$pdf->SetSubject('Intern Certification');
$pdf->SetKeywords('Intern Certification');
set_time_limit(0);
ini_set('memory_limit', '-1');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetDefaultMonospacedFont('Helvetica');

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->AddPage();

$pdf->SetFont('Times', 'BI', 9);
$pdf->writeHTML('Republic of the Philippines', true, false, true, false, 'C');

$pdf->SetFont('Helvetica', 'B', 13);
$pdf->writeHTML('PHILIPPINE HEALTH INSURANCE CORPORATION', true, false, true, false, 'C');

$pdf->SetFont('Times', '', 9);
$pdf->Cell(0, 0, 'Regional Office VIII', 0, 1, 'C', 0, '', 0);
$pdf->Cell(0, 0, '167 P. Burgos Street, Tacloban City', 0, 1, 'C', 0, '', 0);
$pdf->Cell(0, 0, 'Telefax: (053) 523-8283', 0, 1, 'C', 0, '', 0);
$pdf->writeHTML('<u>www.philhealth.gov.ph</u> &nbsp; Email: hr.pro8@philhealth.gov.ph', true, false, true, false, 'C');

$pdf->Ln();$pdf->Ln();$pdf->Ln();

$pdf->SetFont('Times', 'BU', 18);
$pdf->writeHTML('C E R T I F I C A T I O N', true, false, true, false, 'C');

$pdf->Ln();$pdf->Ln();

$pdf->Image('../assets/images/uhc logo.png', 165, 10, 25, '', '', 'http://www.tcpdf.org', '', false, 300);
$pdf->Image('../assets/images/PhilHealth Horizontal logo.jpg', 25, 15, 15, '', '', 'http://www.tcpdf.org', '', false, 300);

$pdf->SetFont('Times', '', 12);
// Header End

$id = $init->inject($_GET['id']);

$profile = $intern->getInternProfile($id);

$sql = $init->getQuery("SELECT dtr_date FROM dtr WHERE trainee_id = '$id' AND total <> 0 ORDER BY dtr_id DESC LIMIT 1");

// foreach($sql as $data){
// 	$finished = $init->dateString($data->dtr_date);
// }

$sql = $init->getQuery("
		SELECT u.name, o.office, u.position 
		FROM users u
		JOIN offices o ON u.office_id = o.office_id 
		WHERE u.user_id = {$_SESSION['user_id']}
	");

foreach($sql as $data){
	$username = fullname($data->name);
	$useroffice = $data->office;
	$userposition = $data->position;
}

$total = $intern->getTotalHours($id);

$title = $profile['title'];
$name = $profile['name'];
$course = $profile['course'];
$school = $profile['school'];
$school_address = $profile['school_address'];
$indefinite_pronouns = $profile['indefinite_pronouns'];
$personal_pronouns = ucfirst($profile['personal_pronouns']);
$office = $profile['office'];
$date_start = Carbon::createFromFormat('Y-m-d', $profile['date_start_raw'])->format('F d, Y');
$date_finished = $profile['date_finished'] < 1  ? Carbon::now()->format('F d, Y') : Carbon::createFromFormat('Y-m-d', $profile['date_finished'])->format('l, F d, Y');

$time_remaining = $profile['hours_required'] - $total;

$pdf->writeHTML("
	<style>span{line-height: 25px;text-indent: 12.7mm;}</style>
	<span>&nbsp;&nbsp;&nbsp;THIS IS TO CERTIFY that <u><b>$title $name</b></u>, a <b><u>". $course ."</u></b> student of <b><u>$school, $school_address</u></b> has rendered ". $indefinite_pronouns ." office practice under the <b><u>". $office ."</u></b> of this office from <b><u>$date_start to $date_finished</u></b>. $personal_pronouns has rendered a total of <b><u>". Intern::toWords($total) ." ($total Hours)</u></b> of actual practicum service in this office.</span>", true, false, true, false, 'J');

$pdf->writeHTMLCell(0, 10, '', '', '', '', 1, 0, false, 'L', false);

$pdf->writeHTML("&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon the request of the <b>OJT</b> for purposes of curriculum completion.", true, false, true, false, 'J');

$pdf->Cell(0, 10, '', 0, 1, 'C', 0, '', 0);

$pdf->writeHTML("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Issued this <b><u>". date('jS \d\a\y  \o\f F Y') .".</u></b>", true, false, true, false, 'J');

$pdf->Cell(0, 20, '', 0, 1, 'C', 0, '', 0);

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(120, 0, '', 0, 0, 'C', 0, '', 0);
$pdf->Cell(60, 0, strtoupper($username), 0, 1, 'C', 0, '', 0);
$pdf->SetFont('Times', 'I', 8);
$pdf->Cell(120, 0, '', 0, 0, 'C', 0, '', 0);
$pdf->Cell(60, 0, $userposition, 0, 1, 'C', 0, '', 0);

$pdf->Cell(0, 34, '', 0, 1, 'C', 0, '', 0);

$pdf->SetFont('Times', 'I', 8);
$pdf->Cell(0, 0, 'Not validate without Seal', 0, 1, 'L', 0, '', 0);

$pdf->Cell(0, 34, '', 0, 1, 'L', 0, '', 0);

$pdf->SetFont('Times', '', 7);
$pdf->Cell(0, 5, '', 'B', 1, 'C', 0, '', 0, false, 'T', 'C');
$pdf->Cell(40, 0, 'teamphilhealth', 0, 0, 'C', 0, '', 0);
$pdf->Cell(40, 0, 'www.facebook.com/PhilHealth', 0, 0, 'C', 0, '', 0);
$pdf->Cell(50, 0, 'www.youtube.com/teamphilhealth', 0, 0, 'C', 0, '', 0);
$pdf->Cell(30, 0, 'actioncenter@philhealth.gov.ph', 0, 1, 'C', 0, '', 0);

//Cell(width, height, txt, border, ln, align, fill, link, stretch, ignore_min_height, align, valign='M');
$pdf->AddPage();

$pdf->setCellPaddings( 1, 1.2, 1, 1.2);
$pdf->SetFont('Helvetica', '', 8);
$pdf->Cell(20, 0, 'Name', 1, 0, 'L', 0, '', 0);
$pdf->SetFont('Helvetica', 'B', 8);
$pdf->Cell(70, 0, $name, 1, 0, 'L', 0, '', 0);

$pdf->SetFont('Helvetica', '', 8);
$pdf->Cell(20, 0, 'School', 1, 0, 'L', 0, '', 0);
$pdf->SetFont('Helvetica', 'B', 8);
$pdf->Cell(70, 0, $school, 1, 1, 'L', 0, '', 1);

$pdf->SetFont('Helvetica', '', 8);
$pdf->Cell(20, 0, 'Course', 1, 0, 'L', 0, '', 0);
$pdf->SetFont('Helvetica', 'B', 8);
$pdf->Cell(70, 0, $course, 1, 0, 'L', 0, '', 0);

$pdf->SetFont('Helvetica', '', 8);
$pdf->Cell(20, 0, 'Dept.', 1, 0, 'L', 0, '', 0);
$pdf->SetFont('Helvetica', 'B', 8);
$pdf->Cell(70, 0, $office, 1, 1, 'L', 0, '', 1);

$pdf->Cell(180, 0, '', 1, 1, 'L', 0, '', 0);

$pdf->Cell(20, 0, '', 1, 0, 'L', 0, '', 0);
$pdf->SetFont('Helvetica', '', 8);
$pdf->Cell(70, 0, 'OJT Immediate Supervisor', 1, 0, 'L', 0, '', 1);
$pdf->SetFont('Helvetica', 'B', 8);
$pdf->Cell(90, 0, $profile['supervisor'], 1, 1, 'L', 0, '', 1);

$pdf->Cell(20, 0, '', 1, 0, 'L', 0, '', 0);
$pdf->SetFont('Helvetica', '', 8);
$pdf->Cell(70, 0, 'No. of Hours OJT Required', 1, 0, 'L', 0, '', 1);
$pdf->SetFont('Helvetica', 'B', 8);
$pdf->Cell(90, 0, $profile['hours_required'] . ' hours', 1, 1, 'L', 0, '', 1);

$pdf->Cell(20, 0, '', 1, 0, 'L', 0, '', 0);
$pdf->SetFont('Helvetica', '', 8);
$pdf->Cell(70, 0, 'Date Started', 1, 0, 'L', 0, '', 1);
$pdf->SetFont('Helvetica', 'B', 8);
$pdf->Cell(90, 0, Carbon::createFromFormat('Y-m-d', $profile['date_start_raw'])->format('l, F d, Y'), 1, 1, 'L', 0, '', 1);

$pdf->Cell(20, 0, '', 1, 0, 'L', 0, '', 0);
$pdf->SetFont('Helvetica', '', 8);
$pdf->Cell(70, 0, 'Date Ended', 1, 0, 'L', 0, '', 1);
$pdf->SetFont('Helvetica', 'B', 8);
$pdf->Cell(90, 0, $date_finished, 1, 1, 'L', 0, '', 1);

$pdf->Cell(0, 10, '', 0, 1, 'L', 0, '', 0);

$pdf->SetFont('Helvetica', 'B', 8);
$pdf->Cell(75, 0, 'Month', 1, 0, 'C', 0, 0);
$pdf->Cell(75, 0, 'Year', 1, 0, 'C', 0, 0);
$pdf->Cell(30, 0, 'Total Hours', 1, 1, 'C', 0, 0);

$sql = $init->getQuery("SELECT DISTINCT monthname(dtr_date) month, year(dtr_date) year FROM dtr WHERE trainee_id = '$id'");

$pdf->SetFont('Helvetica', '', 8);

$total = 0;
foreach($sql as $data){
	$month = $data->month;
	$year = $data->year;

	$sql = $init->getQuery("SELECT sum(total) total FROM dtr WHERE monthname(dtr_date) = '$month' AND year(dtr_date) = '$year' AND trainee_id = '$id'");
	foreach($sql as $data){
		$pdf->Cell(75, 0, $month, 1, 0, 'C', 0, 0);
		$pdf->Cell(75, 0, $year, 1, 0, 'C', 0, 0);
		$pdf->Cell(30, 0, Intern::toTimeString($data->total), 1, 1, 'C', 0, 0);	
		$total += $data->total;
	}
}

$pdf->SetFont('Helvetica', 'I', 7);
$pdf->Cell(150, 0, 'Total Hours', 1, 0, 'C', 0, 0);
$pdf->SetFont('Helvetica', 'B', 7);
$pdf->Cell(30, 0, Intern::toTimeString($total), 1, 1, 'C', 0, 0);

$sql = $init->getQuery("SELECT DISTINCT monthname(dtr_date) month, year(dtr_date) year FROM dtr WHERE trainee_id = '$id' ORDER BY year(dtr_date) DESC");

$pdf->SetMargins(20, 10, 20, true);

foreach($sql as $data) :
	$month = $data->month;
	$year = $data->year;

	$pdf->AddPage();
	$pdf->SetFont('Helvetica', 'B', 8);
	$pdf->setCellPadding(1.2);

	$pdf->SetFont('Helvetica', 'I', 6);
	$pdf->Cell(0, 0, 'Philippines Health Insurance Corporation Form', 0, 1, 'L', 0, 0);

	$pdf->Ln(2);

	$pdf->SetFont('Helvetica', 'BI', 12);
	$pdf->Cell(0, 0, 'D A I L Y   T I M E   R E C O R D', 0, 1, 'C', 0, '', 0);

	$pdf->Ln(2);
	// First row
	$pdf->SetFont('Helvetica', 'B', 8);
	$pdf->Cell(10, 0, '', 'LTR', 0, 'C', 0, '', 0, false, 'T', 'C');
	$pdf->Cell(20, 0, '', 'LTR', 0, 'C', 0, '', 0, false, 'T', 'C');
	$pdf->Cell(80, 0, $month . ' ' . $year, 'LTR', 0, 'C', 0, '', 0, false, 'T', 'C');
	$pdf->Cell(30, 0, '', 'LTR', 0, 'C', 0, '', 0, false, 'T', 'C');
	$pdf->Cell(30, 0, '', 'LTR', 1, 'C', 0, '', 0, false, 'T', 'C');

	// Second

	$pdf->SetFont('Helvetica', '', 8);
	$pdf->Cell(10, 0, 'No.', 'LR', 0, 'C', 0, '', 0, false, 'T', 'C');
	$pdf->Cell(20, 0, 'Days', 'LR', 0, 'C', 0, '', 0, false, 'T', 'C');
	$pdf->Cell(40, 0, 'Morning', 'LTR', 0, 'C', 0, '', 0, false, 'T', 'C');
	$pdf->Cell(40, 0, 'Afternoon', 'LTR', 0, 'C', 0, '', 0, false, 'T', 'C');
	$pdf->Cell(30, 0, 'Total', 'LR', 0, 'C', 0, '', 0, false, 'T', 'C');
	$pdf->Cell(30, 0, 'Remarks', 'LR', 1, 'C', 0, '', 0, false, 'T', 'C');

	// Third

	$pdf->SetFont('Helvetica', 'I', 7.5);
	$pdf->Cell(10, 0, '', 'LRB', 0, 'C', 0, '', 0, false, 'T', 'C');
	$pdf->Cell(20, 0, '', 'LRB', 0, 'C', 0, '', 0, false, 'T', 'C');
	$pdf->Cell(20, 0, 'In', '1', 0, 'C', 0, '', 0, false, 'T', 'C');
	$pdf->Cell(20, 0, 'Out', '1', 0, 'C', 0, '', 0, false, 'T', 'C');
	$pdf->Cell(20, 0, 'In', '1', 0, 'C', 0, '', 0, false, 'T', 'C');
	$pdf->Cell(20, 0, 'Out', '1', 0, 'C', 0, '', 0, false, 'T', 'C');
	$pdf->Cell(30, 0, '', 'LRB', 0, 'C', 0, '', 0, false, 'T', 'C');
	$pdf->Cell(30, 0, '', 'LRB', 1, 'C', 0, '', 0, false, 'T', 'C');

	$sql1 = $init->getQuery("SELECT * FROM dtr WHERE monthname(dtr_date) = '$month' AND year(dtr_date) = '$year' AND trainee_id = '$id'");

	$pdf->SetFont('Helvetica', '', 8);
	$new_total = 0;
	foreach($sql1 as $data1){

		$total = $data1->total;
		$new_total += $total;
		$date = Carbon::createFromFormat('Y-m-d', $data1->dtr_date);

		if($total == 0){
			if($date->isWeekend()){
				$pdf->SetFillColor(229,255,224);
				$pdf->MultiCell(10, 5, $date->day, 1, 'C', 1, 0, '', '', true);
				$pdf->MultiCell(20, 5, $date->dayOfWeek, 1, 'C', 1, 0, '', '', true);
				$pdf->MultiCell(140, 5, 'Weekend', 1, 'C', 1, 1, '', '', true);
			}

			elseif($init->isHoliday($date)){

				$holidaySql = $init->getQuery("SELECT * FROM holidays WHERE holidayDate = '$date'");

				foreach($holidaySql as $holidayData){
					$holiday = $holidayData->name;
				}
				$pdf->SetFillColor(204,255,255);
				$pdf->MultiCell(10, 5, $date->day, 1, 'C', 1, 0, '', '', true);
				$pdf->MultiCell(20, 5, $date->dayOfWeek, 1, 'C', 1, 0, '', '', true);
				$pdf->MultiCell(140, 5, $holiday , 1, 'C', 1, 1, '', '', true);
			}

			else{
				$pdf->SetFillColor(253,240,240);
				$pdf->MultiCell(10, 5, $date->day, 1, 'C', 1, 0, '', '', true);
				$pdf->MultiCell(20, 5, $date->dayOfWeek, 1, 'C', 1, 0, '', '', true);
				$pdf->MultiCell(20, 5, $data1->morning_in, 1, 'C', 1, 0, '', '', true);
				$pdf->MultiCell(20, 5, $data1->morning_out, 1, 'C', 1, 0, '', '', true);
				$pdf->MultiCell(20, 5, $data1->afternoon_in, 1, 'C', 1, 0, '', '', true);
				$pdf->MultiCell(20, 5, $data1->afternoon_out, 1, 'C', 1, 0, '', '', true);
				$pdf->MultiCell(30, 5, Intern::toTimeString($total), 1, 'C', 1, 0, '', '', true);
				$pdf->MultiCell(30, 5, '' , 1, 'C', 1, 1, '', '', true);
			}
		} else{
			$pdf->SetFillColor(255,250,250);
			$pdf->MultiCell(10, 5, $date->day, 1, 'C', 1, 0, '', '', true);
			$pdf->MultiCell(20, 5, $date->dayOfWeek, 1, 'C', 1, 0, '', '', true);
			$pdf->MultiCell(20, 5, $data1->morning_in, 1, 'C', 1, 0, '', '', true);
			$pdf->MultiCell(20, 5, $data1->morning_out, 1, 'C', 1, 0, '', '', true);
			$pdf->MultiCell(20, 5, $data1->afternoon_in, 1, 'C', 1, 0, '', '', true);
			$pdf->MultiCell(20, 5, $data1->afternoon_out, 1, 'C', 1, 0, '', '', true);
			$pdf->MultiCell(30, 5, Intern::toTimeString($total), 1, 'C', 1, 0, '', '', true);
			$pdf->MultiCell(30, 5, $data1->remarks, 1, 'C', 1, 1, '', '', true);
		}
	}

	$pdf->SetFillColor(223, 230, 233);
	// $pdf->MultiCell(110, 5, 'Total hours of the month', 1, 'C', 1, 0, '', '', true);
	// $pdf->MultiCell(60, 5, Intern::toTimeString($new_total), 1, 'C', 1, 1, '', '', true);
	$pdf->Cell(110, 0, 'Total hours of the month', 'TRBL', 0, 'C', 1, '', 1, false, 'T', 'C');
	$pdf->Cell(60, 0, Intern::toTimeString($new_total), 'TRBL', 1, 'C', 1, '', 1, false, 'T', 'C');

	$pdf->Ln(2);

	$pdf->SetFont('Helvetica', '', 10);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->MultiCell(0, 0, 'I certify on my honor that the above is a true and correct report of number of hours of work performed , record of which was made daily at the time of arrival and at departure from office.', 0, 'L', 1, 1, '', '', true);

	$pdf->Ln(5);

	$pdf->SetFont('Helvetica', 'B', 10);
	$pdf->Cell(83, 0, $name, '', 0, 'C', 0, '', 0, false, 'T', 'C');
	$pdf->Cell(4, 0, '', '', 0, 'C', 0, '', 0, false, 'T', 'C');
	$pdf->Cell(83, 0, '', '', 1, 'C', 0, '', 0, false, 'T', 'C');

	$pdf->SetFont('Helvetica', 'I', 7);
	$pdf->Cell(83, 0, 'Name of Intern', 'T', 0, 'C', 0, '', 0, false, 'T', 'C');
	$pdf->Cell(4, 0, '', '', 0, 'C', 0, '', 0, false, 'T', 'C');
	$pdf->Cell(83, 0, 'Immediate Supervisor', 'T', 1, 'C', 0, '', 0, false, 'T', 'C');
	// $pdf->SetFont('Helvetica', 'BIU', 10.5);
	// $pdf->Cell(0, 1, $name, 0, 1, 'C', 0, '', 0);
	// $pdf->SetFont('Helvetica', 'I', 8);
	// $pdf->Cell(0, 1, 'Name of Intern', 0, 1, 'C', 0, '', 0);
	// $pdf->SetFont('Helvetica', 'I', 8);
	// $pdf->Ln();
	// $pdf->Cell(0, 0, '', 'T', 1, 'C', 0, '', 0, false, 'T', 'C');
	// $pdf->Cell(0, 1, '___________________________________________', 0, 1, 'C', 0, '', 0);
	// $pdf->SetFont('Helvetica', 'I', 8);
	// $pdf->Cell(0, 1, 'Immediate Supervisor', 0, 1, 'C', 0, '', 0);

endforeach;

$pdf->Output($name . '.pdf', 'I');
ob_start ();