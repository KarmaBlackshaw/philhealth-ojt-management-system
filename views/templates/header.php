<?php  

require_once dirname(dirname(__DIR__)) . '/lib/init.php';
$home = new Home;
$web = gethostbyname('');
$directory = explode('\\', dirname(__DIR__));
$root = $directory[3];

define('base', $_SERVER['DOCUMENT_ROOT'] . '/'. $root .'/');
// define('base_assets', 'http://'. $web .':8080//'. $root .'/assets/');
define('base_assets', 'http://'. $web .'/'. $root .'/assets/');

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>OJT Manager</title>
	<!-- icon -->
	<link rel="icon" href="<?= rel_assets('images/if_meteorite_3285298.ico') ?>">

	<!-- assets -->
	<link rel="stylesheet" href="<?= rel_assets('css/animate.css') ?>">
	<link rel="stylesheet" href="<?= rel_assets('css/fontawesome.min.css') ?>">
	<link rel="stylesheet" href="<?= rel_assets('css/hover-min.css') ?>">
	<link rel="stylesheet" href="<?= rel_assets('css/notify.min.css') ?>">
	<link rel="stylesheet" href="<?= rel_assets('css/bootstrap.min.css') ?>">

	<link rel="stylesheet" href="<?= rel_assets('dataTables/dataTables.bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?= rel_assets('css/style.css') ?>">
	<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">

	<script>document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] + ':35729/livereload.js?snipver=1"></' + 'script>')</script>

	<?php if(isset($_SESSION['login']) == true) : ?>
	<link rel="stylesheet" href="<?= rel_assets('css/style.blue.css') ?>">
		<?php $profile = $home->myProfile(); ?>
		<nav class="side-navbar">
		  <div class="side-navbar-wrapper">
		    <div class="sidenav-header d-flex align-items-center justify-content-center">
		      <div class="sidenav-header-inner text-center">
		      	<img src="../assets/images/if_wink_1325171.svg" alt="person" class="img-fluid rounded-circle hvr-grow pointer" data-toggle="modal" data-target="#modalProfile">
		        <h2 class="h6"><?= $profile['name']; ?></h2>
		        <small class="text-muted font-weight-light"><i class="fas fa-circle fa-xs mr-1 online"></i><?= strtoupper($profile['position']); ?></small>
		      </div>
		      <div class="sidenav-header-logo">
		        <a href="javascript:" class="brand-small text-center">
		        	<img data-toggle="modal" data-target="#modalProfile" src="../assets/images/if_wink_1325171.svg" alt="person" class="img-fluid rounded-circle hvr-grow pointer">
		        </a>
		      </div>
		    </div>
		    <div class="main-menu">
		      <h5 class="sidenav-heading">Main</h5>
		      <ul id="side-main-menu" class="side-menu list-unstyled">
		        <li><a href="index.php" class="hvr-icon-grow"><i class="fas fa-home mr-2 hvr-icon"></i>Home</a></li>
		        <li><a href="intern.php" class="hvr-icon-grow"><i class="fas fa-users mr-2 hvr-icon"></i>Interns</a></li>
		        <li><a href="users.php" class="hvr-icon-grow"><i class="fas fa-user mr-2 hvr-icon"></i> Users</a></li>
		        <li><a href="audit_trails.php" class="hvr-icon-grow"><i class="fas fa-search mr-2 hvr-icon"></i>Audit Trails</a></li>
		        <li><a href="settings.php" class="hvr-icon-grow"><i class="fas fa-cog mr-2 hvr-icon"></i>Settings</a></li>
		      </ul>
		    </div>
		  </div>
		</nav>
		<div class="page">
		  <!-- navbar-->
		  <header class="header">
		    <nav class="navbar">
		      <div class="container-fluid">
		        <div class="navbar-holder d-flex align-items-center justify-content-between">
		          <div class="navbar-header">
		          	<a id="toggle-btn" href="#" class="menu-btn"><i class="fas fa-bars"></i></a>
		      	  </div>
		          <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
		            <li class="nav-item">
		              <form action="../controllers/LoginsController.php" method="POST">
		                <button type="submit" class="btn btn-outline-warning btn-sm" name="logout"><span class="d-none d-sm-inline-block"><i class="fas fa-sign-out-alt mr-1"></i>Logout</span></button>
		              </form>
		            </li>
		          </ul>
		        </div>
		      </div>
		    </nav>
		  </header>

		  <div class="modal fade" id="modalProfile">
		      <div class="modal-dialog">
		          <form id="formManageProfile" autocomplete="off">
		              <div class="modal-content">
		                  <div class="modal-header">
		                      <div class="modal-title font-weight-bold">Manage Profile</div>
		                      <button class="close" data-dismiss="modal">&times;</button>
		                  </div>
		                  <div class="modal-body">
		                      <div class="form-group">
		                          <label class="small text-muted">Name</label>
		                          <div class="input-group">
		                              <input type="text" class="form-control" name="fname" value="<?= $profile['fname'] ?>" placeholder="First" required>
		                              <input type="text" class="form-control" name="mname" value="<?= $profile['mname'] ?>" placeholder="Middle" required>
		                              <input type="text" class="form-control" name="lname" value="<?= $profile['lname'] ?>" placeholder="Last" required> 
		                          </div>
		                      </div>
		                      <div class="form-group">
		                          <label class="small text-muted">Office</label>
		                          <select name="office" class="form-control" required>
		                              <option value="">Choose office</option>
		                              <?php $office = $home->getoffices(); ?>
		                              <?php foreach($office as $data) : ?>
		                              <option value="<?= $data->office_id; ?>" <?= $data->office_id == $profile['office_id'] ? 'selected' : '' ?> ><?= $data->office; ?></option>
		                              <?php endforeach; ?> 
		                          </select>
		                      </div>
		                      <div class="form-group">
		                          <label class="small text-muted">Position</label>
		                          <input type="text" class="form-control" name="position" value="<?= $profile['position']; ?>" placeholder="Position" required>
		                      </div>
		                      <div class="form-group">
		                          <label class="small text-muted">Username</label>
		                          <input type="text" class="form-control" name="username" value="<?= $profile['username']; ?>" placeholder="Username" required>
		                      </div>
		                      <input type="hidden" name="hiddenManageProfile" value=""> </div>
		                  <div class="modal-footer">
		                      <button class="btn btn-primary" type="button" onclick="manageProfile()" data-dismiss="modal">Save</button>
		                      <button class="btn btn-secondary" data-dismiss="modal">Close</button>
		                  </div>
		              </div>
		          </form>
		      </div>
		  </div>
		  
		<div id="foodDiv">
	<?php endif; ?>
</head>
<body>