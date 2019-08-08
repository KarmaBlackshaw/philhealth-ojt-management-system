<?php  

include 'tcpdf.php';

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$space = '&nbsp;&nbsp;&nbsp;&nbsp;';


$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Ernie Jeash C. Villahermosa');
$pdf->SetTitle('Intern Certification');
$pdf->SetSubject('Intern Certification');
$pdf->SetKeywords('Intern Certification');

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
$pdf->writeHTML('Regional Office VIII', true, false, true, false, 'C');
$pdf->writeHTML('167 P. Burgos Street, Tacloban City', true, false, true, false, 'C');
$pdf->writeHTML('Telefax: (053) 523-8283', true, false, true, false, 'C');
$pdf->writeHTML('<u>www.philhealth.gov.ph</u> &nbsp; Email: hr.pro8@philhealth.gov.ph', true, false, true, false, 'C');

$pdf->writeHTMLCell(0, 15, '', '', '', '', 1, 0, false, 'L', false);

$pdf->SetFont('Times', 'BU', 18);
$pdf->writeHTML('C E R T I F I C A T  I O N', true, false, true, false, 'C');

$pdf->writeHTMLCell(0, 15, '', '', '', '', 1, 0, false, 'L', false);

$pdf->SetFont('Times', '', 12);
$pdf->writeHTML($space . 'THIS IS TO CERTIFY that <u><b>Ms. Emmyrose D. Daloso</b></u>, a <b><u>Bachelor of Science in Information Technology</u></b> student of <b><u>Eastern Visayas State University</u></b> has rendered her office practice under the <b><u>Health Care Delivery Management Division</u></b> of this office from <b><u>November 17, 2017 to March 16, 2018</u></b>. She has rendered a total of <b><u>Four Hundred Eighty Six (486 Hours)</u></b> of actual practicum service in this office.', true, false, true, false, 'J');

$pdf->writeHTMLCell(0, 10, '', '', '', '', 1, 0, false, 'L', false);

$pdf->writeHTML($space . 'This certification is issued upon the request of <b><u>Ms. Daloso</u></b> for purposes of curriculum completion.', true, false, true, false, 'J');

$pdf->writeHTMLCell(0, 10, '', '', '', '', 1, 0, false, 'L', false);

$pdf->writeHTML($space . 'Issued this <b><u>16th day of March 2018.</u></b>', true, false, true, false, 'J');

$pdf->writeHTMLCell(0, 20, '', '', '', '', 1, 0, false, 'L', false);

$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(80, 0, '', 0, 0, 'C', 0, '', 0);
$pdf->Cell(80, 0, 'EMMANUEL C. MONTILLA', 0, 1, 'C', 0, '', 0);
$pdf->SetFont('Times', 'I', 8);
$pdf->Cell(80, 0, '', 0, 0, 'C', 0, '', 0);
$pdf->Cell(80, 0, 'Human Resource Management Officer III', 0, 1, 'C', 0, '', 0);

$pdf->writeHTMLCell(0, 50, '', '', '', '', 1, 0, false, 'L', false);

$pdf->SetFont('Times', 'I', 8);
$pdf->writeHTML('Not validate without Seal', true, false, true, false, 'J');

$pdf->writeHTMLCell(0, 68, '', '', '', '', 1, 0, false, 'L', false);
$pdf->writeHTML('<hr>', true, false, true, false, 'J');

$pdf->SetFont('Times', '', 7);
$pdf->Cell(40, 0, 'teamphilhealth', 0, 0, 'C', 0, '', 0);
$pdf->Cell(40, 0, 'www.facebook.com/PhilHealth', 0, 0, 'C', 0, '', 0);
$pdf->Cell(40, 0, 'www.youtube.com/teamphilhealth', 0, 0, 'C', 0, '', 0);
$pdf->Cell(40, 0, 'actioncenter@philhealth.gov.ph', 0, 0, 'C', 0, '', 0);

//Close and output PDF document
$pdf->Output('Daloso Certificate.pdf', 'I');