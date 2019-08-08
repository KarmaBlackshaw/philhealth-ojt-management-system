<?php

require_once 'templates/header.php';
require_once abs_sessions('administrator');
$intern = new Intern;
$home = new Home;

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

<div class="breadcrumb-holder">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">OJT Manager</a>
            </li>
            <li class="breadcrumb-item pointer" onclick="window.location.href = 'intern.php'">On-the-Job Trainees</li>
            <li class="breadcrumb-item active">OJT Information</li>
        </ul>
    </div>
</div>
<section>
    <div class="container-fluid">
        <div class="card mt-5">
            <form id="editTrainee">
                <div class="card-header">
                    <div class="container-fluid h4">Intern Profile</div>
                </div>
                <div class="card-body">
                    <input type="hidden" value="<?= $id; ?>" name="hiddenID">
                    <div class="row container-fluid">
                        <div class="col-lg-6">
                            <!-- Student Id -->
                            <div class="form-group">
                                <label class="small text-muted">ID</label>
                                <input type="text" class="form-control" value="<?= $id; ?>" name="trainee_id" placeholder="Trainee ID">
                            </div>

                            <!-- Name -->
                            <div class="form-row">
                                <div class="form-group col">
                                    <label class="small text-muted">First Name</label>
                                    <input type="text" class="form-control" value="<?= $profile['fname'] ?>" name="fname" placeholder="First Name">
                                </div>
                                <div class="form-group col">
                                    <label class="small text-muted">Middle Name</label>
                                    <input type="text" class="form-control" value="<?= $profile['mname'] ?>" name="mname" placeholder="Middle Name">
                                </div>
                                <div class="form-group col">
                                    <label class="small text-muted">Last Name</label>
                                    <input type="text" class="form-control" value="<?= $profile['lname'] ?>" name="lname" placeholder="Last Name">
                                </div>
                            </div>

                            <!-- Gender -->
                            <div class="form-group">
                                <label class="small text-muted">Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="">Choose Gender</option>
                                    <option value="male" <?= $profile['gender'] == 'male' ? 'selected' : ''; ?>>Male</option>
                                    <option value="female" <?= $profile['gender'] == 'female' ? 'selected' : ''; ?>>Female</option>
                                </select>
                            </div>

                            <!-- School -->
                            <div class="form-group">
                                <label class="small text-muted">School</label>
                                <select name="school" class="form-control">
                                    <option value="">Choose School</option>
                                    <?php $sql = $home->getColleges(); ?>
                                    <?php foreach ($sql as $data): ?>
                                        <option value="<?= $data->school_id; ?>" <?=( $data->school_id == $profile['school_id']) ? 'selected' : ''; ?>>
                                            <?= $data->school; ?></option>
                                    <?php endforeach; ?> 
                                </select>
                            </div>

                            <!-- Course -->
                            <div class="form-group">
                                <label class="small text-muted">Course</label>
                                <input type="text" class="form-control" value="<?= $profile['course']; ?>" name="course" placeholder="Course">
                            </div>

                        </div>
                        <div class="col-lg-6">

                            <!-- Office -->
                            <div class="form-group">
                                <label class="small text-muted">Office</label>
                                <select name="office" class="form-control">
                                    <option value="">Choose Office</option>
                                    <?php $sql = $home->getOffices(); ?>
                                    <?php foreach ($sql as $data) : ?>
                                        <option value="<?= $data->office_id; ?>" <?=( $data->office_id == $profile['office_id']) ? 'selected' : ''; ?>>
                                            <?= $data->office; ?></option>
                                    <?php endforeach; ?> 
                                </select>
                            </div>

                            <!-- Supervisor -->
                            <div class="form-group">
                                <label class="small text-muted">Supervisor</label>
                                <input type="text" class="form-control" value="<?= $profile['supervisor']; ?>" name="supervisor" placeholder="Supervisor">
                            </div>

                            <!-- Schedule -->
                            <div class="form-group">
                                <label class="small text-muted">Schedule</label>
                                <input type="text" class="form-control" value="<?= $profile['schedule']; ?>" name="schedule" placeholder="Schedule">
                            </div>

                            <!-- Hours Required -->
                            <div class="form-group">
                                <label class="small text-muted">Required</label>
                                <input type="text" class="form-control" value="<?= $profile['hours_required']; ?> hrs." name="required" placeholder="Hours Required">
                            </div>
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="form-group col">
                                        <label class="small text-muted">Date of Arrival</label>
                                        <input type="text" class="form-control" value="<?= $profile['date_started']; ?>" readonly>
                                    </div>
                                    <div class="form-group col">
                                        <label class="small text-muted">Estimated Date of Completion</label>
                                        <input type="text" class="form-control" value="<?= $profile['expected_month']; ?>" readonly>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white pt-0 pr-5 text-right">
                    <div class="container-fluid">
                        <button class="btn btn-primary btn-sm mt-3" type="submit">Update</button>
                        <button class="btn btn-secondary btn-sm mt-3" type="button" data-toggle="modal" data-target="#modalRemoveTrainee">Remove</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<div class="modal fade" id="modalRemoveTrainee">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title h4">Remove Intern</div>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body"> <span class="h6">Are you sure to remove <b><?= $profile['name']; ?></b>?</span> </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" type="button" onclick="removeIntern(<?= $id; ?>)" data-dismiss="modal">Yes</button>
                <button class="btn btn-secondary btn-sm" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
<script src="<?= rel_assets('js/intern_information.js') ?>"></script>