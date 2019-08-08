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
        <form>
        <div class="row">
            <div class="col-lg-12 col-md">
                <div class="card">
                    <div class="card-header d-flex bd-highlight">
                        <div class="mr-auto bd-highlight h4">General Settings</div>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="text-muted small">Region</label>
                                    <div class="input-group mb-3">
                                      <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2">
                                      <div class="input-group-append">
                                        <button class="btn btn-outline-success" type="button" id="button-addon2"><i class="fas fa-pen"></i></button>
                                      </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="text-muted small">Address</label>
                                    <input type="text" class="form-control" autocomplete="nope">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="text-muted small">Telefex</label>
                                    <input type="text" class="form-control" autocomplete="nope">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="text-muted small">E-mail</label>
                                    <input type="text" class="form-control" autocomplete="nope">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="text-muted small">PhilHealth Logo</label>
                                    <input type="text" class="form-control" autocomplete="nope">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="text-muted small">UHC Logo</label>
                                    <input type="text" class="form-control" autocomplete="nope">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</section>

<?php require_once 'templates/footer.php'; ?>
<script src="<?= rel_assets('js/settings.js') ?>"></script>