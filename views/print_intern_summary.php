<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>
<?php

ob_start();
include (dirname(dirname(__FILE__)) . '/lib/init.php');
require_once abs_sessions('administrator');
$intern = new Intern;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Karma Blackshaw');
$pdf->SetTitle('Intern Certification');
$pdf->SetSubject('Intern Certification');
$pdf->SetKeywords('Intern Certification');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetDefaultMonospacedFont('Helvetica');
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
$pdf->AddPage();
$pdf->SetFont('Helvetica', 'B', 10);
$pdf->writeHTML('Summary of Interns', true, false, true, false, 'C');
$pdf->SetFont('Helvetica', 'I', 7);
$pdf->writeHTML('Date issued: <b>' . Carbon::now()->toDayDateTimeString() . '</b>', true, false, true, false, 'C');
$pdf->MultiCell(0, 5, '', 0, 'C', 0, 1, '', '', true);
$pdf->setCellPaddings($left = '', $top = '1', $right = '', $bottom = '1');
$pdf->SetFont('Helvetica', 'B', 8);

$pdf->Cell(8, 0, 'No', 1, 0, 'C', 0, '', 0);
$pdf->Cell(40, 0, 'Name', 1, 0, 'C', 0, '', 0);
$pdf->Cell(20, 0, 'ID', 1, 0, 'C', 0, '', 0);
$pdf->Cell(15, 0, 'School', 1, 0, 'C', 0, '', 0);
$pdf->Cell(15, 0, 'Course', 1, 0, 'C', 0, '', 0);
$pdf->Cell(15, 0, 'Office', 1, 0, 'C', 0, '', 0);
$pdf->Cell(40, 0, 'Supervisor', 1, 0, 'C', 0, '', 0);
$pdf->Cell(15, 0, 'Date Start', 1, 0, 'C', 0, '', 0);
$pdf->Cell(25, 0, 'Schedule', 1, 0, 'C', 0, '', 0);
$pdf->Cell(20, 0, 'Required Hrs.', 1, 0, 'C', 0, '', 0);
$pdf->Cell(20, 0, 'Hrs Earned', 1, 0, 'C', 0, '', 0);
$pdf->Cell(15, 0, 'Balance', 1, 0, 'C', 0, '', 0);
$pdf->Cell(15, 0, 'Remarks', 1, 1, 'C', 0, '', 0);

$pdf->SetFont('Helvetica', '', 7);

if(isset($_POST['school_id']) || isset($_POST['office_id'])){
    $order = $init->inject($_POST['name']) == 'desc' ? 'DESC' : 'ASC';
    $year_start = $init->inject($_POST['year_start']);
    $year_end = $init->inject($_POST['year_end']);

    $and = '';

    if($year_start > $year_end || empty($year_start || empty($year_end))){
        header('Location: ' . explode('?', $_SERVER['HTTP_REFERER'])[0] . '?error=The year range is invalid!');
        exit();
    } else{

        $years = [];

        if(!empty($year_start) && !empty($year_end)){
            while($year_start <= $year_end){
                $years[] = $year_start;
                $year_start++;
            }

            $years_implode = empty(implode("','", $years)) ? '' : "'" . implode("','", $years) . "'";

            $and =  empty($years_implode) ? '' : "AND year(date_started) IN ($years_implode)";
        }
    }

    if(isset($_POST['school_id'])){
        $school_id = $init->inject($_POST['school_id']);

        $query = "
                    SELECT *, SUBSTRING_INDEX(name, '%', -1) lname
                    FROM trainees
                    JOIN colleges ON trainees.school_id = colleges.school_id
                    JOIN offices ON offices.office_id = trainees.office_id
                    WHERE colleges.school_id = '$school_id'
                ";

    }

    if(isset($_POST['office_id'])){
        $office_id = $init->inject($_POST['office_id']);

        $query = "
                    SELECT *, SUBSTRING_INDEX(name, '%', -1) lname
                    FROM trainees
                    JOIN colleges ON trainees.school_id = colleges.school_id
                    JOIN offices ON offices.office_id = trainees.office_id
                    WHERE offices.office_id = '$office_id'

                ";
    }

    $query .= "
        AND trainees.removed = 0
        $and
        ORDER BY lname $order
    ";

    $sql = $init->getQuery($query);

    foreach($sql as $key => $data):
        $pdf->Cell(8, 0, $key + 1, 1, 0, 'C', 0, '', 0);
        $pdf->Cell(40, 0, fullname($data->name, 'LFM') , 1, 0, 'L', 0, '', 0);
        $pdf->Cell(20, 0, $data->trainee_id, 1, 0, 'L', 0, '', 0);
        $pdf->Cell(15, 0, $init->acronym($data->school) , 1, 0, 'L', 0, '', 0);
        $pdf->Cell(15, 0, $init->acronym($data->course) , 1, 0, 'L', 0, '', 0);
        $pdf->Cell(15, 0, $init->acronym($data->office) , 1, 0, 'L', 0, '', 0);
        $pdf->Cell(40, 0, $data->supervisor, 1, 0, 'L', 0, '', 0);
        $pdf->Cell(15, 0, date("m-d-Y", strtotime($data->date_started)) , 1, 0, 'L', 0, '', 0);
        $pdf->Cell(25, 0, $data->schedule, 1, 0, 'L', 0, '', 0);
        $pdf->Cell(20, 0, $data->hours_required, 1, 0, 'L', 0, '', 0);
        $earnedSQL = $init->getQuery("SELECT SUM(total) total FROM dtr WHERE trainee_id = '$data->trainee_id'");
        foreach($earnedSQL as $earned){
            $earn = floor($earned->total / 60);
        }
        $balance = $data->hours_required - $earn;
        $pdf->Cell(20, 0, $earn, 1, 0, 'L', 0, '', 0);
        $pdf->Cell(15, 0, $balance, 1, 0, 'L', 0, '', 0);
        $pdf->Cell(15, 0, '', 1, 1, 'L', 0, '', 0);
    endforeach;
}

