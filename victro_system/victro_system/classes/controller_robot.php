<?php

class controller_robot{
    protected function global_robot(){
        GLOBAL $victro_robot;
        return($victro_robot);
    }
    /**
     * Call Addon class
     * @param integer $victro_addon_id Id of registered Addon
     * @return Object
     */
    protected function addon($victro_addon_id){
        include_once('addon.php');
        $victro_addon = new addon();
        $victro_setted = $victro_addon->set_addon($victro_addon_id, $this);
        return($victro_setted);
    }
    /**
     * Load a external controller file<BR>
     * Exemple: <i>$this->extend_controller("basic", array('ID', 1));</i>
     * @param String $victro_name_controller name of extended controllers file
     * @param Array $victro_data array with data that you want to send to controller
     * @return boolean if file does not found it returns false
     */
    protected function extend_controller($victro_name_controller = "", $victro_data = array()) {
        GLOBAL $victro_robot;
        if ($victro_name_controller == "") {
            $victro_name_controller = $victro_robot['action'];
        }
        if(is_array($victro_data) and count($victro_data) > 0){
            extract ($victro_data, EXTR_PREFIX_SAME, "bot");
        }
        if (file_exists(PATH_APP.PATH_ROBOT.$victro_robot['local_url'] . 'controller/' . $victro_name_controller . '.php')) {
            require_once(PATH_APP.PATH_ROBOT.$victro_robot['local_url'] . 'controller/' . $victro_name_controller . '.php');
        } else {
            return(false);
        }
    }
    /**
     * Load a model file<BR>
     * Exemple: <i>$bot_model = $this->model("basic", array('ID', 1));</i>
     * @param String $victro_name_model name of models file
     * @param Array $victro_data array with data that you want to send to model
     * @return Object if file does not found it returns false else returns model object class
     */
    protected function model($victro_name_model = "", $victro_data = array()) {
        require_once('model_robot.php');
        GLOBAL $victro_robot;
        if ($victro_name_model == "") {
            $victro_name_model = $victro_robot['action'];
        }
        if(is_array($victro_data) and count($victro_data) > 0){
            extract ($victro_data, EXTR_PREFIX_SAME, "bot");
        }
        if (file_exists(PATH_APP.PATH_ROBOT.$victro_robot['local_url'] . '/model/' . $victro_name_model . '.php')) {
            require_once(PATH_APP.PATH_ROBOT.$victro_robot['local_url'] . '/model/' . $victro_name_model . '.php');
            $victro_act_robot = new $victro_name_model;
            if (is_subclass_of($victro_act_robot, 'model_robot')) {
                return($victro_act_robot);
            } else
            if (is_subclass_of($victro_act_robot, $victro_robot['class'])) {
                return($victro_act_robot);
            } else {
                return(false);
            }
        } else {
            return(false);
        }
    }
    /**
     * Load a view file<BR>
     * This method can return a include view or html of itself
     * Exemple: <i>$this->view("basic", array('ID', 1), false);</i>
     * Exemple: <i>$bot_html = $this->view("basic", array('ID', 1), true);</i>
     * @param String $victro_name_view name of views file
     * @param Array $victro_data array with data that you want to send to view
     * @param Boolean $victro_mode set if you want to require (false) or html (true) of view`s called
     * @return Object if file does not found it returns false else if param 3 is false it requires the view else if param 3 is true it returns the html of this view
     */
    protected function view($victro_name_view = "", $victro_data = array(), $victro_mode = false) {
        GLOBAL $victro_robot;
        if ($victro_name_view == "") {
            $victro_name_view = $victro_robot['action'];
        }
        if(is_array($victro_data) and count($victro_data) > 0){
            extract($victro_data, EXTR_PREFIX_SAME, "bot");
        }
        if(file_exists(THEME_FULLDIR."/model/loadfile.php")){
            require_once(THEME_FULLDIR."/model/loadfile.php");
        } else
        if(file_exists(THEME_FULLDIR."/loadfile.php")){
            require_once(THEME_FULLDIR."/loadfile.php");
        }
        if (file_exists(PATH_APP.PATH_ROBOT.$victro_robot['local_url'] . '/view/' . $victro_name_view . '.php')) {
            if ($victro_mode == false) {
                require_once(PATH_APP.PATH_ROBOT.$victro_robot['local_url'] . '/view/' . $victro_name_view . '.php');
            } else {
                $victro_content_file = file_get_contents(PATH_APP.PATH_ROBOT.$victro_robot['local_url'] . '/view/' . $victro_name_view . '.php');
                return($victro_content_file);
            }
        } else {
            return(false);
        }
    }
    /**
     * Load a view file inside system design<BR>
     * This method loads the view inside system design
     * Exemple: <i>$this->system_view("basic", array('ID', 1));</i>
     * @param String $victro_name_view name of views file
     * @param Array $victro_data array with data that you want to send to view
     * @return Object if file does not found it returns false else it requires the view
     */
    protected function system_view($victro_name_view = "", $victro_data = array()) {
        GLOBAL $victro_robot;
        GLOBAL $victro_maker;
        if ($victro_name_view == "") {
            $victro_name_view = $victro_robot['action'];
        }
        if(is_array($victro_data) and count($victro_data) > 0){
            extract($victro_data, EXTR_PREFIX_SAME, "bot");
        }
        if (file_exists(PATH_APP.PATH_ROBOT.$victro_robot['local_url'] . '/view/' . $victro_name_view . '.php')) {
            $victro_content = PATH_APP.PATH_ROBOT.$victro_robot['local_url'] . '/view/' . $victro_name_view . '.php';
            require_once(THEME_FULLDIR . 'robot.php');
        } else {
            return(false);
        }
    }
    /**
     * Filter INPUT or GET<BR>
     * This method filters INPUT or GET params
     * Exemple: <i>$bot_value = $this->input("NAME", "POST"); -- Filter as POST</i>
     * Exemple: <i>$bot_value = $this->input("NAME", "GET"); -- Filter as GET</i>
     * Exemple: <i>$bot_value = $this->input("NAME", "GET_POST"); -- Try to filter as POST if nothing is found try to filter as GET </i>
     * Exemple: <i>$bot_value = $this->input("NAME", "POST_GET"); -- Try to filter as GET if nothing is found try to filter as POST </i>
     * @param String $victro_name name Param GET or POST
     * @param String $victro_type type of filter (POST, GET, GET_POST, POST_GET)
     * @param String $victro_filter type of filter (check PHP documentation of 'filter_input')
     * @return String if nothing is found it returns false else it return a value it can be (String, Boolean, Integer...)
     */
    protected function input($victro_name, $victro_type = "POST", $victro_filter = "default"){
        $victro_type_array = explode("_", $victro_type);
        $victro_value = false;
        $victro_filter = "FILTER_".mb_strtoupper($victro_filter, "UTF-8");
        foreach($victro_type_array as $victro_type_array2){
            $victro_type = mb_strtoupper($victro_type_array2, "UTF-8");
            $victro_value1 = filter_input(constant("INPUT_".$victro_type), $victro_name, constant($victro_filter));
            if($victro_value1 != false and $victro_value1 != null){
                $victro_value = $victro_value1;
            }
        }
        return($victro_value);
    }
    private function stringsecurity($victro_sentence, $victro_words) {
         $victro_result = false;
        foreach ($victro_words as $victro_key => $victro_value) {
            $victro_pos = strpos($victro_sentence, $victro_value);
            if ($victro_pos !== false) {
                $victro_result = true;
                break;
            }
        }
        return $victro_result;
    }
    /**
     * Check if current user can access some area of system<BR>
     * If the user cannot access the are it will redirected to login
     * @param String $victro_id access control (1, 2, 3, 4, 5 or =1, =2, =3, =4, =5)
     */
    protected function allow_user_type_by($victro_id, $victro_goto = "") {
        global $victro_site;
        $victro_permited = 0; // false
        if ($victro_id == '1' and $_SESSION['typeuser'] >= 1) {
            $victro_permited = 1;
        }
        if ($victro_id == '=1' and $_SESSION['typeuser'] == 1) {
            $victro_permited = 1;
        }
        if ($victro_id == '2' and $_SESSION['typeuser'] >= 2) {
            $victro_permited = 1;
        }
        if ($victro_id == '=2' and $_SESSION['typeuser'] == 2) {
            $victro_permited = 1;
        }
        if ($victro_id == '3' and $_SESSION['typeuser'] >= 3) {
            $victro_permited = 1;
        }
        if ($victro_id == '=3' and $_SESSION['typeuser'] == 3) {
            $victro_permited = 1;
        }
        if ($victro_id == '4' and $_SESSION['typeuser'] >= 4) {
            $victro_permited = 1;
        }
        if ($victro_id == '=4' and $_SESSION['typeuser'] == 4) {
            $victro_permited = 1;
        }
        if ($victro_id == '5' and $_SESSION['typeuser'] == 5) {
            $victro_permited = 1;
        }
        if ($victro_permited == 0) {
            if($victro_goto == ""){
                header('location:' . $victro_site['full_url'] . 'system/home');
            } else {
                header('location:' . $victro_goto);
            }
        }
    }
    /**
     * Start Session<BR>
     * Start a session in security mode
     * @param String $victro_name Session name
     * @param Object $victro_value session data (integer, array, string...)
     * @param String $victro_empty clean session before set the new value
     * @param String $victro_unique session with unique values (check array_unique in PHP documentation)
     */
    protected function start_session($victro_name, $victro_value, $victro_empty = null, $victro_unique = null){
        GLOBAL $victro_datap;
        $victro_id_bot = $victro_datap[1];
        if ($victro_unique != null) {
            $_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)] = array_unique($_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)]);
        }
        if ($victro_empty != null) {
            unset($_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)]);
        }
        if (is_array($victro_value)) {
            if (isset($_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)]) and ! is_array($_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)])) {
                $_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)] = Array();
            }
            foreach ($victro_value as $victro_key => $victro_dat) {
                $_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)][$victro_key] = $victro_dat;
            }
        } else {
            $_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)] = $victro_value;
        }
    }
    /**
     * Get Session<BR>
     * Get a session in security mode
     * @param String $victro_name Session name
     * @return Object If session is not set returns false else return Session's value
     */
    protected function get_session($victro_name) {
        GLOBAL $victro_datap;
        $victro_id_bot = $victro_datap[1];
        if (isset($_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)])) {
            return($_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)]);
        } else {
            return(0);
        }
    }
    /**
     * Delete Session<BR>
     * Delete a session in security mode
     * @param String $victro_name Session name
     */
    protected function unset_session($victro_name) {
        GLOBAL $victro_datap;
        $victro_id_bot = $victro_datap[1];
        if (isset($_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)])) {
            unset($_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)]);
        }
    }
        /**
     * Unset value of a Session<BR>
     * If session is an array it will search a value then unset it.
     * @param String $victro_name Session name
     * @param String $victro_search value search
     */
    protected function unset_value_session($victro_name, $victro_search) {
        GLOBAL $victro_datap;
        $victro_id_bot = $victro_datap[1];
        if (isset($_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)])) {
            if (($victro_key = array_search($victro_search, $_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)])) !== false) {
                unset($_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)][$victro_key]);
                return(1);
            } else {
                return(0);
            }
        }
    }
    /**
     * This method creates forms
     * @deprecated since version 0.8
     * @return view_robot Pre design
     */
    protected function design_view(){
        require_once('view_robot.php');
        $victro_design = new view_robot();
        return($victro_design);
    }
    /**
     * This method reloads page
     */
    protected function reload(){
        header('location: '.SITE_URL.NOW_URL);
    }
    /**
     * This method allow you to create a var with assets files
     *
     */
    public function assets($victro_var1 = "", $victro_var2 = "", $victro_var3 = "", $victro_var4 = "", $victro_var5 = "", $victro_var6 = "", $victro_var7 = "", $victro_var8 = ""){
        if($victro_var1 == ""){
            die("You cannot access an empty assets folder");
        }
        $victro_go = array();
        for($i = 1; $i <= 8; $i++){
            if(${'victro_var'.$i} != ""){
                $victro_go[] = ${'victro_var'.$i};
            }
        }
        $victro_file = implode("/", $victro_go);
        $victro_robot = $this->global_robot();
        $victro_final_file = PATH_APP.PATH_ROBOT.$victro_robot['local_view']."assets/".$victro_file;
        $victro_file_info = pathinfo($victro_final_file);
        $victro_images = array("png","PNG","jpg","JPG","gif","GIF","JPEG","jpeg");
        if($victro_file_info['extension'] == 'css'){
            header("Content-type: text/css");
        } else if($victro_file_info['extension'] == 'js'){
            header("Content-type: application/javascript");
        } else if(in_array($victro_file_info['extension'], $victro_images)){
            header('Content-type:image/'.$victro_file_info['extension']);
        }else {
            echo $victro_file_info['extension'];
        }
        if(file_exists($victro_final_file)){
            echo file_get_contents($victro_final_file);
        }

    }
    protected function language($victro_lang){
        $_SESSION['bot_lang'] = $victro_lang;
    }
}

?>
