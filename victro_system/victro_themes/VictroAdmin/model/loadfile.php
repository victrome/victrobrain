<?php if(!defined('PROTECT')){ exit('NO ACCESS'); } ?>
<?php
	function js_include($victro_file){
		echo '<script src="'.THEME.'assets/js/'.$victro_file.'"></script>';
	}
	function css_include($victro_file){
		echo '<script src="'.THEME.'assets/css/'.$victro_file.'"></script>';
	}
	function plugin_include($victro_file){
		echo '<script src="'.THEME.'assets/plugins/'.$victro_file.'"></script>';
	}
	function plugincss_include($victro_file){
		echo '<link href="'.THEME.'assets/plugins/'.$victro_file.'" rel="stylesheet" />';
	}
	function basic_css(){
                echo '<link rel="stylesheet" href="'.THEME.'bootstrap/css/bootstrap.min.css">';
                echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">';
                echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">';
                echo '<link rel="stylesheet" href="'.THEME.'plugins/jvectormap/jquery-jvectormap-1.2.2.css">';
                echo '<link rel="stylesheet" href="'.THEME.'dist/css/AdminLTE.css">';
                echo '<link rel="stylesheet" href="'.THEME.'dist/css/skins/skin-victro.css">';
		echo '<link href="'.SITE_URL.'system/victro_js/dropzone.css" rel="stylesheet" />';
	}
	function basic_js(){
		echo '<script src="assets/plugins/pace/pace.min.js"></script>';
	}
	function basic_js2(){
            echo '<script src="'.THEME.'plugins/jQuery/jquery-2.2.3.min.js"></script>';
            echo '<script src="'.THEME.'bootstrap/js/bootstrap.min.js"></script>';
            echo '<script src="'.THEME.'plugins/fastclick/fastclick.js"></script>';
            echo '<script src="'.THEME.'dist/js/app.min.js"></script>';
            echo '<script src="'.THEME.'plugins/sparkline/jquery.sparkline.min.js"></script>';
            echo '<script src="'.THEME.'plugins/slimScroll/jquery.slimscroll.min.js"></script>';
            echo '<script src="'.THEME.'plugins/chartjs/Chart.min.js"></script>';
            echo '<script src="'.THEME.'dist/js/pages/dashboard2.js"></script>';
            //echo '<script src="'.THEME.'dist/js/demo.js"></script>';
            echo '<script src="'.SITE_URL.'system/victro_js/notification.js"></script>';
            echo '<script src="'.SITE_URL.'system/victro_js/dropzone.js"></script>';
            echo '<script src="'.SITE_URL.'system/victro_js/search.js"></script>
                <script> get_notification("'.SITE_URL.'"); setInterval(function(){ get_notification("'.SITE_URL.'"); }, 20000); </script>';
	}
	function basic_js3($victro_js = ''){
		echo '<script>
		$(document).ready(function() {
			App.init();
			'.$victro_js.'
		});
	</script>';
		/*echo "<script> $(document).on('keydown', function(e) {
				  console.log(e.which); // Retorna o número código da tecla
				  //console.log(e.altKey); // Se o alt foi Pressionado retorna true
				  if ((e.altKey) && (e.which === 85)) { // Pesquisar (Alt + P)
					$('#victro-modal-title').html('".victro_translate('Upload File', 1, true)."');
					$('#victro-modal-body').html('<form action=\"upload.php\" class=\"dropzone\" enctype=\"multipart/form-data\">');
					//$victro_('#victro-modal-body').append('<input name=\"file\" type=\"file\" multiple />');
					$('#victro-modal-body').append('</form>');
					$('.dropzone').dropzone({ url: '".SITE_URL."system/victro_upload' });
					$('#victro-modal-system').modal();
				  }
				});</script>"; */
	}
	