if(isset($_GET['summary'])){
    $print = $init->inject($_POST['print']);
    $name = $init->inject($_POST['name']);
    $year_start = $init->inject($_POST['year_start']);
    $year_end = $init->inject($_POST['year_end']);

    $and = '';

    if($year_start > $year_end || empty($year_start || empty($year_end))){
        header('Location: ' . explode('?', $_SERVER['HTTP_REFERER'])[0] . '?error=The year range is invalid!');
        exit();
    } else{

        $years = [];

        if(!empty($year_start) && !empty($year_end)){
            while($year_start <= $year_end){
                $years[] = $year_start;
                $year_start++;
            }

            $years_implode = empty(implode("','", $years)) ? '' : "'" . implode("','", $years) . "'";

            $and =  empty($years_implode) ? '' : "AND year(date_started) IN ($years_implode)";
        }
    }



    $sql = $init->getQuery("
        SELECT SUBSTRING_INDEX(name, '%', -1) lname, trainee_id, name, school, course, office, supervisor, date_started, schedule, hours_required
        FROM trainees t
        JOIN school s ON s.school_id = t.school_id
        JOIN offices o ON o.office_id = t.office_id
        WHERE t.removed = 0
        $and
        ORDER BY lname $name");


    $number = 1;

    $json['overall'] = [];
    $json['on-going'] = [];
    $json['finished'] = [];

    foreach($sql as $key => $data){
        $left = $data->hours_required - ($intern->getTotalTime($data->trainee_id) / 60);

        if($left <= 0){
            $json['finished'][] = $sql[$key];
        } else{
            $json['on-going'][] = $sql[$key];
        }
    }

    if($print == 'finished'){
        $sql = $json['finished'];
    } elseif($print == 'on-going'){
        $sql = $json['on-going'];
    } else{
        $sql = $sql;
    }

    foreach($sql as $data){
        $pdf->Cell(8, 0, $number, 1, 0, 'C', 0, '', 0);
        $pdf->Cell(40, 0, fullname($data->name, 'LFM') , 1, 0, 'L', 0, '', 0);
        $pdf->Cell(20, 0, $data->trainee_id, 1, 0, 'L', 0, '', 0);
        $pdf->Cell(15, 0, $init->acronym($data->school) , 1, 0, 'L', 0, '', 0);
        $pdf->Cell(15, 0, $init->acronym($data->course) , 1, 0, 'L', 0, '', 0);
        $pdf->Cell(15, 0, $init->acronym($data->office) , 1, 0, 'L', 0, '', 0);
        $pdf->Cell(40, 0, $data->supervisor, 1, 0, 'L', 0, '', 0);
        $pdf->Cell(15, 0, date("m-d-Y", strtotime($data->date_started)) , 1, 0, 'L', 0, '', 0);
        $pdf->Cell(25, 0, $data->schedule, 1, 0, 'L', 0, '', 0);
        $pdf->Cell(20, 0, $data->hours_required, 1, 0, 'L', 0, '', 0);
        $earnedSQL = $init->getQuery("SELECT SUM(total) total FROM dtr WHERE trainee_id = '$data->trainee_id'");
        foreach($earnedSQL as $earned){
            $earn = floor($earned->total / 60);
        }
        $balance = $data->hours_required - $earn;
        $pdf->Cell(20, 0, $earn, 1, 0, 'L', 0, '', 0);
        $pdf->Cell(15, 0, $balance, 1, 0, 'L', 0, '', 0);
        $pdf->Cell(15, 0, '', 1, 1, 'L', 0, '', 0);
        $number++;
    }
}

$pdf->Output('intern_summary_' . date('Y-m-d') . '.pdf', 'I');
ob_end_flush();

