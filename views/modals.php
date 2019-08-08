<?php 
    require_once dirname(__DIR__) . '/lib/init.php'; 
    use Carbon\Carbon;
?>
<div class="modal fade" id="modalAddOJT">
    <div class="modal-dialog modal-lg">
        <form id="form_add_trainee" autocomplete="off">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title font-weight-bold">Register Intern</div>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="small text-muted">ID</label>
                            <input type="number" class="form-control" name="id" placeholder="Enter biometric ID">
                        </div>
                        <div class="form-group">
                            <label class="small text-muted">Name</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="First" name="fname">
                                <input type="text" class="form-control" placeholder="Middle" name="mname">
                                <input type="text" class="form-control" placeholder="Last" name="lname"> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="small text-muted">School</label>
                            <select name="school_id" class="form-control">
                                <option value="">Choose school</option>
                                <?php $school = $home->getColleges(); ?>
                                <?php foreach($school as $data) : ?>
                                    <option value="<?= $data->school_id; ?>"><?= $data->school; ?></option>
                                <?php endforeach; ?> 
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="small text-muted">Gender</label>
                            <select name="gender" class="form-control">
                                <option value="">Choose gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="small text-muted">Course</label>
                            <input type="text" class="form-control" name="course" placeholder="Enter course">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="small text-muted">Office</label>
                            <select name="office_id" class="form-control">
                                <option value="">Choose office</option>
                                <?php $offices = $home->getoffices(); ?>
                                <?php foreach($offices as $data) : ?>
                                <option value="<?= $data->office_id; ?>">
                                    <?= $data->office; ?></option>
                                <?php endforeach; ?> 
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="small text-muted">Supervisor</label>
                            <input type="text" class="form-control" name="supervisor" placeholder="Enter Supervisor">
                        </div>
                        <div class="form-group">
                            <label class="small text-muted">Date Started</label>
                            <input type="date" class="form-control" name="date_started" value="<?= Carbon::now()->format('Y-m-d') ?>">
                        </div>
                        <div class="form-group">
                            <label class="small text-muted">Schedule</label>
                            <input type="text" class="form-control" name="schedule" placeholder="Enter schedule">
                        </div>
                        <div class="form-group">
                            <label class="small text-muted">Required</label>
                            <input type="text" class="form-control" placeholder="Hours required" name="required" placeholder="Enter required hours">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-sm" type="submit">Save</button>
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalAddUser">
    <div class="modal-dialog">
        <form action="" id="formAddUser" autocomplete="off">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title font-weight-bold">Add User</div>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="text-muted small">Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="fname" placeholder="First" required>
                            <input type="text" class="form-control" name="mname" placeholder="Middle" required>
                            <input type="text" class="form-control" name="lname" placeholder="Last" required> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-muted small">Office</label>
                        <select name="office" class="form-control" required>
                            <option value="">Choose office</option>
                            <?php $offices = $home->getoffices(); ?>
                            <?php foreach($offices as $data) : ?>
                            <option value="<?= $data->office_id; ?>">
                                <?= $data->office; ?></option>
                            <?php endforeach; ?> 
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="text-muted small">Position</label>
                        <input type="text" class="form-control" name="position" placeholder="Position" required>
                    </div>
                    <div class="form-group">
                        <label class="text-muted small">Username</label>
                        <input type="text" class="form-control" name="username" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <label class="text-muted small">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                    <input type="hidden" name="hiddenAddUser"> 
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-sm" type="button" onclick="addUser()" data-dismiss="modal">Save</button>
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal_print_intern_summary">
    <div class="modal-dialog">
        <form id="formPrintInternSummary" action="print_intern_summary.php?summary" target="_blank" method="POST" autocomplete="off">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title font-weight-bold-weight-bold">Print Options</div>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="text-muted">Status</label>
                        <div class="btn-group text-center btn-group-toggle w-100" data-toggle="buttons">
                            <label class="btn btn-outline-dark btn-sm active ">
                                <input type="radio" name="print" value="overall" id="option1" autocomplete="off" checked> Overall </label>
                            <label class="btn btn-outline-dark btn-sm">
                                <input type="radio" name="print" value="on-going" id="option2" autocomplete="off"> On-going </label>
                            <label class="btn btn-outline-dark btn-sm">
                                <input type="radio" name="print" value="finished" id="option3" autocomplete="off"> Finished </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-muted">Name</label>
                        <select name="name" class="form-control">
                            <option value="asc">Ascending</option>
                            <option value="desc">Descending</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="text-muted">Range</label>
                        <div class="input-group">
                            <select name="year_start" class="form-control">
                                <option value="">Year Start</option>
                                <?php foreach($years = range(3000, 2018) as $year) : ?>
                                    <option value="<?= $year; ?>"><?= $year; ?></option>
                                <?php endforeach; ?> 
                            </select>
                            <select name="year_end" class="form-control">
                                <option value="">Year End</option>
                                <?php foreach($years = range(3000, 2018) as $year) : ?>
                                    <option value="<?= $year; ?>"><?= $year; ?></option>
                                <?php endforeach; ?> 
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-sm" type="submit">Print</button>
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- School -->
<div class="modal fade" id="modalUpdateSchool">
  <div class="modal-dialog">
    <form id="form_edit_school" autocomplete="off">
        <div class="modal-content">
          <div class="modal-header">
            <div class="modal-title font-weight-bold">Update School</div>
            <button class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
                <label class="small text-muted">School</label>
                <input type="text" class="form-control" placeholder="Name of College/Institution" name="school_name" id="school_name" required>
            </div>
            <div class="form-group">
                <label class="small text-muted">Address</label>
                <input type="text" class="form-control" placeholder="College/Institution address" name="school_address" id="school_address" required>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" id="school_id" name="school_id">
            <button class="btn btn-primary btn-sm" type="submit">Save</button>
            <button class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
          </div>
        </div>
    </form>
  </div>
