<nav class="side-navbar">
  <div class="side-navbar-wrapper">
    <div class="sidenav-header d-flex align-items-center justify-content-center">
      <div class="sidenav-header-inner text-center">
      	<img src="../assets/images/if_wink_1325171.svg" alt="person" class="img-fluid rounded-circle hvr-grow pointer" data-toggle="modal" data-target="#modalProfile">
        <h2 class="h6" id="userName"></h2>
        <span><i class="fas fa-circle fa-xs mr-1 online"></i><span id="userPosition"></span></span>
      </div>
      <div class="sidenav-header-logo">
        <a href="#" class="brand-small text-center"><img data-toggle="modal" data-target="#modalProfile" src="../assets/images/if_wink_1325171.svg" alt="person" class="img-fluid rounded-circle hvr-grow pointer"></a>
      </div>
    </div>
    <div class="main-menu">
      <h5 class="sidenav-heading">Main</h5>
      <ul id="side-main-menu" class="side-menu list-unstyled">
        <li><a href="index.php" class="hvr-icon-grow"><i class="fas fa-home mr-2 hvr-icon"></i>Home</a></li>
        <li><a href="intern.php" class="hvr-icon-grow"><i class="fas fa-users mr-2 hvr-icon"></i>Interns</a></li>
        <!-- <li>
          <a href="#exampledropdownDropdown" data-toggle="collapse">Drop it!</a>
          <ul id="exampledropdownDropdown" class="collapse list-unstyled ">
            <li><a href="">Manage</a></li>
            <li><a href="">Manage</a></li>
          </ul>
        </li> -->
        <li><a href="users.php" class="hvr-icon-grow"><i class="fas fa-user mr-2 hvr-icon"></i> Users</a></li>
        <li><a href="audit_trails.php" class="hvr-icon-grow"><i class="fas fa-search mr-2 hvr-icon"></i>Audit Trails</a></li>
      </ul>
    </div>
    <!-- <div class="admin-menu">
      <h5 class="sidenav-heading">Second menu</h5>
      <ul id="side-admin-menu" class="side-menu list-unstyled">
        <li><a href="#">Recycled</a></li>
      </ul>
    </div> -->
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
  
<div id="foodDiv">
