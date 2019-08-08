<?php 
  require_once 'lib/init.php';
?>

<link rel="icon" href="<?= rel_assets('images/if_meteorite_3285298.ico') ?>">

  <!-- assets -->
  <link rel="stylesheet" href="<?= rel_assets('css/animate.css') ?>">
  <link rel="stylesheet" href="<?= rel_assets('css/fontawesome.min.css') ?>">
  <link rel="stylesheet" href="<?= rel_assets('css/hover-min.css') ?>">
  <link rel="stylesheet" href="<?= rel_assets('css/notify.min.css') ?>">
  <link rel="stylesheet" href="<?= rel_assets('css/bootstrap.min.css') ?>">

  <link rel="stylesheet" href="<?= rel_assets(dataTables/dataTables.bootstrap.min.css) ?>">
  <link rel="stylesheet" href="<?= rel_assets('css/style.css') ?>">

  <div class="col-md-3"></div>
  <div class="col-md">
    <center>
      <div class="card w-50 hvr-bob">
        <div class="card-header text-center bg-dark">
          <h5 class="text-white font-weight-light d-block mb-0">On-the-Job Trainee Manager</h5>
          <small class="text-muted font-weight-italic">PhilHealth Tacloban City</small>
        </div>
        <div class="card-body">
          <?php if(isset($_GET['error'])) : ?>
            <?php if(array_key_exists($_GET['error'], $login_errors)) : ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <b>Error!</b> <?php echo $login_errors[$_GET['error']]; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <?php endif; ?>
          <?php endif; ?>
          <form method="POST" action="controllers/LoginsController.php" autocomplete="off">
            <div class="form-group">
              <label class="text-muted small float-left">Username</label>
              <input type="text" name="username" class="form-control" placeholder="Enter your username" autocomplete="off" value="">
            </div>
            <div class="form-group">
              <label class="text-muted small float-left">Password</label>
              <input type="password" name="password" class="form-control" placeholder="Enter your password" autocomplete="off" value="">
            </div>
            <button type="submit" name="login" class="btn btn-outline-dark btn-block">Login</button>
          </form>
        </div>
        <div class="card-footer bg-dark ">
          <small class="text-muted">version 7.0</small>
        </div>
      </div>
    </center>
  </div>
  <div class="col-md-3"></div>