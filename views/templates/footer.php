<?php if(isset($_SESSION['login'])) : ?>
  <footer class="main-footer">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <p class="text-muted hvr-grow">PhilHealth Regional Office <small>2017-2019</small></p>
        </div>
        <div class="col-sm-6 text-right small">
          <p class="text-light"><img src="../assets/images/if_meteorite_3285298.ico" class="mr-1 hvr-grow" alt="" width="3%"><a href="https://www.facebook.com/KarmaBlackshaw">Ernie Jeash C. Villahermosa</a></p>
        </div>
      </div>
    </div>
  </footer>
<?php endif ?>


	<!-- javascripts $_SERVER['PHP_SELF'] != '/On-the-Job/index.php'-->
	<script src="<?= rel_assets('js/jquery-bootstrap.min.js') ?>"></script>
	<script src="<?= rel_assets('js/fontawesome.min.js') ?>"></script>
	<script src="<?= rel_assets('js/jquery.min.js') ?>"></script>
	<script src="<?= rel_assets('js/ticker.js') ?>"></script>
	<script src="<?= rel_assets('js/notify.js') ?>"></script>
	<script src="<?= rel_assets('dataTables/dataTables.bootstrap.min.js') ?>"></script>
	<script src="<?= rel_assets('js/grasp_mobile_progress_circle-1.0.0.min.js') ?>"></script>
	<script src="<?= rel_assets('js/jquery.mCustomScrollbar.concat.min.js') ?>"></script>
	<script src="<?= rel_assets('js/front.js') ?>"></script>
	<script src="<?= rel_assets('chart/chart.bundle.min.js') ?>"></script>
	<script src="<?= rel_assets('chart/chart.min.js') ?>"></script>
	
	<script src="<?= rel_assets('js/popper-bootstrap.min.js') ?>"></script>
	<script src="<?= rel_assets('js/bootstrap.min.js') ?>"></script>
	
	<script src="<?= rel_assets('js/defaults.js') ?>"></script>
	<script src="<?= node('randomcolor/randomColor.js') ?>"></script>
	<?php require_once abs_views('modals'); ?></div>
	
</body>
</html>