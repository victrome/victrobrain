<?php

if (!defined('PROTECT')) {
    exit('NO ACCESS');
}
require_once('victro_system/victro_system/classes/json-rpc.php');
if (function_exists('xdebug_disable')) {
    xdebug_disable();
}
require_once('victro_system/victro_system/classes/terminal.class.php');
handle_json_rpc(new Terminal());
?>