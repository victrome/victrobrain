<?php
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

}

?>
