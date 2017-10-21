<?php
//if(isset($_SESSION['victro_error_login_'.SESSION_NAME])){ header('location: '.$victro_site.'system/captcha'); }
require_once(PATH_SYSTEM. PATH_SETTINGS. 'mainMethod.class.php');
$victro_maker = new victroindex();

include(LANG_DIR);

if(isset($_GET['error'])){
	if($_GET['error'] == "count"){
		$victro_erromsg = $victro_language['counterror'];
	}
	if($_GET['error'] == "wrong"){
		$victro_erromsg = $victro_language['wronguser'];
	}
	if($_GET['error'] == "block"){
		$victro_erromsg = $victro_language['blockuser'];
	}
}
if(isset($_SESSION) and isset($_SESSION['iduser']) and isset($_SESSION['typeuser']) and isset($_SESSION['user']) and isset($_SESSION['nameuser']) and isset($_SESSION['emailuser']) and  $victro_link != "sys/login"){
	include_once('victro_system/victro_system/sys/home.php');
}	else { 
	if(isset($_POST['login'])){
		$victro_maker->loginindex($_POST['username'], $_POST['password']);
	}
        
	require_once(THEME_FULLDIR.'login.php');
}

?>