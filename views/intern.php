<?php
   require_once 'templates/header.php';
   require_once abs_sessions('administrator');
   $intern = new Intern;
   use Carbon\Carbon;
   use Carbon\CarbonPeriod;
?>
<div class="breadcrumb-holder">
   <div class="container-fluid">
      <ul class="breadcrumb">
         <li class="breadcrumb-item"><a href="#">OJT Manager</a></li>
         <li class="breadcrumb-item active">On-the-Job Trainees</li>
      </ul>
   </div>
</div>
<section>
   <div class="container-fluid mt-5">
      <div class="row">
         <div class="col-lg-9 col-md">
            <?php if(isset($_GET['error'])) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Error!</strong> <?= $_GET['error']; ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <?php endif; ?>
            <div class="card table-responsive">
               <div class="card-header d-flex bd-highlight">
                  <div class="mr-auto bd-highlight">
                     <h4>Intern Summary Table</h4>
                  </div>
                  <div class="bd-highlight">
                     <button class="btn btn-outline-primary btn-sm" data-target="#modalAddOJT" data-toggle="modal"><i class="fas fa-plus"></i></button>
                     <button class="btn btn-outline-dark btn-sm" data-target="#modal_print_intern_summary" data-toggle="modal"><i class="fas fa-print"></i></button>
                     <button class="btn btn-outline-success btn-sm" id="btn_refresh"><i class="fas fa-sync-alt"></i></button>
                  </div>
               </div>
               <div class="card-body px-2">
                  <table class="table table-hover table-striped table-sm small" id="table_intern">
                     <thead class="thead-dark">
                        <tr>
                           <th>Name</th>
                           <th>School</th>
                           <th>Course</th>
                           <th>Office</th>
                           <th>Date of Arrival</th>
                           <th>Schedule</th>
                           <th>Required</th>
                           <th>Status</th>
                           <th width="5"></th>
                        </tr>
                     </thead>
                  </table>
               </div>
            </div>
         </div>
         <div class="col-lg-3">
            <div class="card table-responsive">
               <div class="card-header d-flex bd-highlight">
                  <div class="mr-auto bd-highlight h4">Colleges</div>
                  <div class="bd-highlight">
                     <button class="btn btn-outline-primary btn-sm" data-target="#modalAddSchool" data-toggle="modal"><i class="fas fa-plus"></i></button>
                  </div>
               </div>
               <div class="card-body">
                  <input type="text" class="form-control form-control-sm mb-2" placeholder="Search..." id="searchSchool">
                  <table class="table table-hover table-striped table-sm small" id="table_school">
                     <thead class="thead-dark">
                        <th>Name</th>
                        <th width="5"></th>
                     </thead>
                     <tbody id="tbodySchool">
                          <?php $sql = $init->getQuery("SELECT * FROM school WHERE removed = 0"); ?>
                          <?php foreach ($sql as $data): ?>
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
                        <?php endforeach; ?>
                     </tbody>
                  </table>
               </div>
            </div>
            <div class="card table-responsive">
               <div class="card-header d-flex bd-highlight">
                  <div class="mr-auto bd-highlight">
                     <h4>Office</h4>
                  </div>
                  <div class="bd-highlight"> <button class="btn btn-outline-primary btn-sm" data-target="#modal_add_office" data-toggle="modal"><i class="fas fa-plus"></i></button> </div>
               </div>
               <div class="card-body">
                  <input type="text" class="form-control form-control-sm mb-2" placeholder="Search..." id="searchOffice">
                  <table class="table table-striped table-hover table-sm small" id="table_office">
                     <thead class="thead-dark">
                        <th>Name</th>
                        <th width="5"></th>
                     </thead>
                     <tbody id="tbodyOffice">
                           <?php $sql = $init->getQuery("SELECT * FROM offices WHERE removed = 0"); ?>
                           <?php foreach ($sql as $data): ?>
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
                        <?php endforeach; ?>

                     </tbody>
                  </table>
               </div>
            </div>
            <div class="card table-responsive">
               <div class="card-header d-flex bd-highlight">
                  <div class="mr-auto bd-highlight">
                     <h4>Holidays</h4>
                  </div>
                  <div class="bd-highlight"> <button class="btn btn-outline-primary btn-sm" data-target="#modal_add_holiday" data-toggle="modal"><i class="fas fa-plus"></i></button> </div>
               </div>
               <div class="card-body">
                  <input type="text" class="form-control form-control-sm mb-2" placeholder="Search..." id="searchHoliday">
                  <table class="table table-striped table-hover table-sm small" id="table_holiday">
                     <thead class="thead-dark">
                        <th>Name</th>
                        <th width="5"></th>
                     </thead>
                     <tbody id="tbodyHoliday">
                          <?php $sql = $intern->getHolidays(); ?>
                          <?php foreach ($sql as $data): ?>
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
                        <?php endforeach; ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<?php require_once 'templates/footer.php'; ?>
<script src="<?= rel_assets('js/intern.js') ?>"></script>