</div>

<div class="modal fade" id="modalRemoveSchool">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_remove_school" autocomplete="off">
                <div class="modal-header">
                    <div class="modal-title font-weight-bold">Remove School</div>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body h6">
                    Are you sure to remove <b id="school_name"></b> ?
                </div>
                <div class="modal-footer">
                  <input type="hidden" name="school_id" id="school_id">
                  <button class="btn btn-primary btn-sm" type="submit">Yes</button>
                  <button class="btn btn-secondary btn-sm" data-dismiss="modal">No</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAddSchool">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formAddSchool" autocomplete="off">
                <div class="modal-header">
                    <div class="modal-title font-weight-bold">Add School</div>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="small text-muted">Name</label>
                        <input type="text" class="form-control" placeholder="Name of college/institution" name="school" required>
                    </div>
                    <div class="form-group">
                        <label class="small text-muted">Address</label>
                        <input type="text" class="form-control" placeholder="Address of college/institution" name="school_address" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-sm" type="submit">Save</button>
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End School -->

<!-- Office -->
<div class="modal fade" id="modal_edit_office">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_edit_office" autocomplete="off">
                <div class="modal-header">
                  <div class="modal-title font-weight-bold">Update Office</div>
                  <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="small text-muted">Office</label>
                        <input type="text" class="form-control" id="office" name="office">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="office_id" id="office_id">
                  <button class="btn btn-primary btn-sm" type="submit">Yes</button>
                  <button class="btn btn-secondary btn-sm" data-dismiss="modal">No</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_remove_office">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_remove_office">
                <div class="modal-header">
                    <div class="modal-title font-weight-bold">Remove office</div>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body h6">
                    Are you sure to remove <b id="office"></b> ?
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="office_id" name="office_id">
                  <button class="btn btn-primary btn-sm" type="submit">Yes</button>
                  <button class="btn btn-secondary btn-sm" data-dismiss="modal">No</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_add_office">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_add_office" autocomplete="off">
                <div class="modal-header">
                    <div class="modal-title font-weight-bold">Add Office</div>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="small text-muted">Office Name</label>
                        <input type="text" class="form-control" placeholder="Name of office" name="office" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-sm" type="submit">Save</button>
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Office -->

<!-- Holidays -->
<div class="modal fade" id="modal_edit_holiday">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_edit_holiday" autocomplete="off">
                <div class="modal-header">
                    <div class="modal-title font-weight-bold">Update Holiday</div>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="text-muted small">Holiday</label>
                        <input type="text" class="form-control" id="holiday" name="holiday" required>
                    </div>
                    <div class="form-group">
                        <label class="text-muted small">Date</label>
                        <input type="date" class="form-control" id="holiday_date" name="holiday_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="holiday_id" name="holiday_id">
                    <button class="btn btn-primary btn-sm" type="submit">Save</button>
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </form> 
        </div>
    </div>
</div>

