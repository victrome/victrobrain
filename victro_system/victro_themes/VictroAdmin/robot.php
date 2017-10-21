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
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo (isset($victro_bot_title) ? $victro_bot_title : ""); ?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <!-- content here -->
                        <?php include($victro_content); ?>
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
  <?php 	
	$victro_filefooter = str_replace(".php", "", $victro_content);
	if(file_exists($victro_filefooter.'_footer.php')){
		include_once($victro_filefooter.'_footer.php');
	} 
	?>
  <?php include('model/footer.php'); ?>
