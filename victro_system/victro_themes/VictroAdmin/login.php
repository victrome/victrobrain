<?php if(!defined('PROTECT')){ exit('NO ACCESS'); } ?>
<?php $victro_body_class = "hold-transition login-page"; include('model/header.php'); ?>
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><font class="logo-white">VICTRO</font> <font class="logo-blue">BRA</font><font class="logo-dark">IN</font></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"><?php echo SITE_NAME; ?></p>

    <form method="POST">
      <div class="form-group has-feedback">
        <input type="text" name="username" class="form-control" placeholder="<?php victro_translate("Username"); ?>">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="<?php victro_translate("Password"); ?>">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
       
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" name="login" class="btn btn-primary btn-block btn-flat"><?php victro_translate("Sign In"); ?></button>
        </div>
        <!-- /.col -->
      </div>
    </form>


  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<?php basic_js2(); ?>
</body>
</html>