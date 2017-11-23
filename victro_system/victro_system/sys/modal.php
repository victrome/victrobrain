<?php
if (!defined('PROTECT')) {
    exit('NO ACCESS');
}
if (!isset($_SESSION['typeuser']))
    header('location: ' . SITE_URL . '/sys/login');
require_once(PATH_SYSTEM.PATH_SETTINGS.'translation.php');
require_once(PATH_SYSTEM.PATH_SYSTEM.'classes/addon.php');
$victro_maker = new addon();
$victro_type = filter_input(INPUT_POST, 'type');
if($victro_type == "newaddon"){
  $victro_hex='';
  $victro_baseURL = base64_encode(SITE_URL);
  for ($i=0; $i < strlen($victro_baseURL); $i++){
    $victro_hex .= dechex(ord($victro_baseURL[$i]));
  }
  $victro_baseURL = $victro_hex;
  require_once(THEME_FULLDIR . 'modal/new_addon.php');
}
if($victro_type == "dbaddon"){
  $victro_type = filter_input(INPUT_POST, 'type');
}
if($victro_type == "send_addon"){
  $victro_params['TYPE'] = $victro_maker->input("TYPE");
  $victro_params['SSID'] = $victro_maker->input("SSID");
  $victro_params['PASS'] = $victro_maker->input("PASS");
  $victro_params['HOST'] = $victro_maker->input("HOST");
  $victro_params['MODEL'] = $victro_maker->input("MODEL");
  $victro_params['CONNECT'] = $victro_maker->input("CONNECT");
  echo $victro_maker->send_new_addon($victro_params);
}

?>