<div class="modal fade" id="modal_remove_holidy">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_remove_holiday">
                <div class="modal-header">
                  <div class="modal-title font-weight-bold">Remove Holiday</div>
                  <button class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body h6">
                Are you sure to remove <b id="holiday_name"></b> ?
              </div>
              <div class="modal-footer">
                <input type="hidden" id="holiday_id" name="holiday_id">
                <button class="btn btn-primary btn-sm" type="submit">Yes</button>
                <button class="btn btn-secondary btn-sm" data-dismiss="modal">No</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_add_holiday">
    <div class="modal-dialog">
        <form id="form_add_holiday" autocomplete="off">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title font-weight-bold">Add Holiday</div>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="small text-muted">Holiday</label>
                        <input type="text" class="form-control" placeholder="Name of holiday" name="addHolidayName" required>
                    </div>
                    <div class="form-group">
                        <label class="small text-muted">Holiday</label>
                        <input type="date" class="form-control" name="addHolidayDate" required>
                    </div>
                    <input type="hidden" name="hiddenAddHoliday"> </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-sm" type="submit">Save</button>
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End Holidays -->

<!-- Intern -->
<div class="modal fade" id="modal_remove_dtr_summary">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_remove_dtr_summary">
                <div class="modal-header">
                    <div class="modal-title h4">Remove DTR</div>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span class="h6">Are you sure to remove this DTR dated <b id="month_year"></b></span>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="month" name="month">
                    <input type="hidden" id="year" name="year">
                    <input type="hidden" id="id" name="id">
                    <button class="btn btn-primary btn-sm" type="submit">Yes</button>
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">No</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Intern -->

<!-- Print Intern by Office-->
<div class="modal fade" id="modal_print_office">
    <div class="modal-dialog">
        <form action="print_intern_summary.php" method="POST" target="_blank">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title h4">Print Options</div>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="small text-muted">Sort Name</label>
                        <select name="name" class="form-control">
                            <option value="asc">Ascending</option>
                            <option value="desc">Descending</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="text-muted">Range</label>
                        <div class="input-group">
                            <select name="year_start" class="form-control">
                                <option value="">Year Start</option>
                                <?php foreach($years = range(3000, 2018) as $year) : ?>
                                    <option value="<?= $year; ?>"><?= $year; ?></option>
                                <?php endforeach; ?> 
                            </select>
                            <select name="year_end" class="form-control">
                                <option value="">Year End</option>
                                <?php foreach($years = range(3000, 2018) as $year) : ?>
                                    <option value="<?= $year; ?>"><?= $year; ?></option>
                                <?php endforeach; ?> 
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="office_id" id="modal_print_office_id">
                    <button class="btn btn-primary btn-sm" type="submit">Print</button>
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal_print_college">
    <div class="modal-dialog">
        <form action="print_intern_summary.php" method="POST" target="_blank">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title h4">Print Options</div>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="small text-muted">Sort Name</label>
                        <select name="name" class="form-control">
                            <option value="asc">Ascending</option>
                            <option value="desc">Descending</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="text-muted">Range</label>
                        <div class="input-group">
                            <select name="year_start" class="form-control">
                                <option value="">Year Start</option>
                                <?php foreach($years = range(3000, 2018) as $year) : ?>
                                    <option value="<?= $year; ?>"><?= $year; ?></option>
                                <?php endforeach; ?> 
                            </select>
                            <select name="year_end" class="form-control">
                                <option value="">Year End</option>
                                <?php foreach($years = range(3000, 2018) as $year) : ?>
                                    <option value="<?= $year; ?>"><?= $year; ?></option>
                                <?php endforeach; ?> 
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="school_id" id="modal_print_school_id">
                    <button class="btn btn-primary btn-sm" type="submit">Print</button>
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal_edit_user">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_edit_user">
                <div class="modal-header">
                    <div class="modal-title h4">Edit User</div>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="small text-muted">Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="edit_user_fname" name="fname">
                            <input type="text" class="form-control" id="edit_user_mname" name="mname">
                            <input type="text" class="form-control" id="edit_user_lname" name="lname">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="small text-muted">Office</label>
                        <select id="edit_user_office_id" name="office_id" class="form-control">
                            <option value="">Choose office</option>
                            <?php $offices = $home->getoffices(); ?>
                            <?php foreach($offices as $data) : ?>
                            <option value="<?= $data->office_id; ?>"><?= $data->office; ?></option>
                            <?php endforeach; ?> 
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="small text-muted">Position</label>
                        <input type="text" class="form-control" id="edit_user_position" name="position">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="edit_user_id" name="user_id">
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_remove_user">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_remove_user">
                <div class="modal-header">
                    <div class="modal-title h4">Remove User</div>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-left">
                    <h6>Are you sure to remove <b id="remove_user_name"></b>?</h6>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="remove_user_id" name="user_id">
                    <button type="submit" class="btn btn-primary btn-sm">Yes</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">No</button>
                </div>
            </form>
        </div>
    </div>
</div>
