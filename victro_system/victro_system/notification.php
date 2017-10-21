<?php
if(!defined('PROTECT')){ exit('NO ACCESS'); }
if(!isset($_SESSION['typeuser']) or $_SESSION['iduser'] == "0") exit;
require_once('victro_system/victro_settings/mainMethod.class.php');
$victro_maker = new VictroFunc();
if(!isset($_GET['id'])){
	$victro_notification = ($victro_maker->notification_ajax());
	if(!isset($_SESSION['victro_notification_num']) or $victro_notification['count'] != $_SESSION['victro_notification_num']){
		$_SESSION['victro_notification_num'] = $victro_notification['count'];
		$victro_notification_css = 'animated fadeInDown';
		if($_SESSION['victro_notification_num'] > 0){
			$victro_notification_sound = true;
		} else {
			$victro_notification_sound = false;
		}
	} else {
		$victro_notification_css = '';
		$victro_notification_sound = false;
	}
	if(file_exists(THEME_FULLDIR.'model/notification.php')){
		require_once(THEME_FULLDIR.'model/notification.php');
	}
} else {
	$victro_read_notification = ($victro_maker->read_notification($_GET['id']));
	$victro_notification_sound = false;
	$_SESSION['victro_notification_num']--;
	$victro_notification_css = 'animated fadeInDown';
	if($victro_read_notification['link'] == 'system/notification'){
		require_once(THEME_FULLDIR.'model/modal.php');
	} else {
		header('location: '.SITE_URL.$victro_read_notification['link']);
	}
}


?>