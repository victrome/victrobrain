<?php
if (!defined('PROTECT')) {
    exit('NO ACCESS');
}
if (!isset($_SESSION['typeuser']))
    header('location: ' . SITE_URL . '/system/login');
require_once(PATH_SYSTEM.PATH_SETTINGS.'mainMethod.class.php');
$victro_maker = new VictroFunc();
$victro_widgets = ($victro_maker->home_widgets($_SESSION['typeuser']));
require_once(THEME_FULLDIR . 'home.php');
?>