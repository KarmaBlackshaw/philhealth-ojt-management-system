<?php

    include 'templates/header.php';
    require_once abs_sessions('administrator');
    $home = new Home;
?>
<div class="breadcrumb-holder">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">OJT Manager</a>
            </li>
            <li class="breadcrumb-item active">Home</li>
        </ul>
    </div>
</div>
<section>
    <div class="container-fluid mt-5">

        <div class="card border-success">
            <div class="card-header text-center h2 p-2 pb-0 clearfix">
                <div class="card-title">Dashboard</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php if(isset($_GET['error'])) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <?= $_GET['error']; ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <?php endif; ?>
                <div class="card w-100">
                    <div class="card-header">
                        <div class="card-title"><h5>Intern Summary</h5></div>
                    </div>
                    <div class="card-body table-responsive ">
                        <table class="table table-sm table-hover table-striped small" id="tableHomeInternSummary">
                            <thead class="thead-dark">
                                <th>Name</th>
                                <th>School</th>
                                <th>Course</th>
                                <th>Office</th>
                                <th>Supervisor</th>
                                <th>Date Start</th>
                                <th>Schedule</th>
                                <th>Required</th>
                                <th>Earned</th>
                                <th>Balance</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-success">
            <div class="card-header text-center h2 p-2">
                <div class="card-title">College Section</div>
            </div>
        </div>
        <div class="row" id="home_row_college">
            <?php $sql = $home->getColleges(); ?>
            <?php foreach($sql as $college) : $school_id= $college->school_id; ?>
            <div class="col-lg-6">
                <div class="card table-responsive">
                    <div class="card-header d-flex align-items-center">
                        <?php $count= $init->count("SELECT * FROM trainees WHERE school_id = '$college->school_id'"); ?>
                        <div class="p-0 flex-grow-1 bd-highlight">
                            <h5><?= $college->school; ?></h5>
                        </div>
                        <div class="p-2 bd-highlight">
                            <button class="btn btn-outline-dark btn-sm call_modal_print_colleges" data-school_id="<?= $college->school_id; ?>"><i class="fas fa-print"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover small table-colleges">
                            <thead class="thead-dark">
                                <th>Name</th>
                                <th>Course</th>
                                <th>Office</th>
                                <th>Required</th>
                                <th>Remaining</th>
                            </thead>
                            <tbody>
                                <?php $sql= $init->getQuery("
                                    SELECT *
                                    FROM trainees
                                    JOIN offices ON trainees.office_id = offices.office_id
                                    WHERE trainees.school_id = '$school_id'
                                    AND trainees.removed = 0
                                "); ?>
                                <?php foreach($sql as $data) : ?>
                                <tr>
                                    <td>
                                        <span class="d-block"><?= fullname($data->name); ?></span>
                                        <small class="text-muted"><?= $data->trainee_id; ?></small>
                                    </td>
                                    <td>
                                        <?= acronym($data->course); ?></td>
                                    <td>
                                        <?= $init->acronym($data->office); ?></td>
                                    <td>
                                        <?= $data->hours_required; ?></td>
                                    <?php $remaining=0 ; $sql= $init->getQuery("SELECT SUM(total) total FROM dtr WHERE trainee_id = '$data->trainee_id'"); foreach($sql as $sum){ $totals = $sum->total; } $remaining = $data->hours_required - floor($totals / 60); ?>
                                    <td>
                                        <?= $remaining; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?> </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <canvas id="collegeChart" width="400" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-success">
            <div class="card-header text-center h2 p-2">
                <div class="card-title">Office Section</div>
            </div>
        </div>
        <div class="row" id="home_row_office">
            <?php $sql = $home->getOffices(); ?>
            <?php foreach($sql as $office) : $office_id= $office->office_id; ?>
            <div class="col-lg-6">
                <div class="card table-responsive">
                    <div class="card-header d-flex align-items-center">
                        <?php $count= $init->count("SELECT * FROM trainees WHERE office_id = '$office->office_id'"); ?>
                        <div class="p-2 flex-grow-1 bd-highlight">
                            <h5><?= $office->office; ?></h5>
                        </div>
                        <div class="p-2 bd-highlight">
                            <button class="btn btn-outline-dark btn-sm call_modal_print_office" data-office_id="<?= $office->office_id; ?>"><i class="fas fa-print"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover small table-offices">
                            <thead class="thead-dark">
                                <th>Name</th>
                                <th>Course</th>
                                <th>School</th>
                                <th>Required</th>
                                <th>Remaining</th>
                            </thead>
                            <tbody>
                                <?php $sql= $init->getQuery("
                                    SELECT *
                                    FROM trainees
                                    JOIN school ON trainees.school_id = school.school_id
                                    WHERE trainees.office_id = '$office_id'
                                    AND trainees.removed = 0
                                    "); ?>
                                <?php foreach($sql as $data) : ?>
                                <tr>
                                    <td>
                                        <span class="d-block"><?= fullname($data->name); ?></span>
                                        <small class="text-muted"><?= $data->trainee_id; ?></small>

                                        </td>
                                    <td>
                                        <?= acronym($data->course); ?></td>
                                    <td>
                                        <?= acronym($data->school); ?></td>
                                    <td>
                                        <?= $data->hours_required; ?></td>
                                    <?php $remaining=0 ; $sql= $init->getQuery("SELECT SUM(total) total FROM dtr WHERE trainee_id = '$data->trainee_id'"); foreach($sql as $sum){ $totals = $sum->total; } $remaining = $data->hours_required - floor($totals / 60); ?>
                                    <td>
                                        <?= $remaining; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?> </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <canvas id="officeChart" width="400" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include 'templates/footer.php'; ?>
<script src="<?= node('randomcolor/randomColor.js') ?>"></script>
<script src="<?= rel_assets('js/home.js') ?>"></script>