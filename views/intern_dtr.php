<?php 
include 'templates/header.php'; 
require_once abs_sessions('administrator');
$intern = new Intern;

if(!isset($_GET[ 'id']) || empty($_GET['id'])){ 
    header( 'Location: intern.php'); 
    die(); 
} else{
    $id = $_GET['id'];

    if(!$intern->getInternProfile($id)){
        header( 'Location: intern.php'); 
        die(); 
    } else{
        $profile = $intern->getInternProfile($id);
    }
}

?>
<input type="hidden" id="trainee_id" value="<?= $id ?>">
<div class="breadcrumb-holder">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">OJT Manager</a>
            </li>
            <li class="breadcrumb-item pointer" onclick="window.location.href = 'intern.php'">On-the-Job Trainees</li>
            <li class="breadcrumb-item active">DTR Manager</li>
        </ul>
    </div>
</div>
<section>
    <div class="container-fluid">
        <p class="display-4"><?= $profile['name']; ?></p>
        <div class="form-row">
            <div class="col-lg-4">
                <div class="card table-responsive">
                    <div class="card-header d-flex bd-highlight">
                        <div class="mr-auto bd-highlight h4">DTR Manager</div>
                        <div class="bd-highlight">
                            <div class="input-group">
                                <select name="select_month" id="select_month" class="form-control form-control-sm">
                                    <option value="">Month</option>
                                    <?php for($m = 1; $m <= 12; ++$m) : $month= date( 'F', mktime(0, 0, 0, $m, 1)); ?>
                                        <option value="<?= str_pad($m, 2, 0, STR_PAD_LEFT); ?>"><?= $month; ?></option>
                                    <?php endfor; ?> </select>
                                <select name="select_year" id="select_year" class="form-control form-control-sm ">
                                    <option value="">Year</option>
                                    <?php foreach($years = range(3000, 2018) as $year) : ?>
                                        <option value="<?= $year; ?>"><?= $year; ?></option>
                                    <?php endforeach; ?> </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover table-sm small" id="table_dtr_summary">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-left">Date</th>
                                    <th class="text-left">Hours</th>
                                    <th width="10%"></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <hr>
                        <div class="col px-1 small">
                            <span>Total Time <small class="font-weight-light">(Accurate)</small>: </span>
                            <span class="float-right font-weight-bold" id="total_time">NULL</span>
                        </div>
                    </div>
                </div>
                <div class="card table-responsive">
                    <div class="card-header d-flex bd-highlight"> <span class="h4">Remarks</span> </div>
                    <div class="card-body">
                        <canvas id="myChart" width="400" height="400"></canvas>
                        <hr>
                        <table class="table table-striped table hover table-sm small">
                            <thead class="thead-dark text-center small">
                                <th>Date</th>
                                <th>Absent</th>
                                <th>Tardy</th>
                                <th>Overtime</th>
                                <th>Undertime</th>
                            </thead>
                            <tbody id="tbodyAbsents">
                                <td colspan="5" class="text-center font-italic">Nothing to show!</td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <form id="form_dtr">
                    <div class="col-lg">
                        <div class="card table-responsive" id="dtr_card">
                            <div class="card-header d-flex bd-highlight"> <span id="internDTRMonth" class="h4">Choose Month</span>
                                <input type="hidden" id="hiddenID" value="<?= $_GET['id']; ?>"> </div>
                            <div class="card-body">
                                <div class="alert alert-danger alert-dismissible fade show hidden" id="alert">
                                  <span id="alert_message"></span>
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <table class="table table-bordered table-hover table-sm dtr" id="DTRTable">
                                    <thead class="thead-light text-center">
                                        <tr>
                                            <th rowspan="2" width="2%">No.</th>
                                            <th rowspan="2" width="10%">Days</th>
                                            <th colspan="2">Morning</th>
                                            <th colspan="2">Afternoon</th>
                                            <th rowspan="2" width="10%">Total</th>
                                            <th rowspan="2">Remarks</th>
                                        </tr>
                                        <tr>
                                            <th class="small font-italic" width="15%"><b>In</b> (AM)</th>
                                            <th class="small font-italic" width="15%"><b>Out</b> (AM)</th>
                                            <th class="small font-italic" width="15%"><b>In</b> (PM)</th>
                                            <th class="small font-italic" width="15%"><b>Out</b> (PM)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyDate">
                                        <tr>
                                            <td colspan="8" class="small font-italic text-center">Nothing to show!</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button class="btn btn-sm btn-primary w-100" type="submit">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once 'templates/footer.php'; ?>
<script src="<?= node('lodash/lodash.min.js') ?>"></script>


<script src="<?= rel_assets('js/intern.js') ?>"></script>
<script>
    load_total_hours();
    chartAbsents();
    loadTableAbsents();
    load_table_dtr_summary();
</script>