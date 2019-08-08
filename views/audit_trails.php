<?php

require_once 'templates/header.php'; 
require_once abs_sessions('administrator');
use Carbon\Carbon;

?> 
<div class="breadcrumb-holder">
   <div class="container-fluid">
      <ul class="breadcrumb">
         <li class="breadcrumb-item"><a href="#">OJT Manager</a></li>
         <li class="breadcrumb-item active">Audit Trails</li>
      </ul>
   </div>
</div>
<section>
   <div class="container-fluid mt-5">
      <div class="row">
         <div class="col-lg col-md">
            <div class="card table-responsive">
                <div class="card-header d-flex bd-highlight">
                    <div class="mr-auto bd-highlight h4">Audit Trails</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                       <table class="table table-striped table-hover small">
                          <thead class="thead-dark">
                             <th>User</th>
                             <th>Action</th>
                             <th>Subject</th>
                             <th>Date</th>
                          </thead>
                          <tbody id="tbodyAuditTrails">
                             <?php $sql = $init->getQuery("SELECT * FROM audit_trails audit JOIN users ON audit.user_id = users.user_id ORDER BY audit.audit_id DESC"); ?>
                             <?php foreach($sql as $data) : ?>
                                <tr>
                                   <td class="py-2">
                                      <span class="d-block"><?= fullname($data->name) ?></span>
                                   </td>
                                   <td class="py-1"><?= $data->action;?></td>
                                   <td class="py-1"><?= $data->object == 0 ? 'No item changed' : $data->object;?></td>
                                   <td class="py-1"><?= Carbon::createFromFormat('Y-m-d H:i:s', $data->date_time)->diffForHumans(); ?></td>
                                </tr>
                             <?php endforeach; ?>
                          </tbody>
                       </table>
                    </div>
                </div>
            </div>
         </div>
      </div>
   </div>
</section> 
<?php require_once 'templates/footer.php'; ?> 