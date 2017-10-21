<?php
 if(isset($_POST['check'])){
    rename('../victro_install/install/database.php', '../../victro_system/victro_settings/database.php');
    rename('../victro_install/install/htaccess', '../../.htaccess');
    header("location: ".$_COOKIE['siteurl']);
 }
?>