 <!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>VictroBrain Installer</title>
		<meta name="generator" content="Bootply" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
		<link href="install/view/css/bootstrap.min.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="install/view/css/styles.css" rel="stylesheet">

	</head>
	<body>
<!-- header -->
<div id="top-nav" class="navbar navbar-inverse navbar-static-top">
    <div class="container-fluid">     
            <center><img src="install/view/img/victrobrain-white.png" width="190" style="margin:5px;"></center>
    </div>
    <!-- /container -->
</div>
<!-- /Header -->

<!-- Main -->
<div class="container-fluid">
    <div class="row">
 
        <div class="col-sm-12">

           

            <div class="row">
                <!-- center left-->
                
                <!--/col-->
                <div class="col-md-6 col-md-offset-3">
                    
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <i class="fa fa-wrench pull-right"></i>
                                <h4>Install - User Settings</h4>
				<div class="progress">
                                	<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%">
                                    <span class="sr-only">50% Complete</span>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <form class="form form-vertical" method="post" enctype="multipart/form-data" >
                                <div class="control-group">
                                    <label>Name:</label>
                                    <div class="controls">
                                        <input type="text" required class="form-control" name="name" placeholder="Write your name">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label>Username:</label>
                                    <div class="controls">
                                        <input type="text" required class="form-control" name="username" placeholder="Write your username">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label>E-mail:</label>
                                    <div class="controls">
                                        <input type="email" required class="form-control" name="email" placeholder="Write your email">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label>Password:</label>
                                    <div class="controls">
                                        <input type="password" required class="form-control" name="pass1" placeholder="Write your password">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label>Confirm Password:</label>
                                    <div class="controls">
                                        <input type="password" required class="form-control" name="pass2" placeholder="Write your password again">
                                    </div>
                                </div>
								<div class="control-group">
                                    <label>User Picture:</label>
                                    <div class="controls">
                                        <input type="file" required class="form-control" name="picuser">
                                    </div>
                                </div>
				
                                
                                <div class="control-group">
                                    <label></label>
                                    <div class="controls">
                                        <button type="submit" name="check" class="btn btn-danger">
                                            Next
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!--/panel content-->
                    </div>
             
                </div>
                <!--/col-span-6-->

            </div>
            <!--/row-->

           
        </div>
        <!--/col-span-9-->
    </div>
</div>
<!-- /Main -->


<footer style="position: absolute; bottom: 0px; margin-left: -15px; width: 100%; border-top: 3px solid #00a5eb;">.<font style="position: absolute; left:20px;">
    
    <?php if(date('Y') > 2016 ){ ?>
    Copyright &#xa9; 2016 - <?php echo date('Y'); ?> <a href="http://victrobrain.com">VictroBrain</a>
    <?php } else { ?>
        Copyright &#xa9; 2016 <a href="http://victrobrain.com">VictroBrain</a>
    <?php } ?>
        </font>
        <font style="position: absolute; right:20px;">Version <?php echo $victro_version; ?></font>
</footer>

	<!-- script references -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script src="install/view/js/bootstrap.min.js"></script>
		<script src="install/view/js/scripts.js"></script>
	</body>
</html>