<?php 
require_once 'templates/header.php'; 
require_once abs_sessions('administrator');
use Carbon\Carbon;
$user = new User;

?>
<div class="breadcrumb-holder">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">OJT Manager</a>
            </li>
            <li class="breadcrumb-item active">Users</li>
        </ul>
    </div>
</div>
<section>
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-lg-12 col-md">
                <div class="card table-responsive">
                    <div class="card-header d-flex bd-highlight">
                        <div class="mr-auto bd-highlight h4">User Manager</div>
                        <div class="bd-highlight">
                            <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#modalAddUser"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover table-sm small" id="table_users">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Office</th>
                                    <th>Date Created</th>
                                    <th width="10">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyUserManager">
                                    <?php $sql = $user->getUsers(); ?>
                                    <?php foreach($sql as $data): ?>
                                        <tr>
                                            <td class="text-left pl-3">
                                                <span class="d-block mb-0 font-weight-bold"><?= fullname($data->name); ?></span>
                                                <span class="small text-muted"><?= $data->position; ?></span>
                                            </td>
                                            <td><?= $data->office; ?></td>
                                            <td><?= Carbon::createFromFormat('Y-m-d', $data->date_created)->diffForHumans(); ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-outline-primary btn-sm call_modal_edit_user" data-user_id="<?= $data->user_id; ?>"><i class="fas fa-edit"></i></button>
                                                    <button class="btn btn-outline-danger btn-sm call_modal_remove_user" data-user_id="<?= $data->user_id; ?>" data-user_name="<?= fullname($data->name) ?>"><i class="fas fa-trash"></i></button>
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
<script src="<?= base_assets . 'js/users.js' ?>"></script>