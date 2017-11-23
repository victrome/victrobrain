<?php if (!defined('PROTECT')) {
    exit('NO ACCESS');
}
require_once('victro_system/victro_settings/mainMethod.class.php');
$victro_maker = new VictroFunc();
$victro_datap = explode('/', $victro_datas_robot);
if (!isset($victro_datap[2]) or empty($victro_datap[2])) {
    $victro_datap[2] = 'index';
}
$victro_maker->load_power($victro_datap[1]);
$victro_t_robot = $victro_maker->robot_load($victro_datap[1]);
if ($victro_t_robot[1] != 0) {
    require_once('classes/controller_robot.php');
    $victro_robot = $victro_t_robot[0];
    $victro_class = str_replace(' ', '_', $victro_robot['name']);
    $victro_bot_lang = mb_strtolower(LANG);
    if (file_exists(PATH_APP.PATH_ROBOT.$victro_robot['local_url'] . '/language/' . $victro_bot_lang . '.php')) {
        ob_start();
        include_once(PATH_APP.PATH_ROBOT.$victro_robot['local_url'] . '/language/' . $victro_bot_lang . '.php');
        ob_end_clean();
    }
    include_once('bot_translation.php');
    if (file_exists(PATH_APP.PATH_ROBOT.$victro_robot['local_url'] . '/robot.php')) {
        ob_start();
        include(PATH_APP.PATH_ROBOT.$victro_robot['local_url'] . '/robot.php');
        ob_end_clean();
        $victro_robotphp = PATH_APP.PATH_ROBOT.$victro_robot['local_url'] . "/robot.php";
        $victro_action = $victro_datap[2];
    } else {
        exit(victro_translate("ROBOT FILE DOES NOT FOUND", 2, true));
    }
} else {
    exit(victro_translate("ROBOT DOES NOT FOUND", 2, true));
}

if ($victro_t_robot[1] != 0) {
    if (class_exists($victro_class)) { // check if the class exists
        $victro_robot_open = new $victro_class; // class is started
        if (is_subclass_of($victro_robot_open, 'controller_robot')) { // check if the extend exists
            if (method_exists($victro_robot_open, $victro_action)) { // check if the method exists
                $victro_robot['id'] = $victro_datap[1];
                $victro_robot['action'] = $victro_action; //include the action in array $victro_robot to use in another page
                $victro_robot['full_url'] = SITE_URL . $victro_robot['local_url'];
                $victro_robot['full_view'] = $victro_robot['full_url'] . '/view/';
                $victro_robot['b_link'] = $victro_maker->get_url_plugin('bot/' . $victro_datap[1]);
                $victro_robot['full_b_link'] = SITE_URL . $victro_robot['b_link'];
                $victro_robot['b_action'] = $victro_datas_robot;
                $victro_robot['b_link_action'] = $victro_robot['b_link'].$victro_action;
                $victro_robot['full_b_link_action'] = SITE_URL . $victro_robot['b_link'].$victro_action;
                $victro_robot['local_view'] = $victro_robot['local_url'] . '/view/';
                $victro_robot['local_extracontroller'] = $victro_robot['local_url'] . '/controller/';
                $victro_robot['local_model'] = $victro_robot['local_url'] . '/model/';
                $victro_robot['real_link'] = 'bot/' . $victro_datap[1] . '/';
                $victro_robot['class'] = $victro_class;
                $victro_robot['b_link_array'] = $victro_robot['real_link'].$victro_action;
                $victro_bot_param = $victro_url;
                foreach($victro_bot_param as $victro_key1 => $victro_value1){
                    if($victro_value1 == ""){
                        unset($victro_bot_param[$victro_key1]);
                    }
                }
                if($victro_bot_param[0] != "bot"){
                    $victro_bot_url1 = explode("/", $victro_robot['b_link_action']);
                } else {
                    $victro_bot_url1 = explode("/", $victro_robot['b_link_array']);
                }
                $victro_bot_url2 = count($victro_bot_url1);
                for($i = 0; $i  < $victro_bot_url2; $i++){
                    unset($victro_bot_param[$i]);
                }
                if(!isset($victro_bot_param)){
                    $victro_bot_param = array(null);
                }
                define("ROBOT_ASSETS", SITE_URL.$victro_robot['b_link']."assets/");
                call_user_func_array(array($victro_robot_open, $victro_action), $victro_bot_param);
                //$victro_maker->unset_vars();
            } else if ($_SESSION['typeuser'] == 5) { // else of method exists
                $victro_error_pag = 'nomethod';
                $victro_content = 'victro_system/victro_system/errors.php';
            }
        } else if ($_SESSION['typeuser'] == 5) { // else of check extents
            $victro_error_pag = 'noextend';
            $victro_content = 'victro_system/victro_system/errors.php';
        }
    } else if ($_SESSION['typeuser'] == 5) { // else of class exists
        $victro_error_pag = 'noclass';
        $victro_content = 'victro_system/victro_system/errors.php';
        echo "<h1>CLASS ERROR</h1>";
    }

    // Function to load var to model


}
?>
