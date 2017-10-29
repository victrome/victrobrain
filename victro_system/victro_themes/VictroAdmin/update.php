<?php if(!defined('PROTECT')){ exit('NO ACCESS'); } ?>
<?php include('model/header.php'); ?>
<?php include('model/topbar.php'); ?>
<?php include('model/sidebar.php'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
       <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
      <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="box box-info">
                <!-- /.box-header -->
                <div class="box-body">
                    <!-- content here -->
                    <div class="row">
                      <div class="col-md-6 col-md-offset-3">
                        <?php echo $html; ?>
                      </div>
                    </div>
                    <!-- content ends -->
                </div>
            </div>
            </div>
        </div>
    </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php include('model/footer.php'); ?>
