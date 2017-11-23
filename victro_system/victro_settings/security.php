<?php
//$_SERVER['HTTP_HOST'].$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
require_once("security.class.php");

$victro_maker = new SECURITY();
$victro_session = $victro_maker->getSettings("VICTRO_SESSION_NAME");
if (!isset($_SESSION)) {
    session_name($victro_session->value);
    session_start();
}
$victro_db_url = $victro_maker->getSettings("VICTRO_URL");
$victro_db_type_url = $victro_maker->getSettings("VICTRO_TYPE_URL");
$victro_site = $victro_db_type_url->value . "://" . $_SERVER['HTTP_HOST']. $victro_db_url->value;
if(isset($victro_maker->getSettings("VICTRO_USER")->value) && isset($victro_maker->getSettings("VICTRO_PASSWORD")->value)){
    $victro_user = $victro_maker->getSettings("VICTRO_USER")->value;
    $victro_password = $victro_maker->getSettings("VICTRO_PASSWORD")->value;
} else {
    $victro_user = "";
    $victro_password = "";
}


$victro_server = $_SERVER["REQUEST_URI"]; // Get url server
$victro_p1 = strrchr($victro_server, "?"); // get last url php
$victro_p2 = str_replace($victro_p1, "", $victro_server); // replace last url php
$victro_url_array = explode("/", $victro_p2); // get url in array
array_shift($victro_url_array); // delete the first array
$victro_db_folders = $victro_maker->getSettings("VICTRO_FOLDERS");
$victro_start = $victro_db_folders->value; // get in db how much folders before victro directory
$victro_unse = 0; // starts var
//Function to unset array while number folders is less then $victro_unse
while ($victro_unse <= $victro_start) {
    if ($victro_unse != $victro_start) {
        unset($victro_url_array[$victro_unse]);
    }
    $victro_unse++;
}
//End while function

$victro_url = array_values($victro_url_array); // renumeric array
$victro_link = implode("/", $victro_url); // generate complete link
if ($victro_user != "" && $victro_password != "" && (!isset($_SESSION["iduser"]) || $_SESSION["iduser"] == 0)) {
    require_once("mainMethod.class.php");
    $victro_maker2 = new victroindex();
    $victro_maker2->loginindex($victro_user, $victro_password, false);
}

if (!isset($victro_url[1])) {
    $victro_url[1] = null;
}

// Add slash if url is index
if ($victro_link == "index") {
    header('location:' . $victro_site . 'index/');
}
// end add slash

if ($victro_maker->getSettings("VICTRO_INDEX")->value != 'index') {
    $victro_url_index_a = $victro_maker->getSettings("VICTRO_INDEX")->value;
    $victro_broke_url_index = explode('/', $victro_url_index_a);
    $victro_action_index_a = $victro_broke_url_index[2];
}
$victro_route_id = 0;
if(isset($victro_url[0]) && strlen($victro_url[0]) > 3){
    $victro_route_id = $victro_maker->getRobotRoute($victro_url[0], 0);
}

$victro_isLogged = true;
if (!isset($_SESSION['user']) || !isset($_SESSION['typeuser'])) {
    $victro_isLogged = false;
}

//DEFINES
define('SITE_URL', $victro_site);
define('ROUTE_BOT_ID', $victro_route_id);
define('THEME_NAME', $victro_maker->getSettings("VICTRO_THEME")->value);
define('THEME', SITE_URL . PATH_SYSTEM . PATH_THEME . THEME_NAME . '/');
define('THEME_FULLDIR', PATH_SYSTEM . PATH_THEME . THEME_NAME . '/');
define('PROTECT', true);
define('SITE_NAME', $victro_maker->getSettings("VICTRO_TITLE")->value);
define('LANG', $victro_maker->getSettings("VICTRO_LANGUAGE")->value);
define('LANG_DIR', PATH_SYSTEM . PATH_SYSTEM . PATH_LANGUAGE . LANG . ".php");
define('SESSION_NAME', $victro_maker->getSettings("VICTRO_SESSION_NAME")->value);
define('PICTURE_LOGO', $victro_maker->getSettings("VICTRO_LOGO")->value);
define('POWER_DIR', PATH_APP . PATH_POWER);
define('STORAGE', SITE_URL . 'loadfile?');
define('STORAGE_NAME', SITE_URL . 'loadfile?name=');
define('STORAGE_ID', SITE_URL . 'loadfile?id=');
define('VICTRO_INDEX', $victro_maker->getSettings("VICTRO_INDEX")->value);
define('SECURITY_KEY', md5(SESSION_NAME . date('m-Y')));
define('STORAGE_KEY', md5(SESSION_NAME . 'system/fileload?'));
define('NOW_URL', $victro_link);
define('NUM_FOLDERS', $victro_maker->getSettings("VICTRO_FOLDERS")->value);
define('VERSION', $victro_maker->getSettings("VICTRO_VERSION")->value);
define('IS_LOOGED', $victro_isLogged);
foreach ($victro_url as $victro_key => $victro_ur) {
    define('URL_' . $victro_key, $victro_ur);
}
?>
