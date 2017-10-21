<?php if(!defined('PROTECT')){ exit('NO ACCESS'); } ?>
<?php include('loadfile.php'); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo SITE_NAME; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <?php basic_css(); ?>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<?php if(!isset($victro_body_class)){ $victro_body_class = "hold-transition skin-blue sidebar-mini fixed"; } ?>
<body class="<?php echo $victro_body_class; ?>">
    <div class="modal fade" id="victro-modal-system">
            <div class="modal-dialog">
                    <div class="modal-content">
                            <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title" id="victro-modal-title"></h4>
                            </div>
                            <div class="modal-body panel-form" id="victro-modal-body">

                            </div>
                    </div>
            </div>
    </div>
    <div class="wrapper">