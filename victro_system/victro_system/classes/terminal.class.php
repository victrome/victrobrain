<?php

require_once("victro_system/victro_settings/database.php");

class Terminal extends victro_DBconnect {

    protected function getSessionName() {
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("select session_name from victro_system");
        $victro_tb->execute();
        $victro_sessionname = $victro_tb->fetch(PDO::FETCH_ASSOC);
        $victro_sessionname = $victro_sessionname['session_name'];
        return($victro_sessionname);
    }

    protected function checkUserOn() {
        if (isset($_SESSION['typeuser'])) {
            return($_SESSION['typeuser']);
        }
        return(false);
    }

    protected function getUserId() {
        if (isset($_SESSION['iduser'])) {
            return($_SESSION['iduser']);
        }
        return(false);
    }

    static $victro_login_documentation = "login to the server (return token)";

    public function login($victro_user, $victro_passwd) {
        $victro_conn = $this->defaultConnection();
        $victro_passhash = md5($victro_passwd);
        $victro_tb = $victro_conn->prepare("select id, username, type, name, email, failures, user_pic from victro_user where username = :user and victro_password = :pass");
        $victro_tb->bindParam(":user", $victro_user, PDO::PARAM_STR);
        $victro_tb->bindParam(":pass", $victro_passhash, PDO::PARAM_STR);
        $victro_tb->execute();
        $victro_l = $victro_tb->fetch(PDO::FETCH_ASSOC);
        if ($victro_tb->rowCount() == 1 and $victro_l['failures'] <= 3) {
            if (!isset($_SESSION["iduser"])) {
                $_SESSION["iduser"] = 0;
            }
            if (!isset($_SESSION["typeuser"])) {
                $_SESSION["typeuser"] = 0;
            }
            while ($victro_l['type'] != $_SESSION["typeuser"] and $victro_l["id"] != $_SESSION["iduser"]) {
                $_SESSION["iduser"] = $victro_l["id"];
                $_SESSION["typeuser"] = $victro_l["type"];
                $_SESSION["user"] = $victro_l["username"];
                $_SESSION["nameuser"] = $victro_l["name"];
                $_SESSION["emailuser"] = $victro_l["email"];
                $_SESSION["picuser"] = $victro_l["user_pic"];
                $victro_shortname = explode(" ", $victro_l['name']);
                $_SESSION["shortnameuser"] = $victro_shortname[0];
            }
            $victro_tb1 = $victro_conn->prepare("update victro_user set failures = 0 where id = :id");
            $victro_tb1->bindParam(":id", $victro_l['id'], PDO::PARAM_STR);
            $victro_tb1->execute();
            return md5($victro_user . ":" . $victro_passwd);
        } else if ($victro_l['failures'] > 3) {
            if (isset($_SESSION['error_victro'])) {
                $victro_errorlogin = $_SESSION['error_victro'];
            } else {
                $victro_errorlogin = 0;
            }
            $_SESSION['error_victro'] = $victro_errorlogin + 1;

            throw new Exception("Your user is blocked");
        } else {
            $victro_tb2 = $victro_conn->prepare("select id, username from victro_user where username = :user");
            $victro_tb2->bindParam(":user", $victro_user, PDO::PARAM_STR);
            $victro_tb2->execute();
            if ($victro_tb2->rowCount() >= 1) {
                $victro_tb3 = $victro_conn->prepare("update victro_user set failures = (failures + 1) where username = :user");
                $victro_tb3->bindParam(":user", $victro_user, PDO::PARAM_STR);
                $victro_tb3->execute();
                throw new Exception("Your user is blocked");
            } else {
                throw new Exception("Wrong Password");
            }
            $_SESSION['victro_error_login_' . SESSION_NAME] = "error";
        }
    }

    public function logout() {
        session_destroy();
        return("You are out!");
        //response("$('body').terminal().logout();" , 1, false);
    }

    static $install_documentation = "Install robot or power";

    public function install($victro_install, $victro_dir) {
        if ($this->checkUserOn() != false) {
            if ($victro_install == "bot") {
                return $this->install_bot($victro_dir);
            } else {
                return("Wrong params");
            }
        } else {
            return("You need to login");
        }
    }

    public function install_bot($victro_dir) {
        $victro_conn = $this->defaultConnection();
        $victro_folder = "victro_apps/victro_robot/";
        $victro_return = "Error: Robot does not exist";
        if(is_dir($victro_folder . $victro_dir)){
            if (file_exists($victro_folder . $victro_dir . '/install.php') and file_exists($victro_folder . $victro_dir . '/robot.php')) {
                include("robot.php");
                $victro_robot = new robot();
                ob_start();
                include($victro_folder . $victro_dir . '/install.php');
                ob_end_clean();
                $victro_robot->victro_botfolder = $victro_dir;
                $victro_robot->connect = $victro_conn;
                $victro_return = $victro_robot->install();
            }
        }
        return($victro_return);
    }
    
    public function update_bot($victro_dir) {
        $victro_conn = $this->defaultConnection();
        $victro_folder = "victro_apps/victro_robot/";
        $victro_return = "Error: Robot does not exist";
        if(is_dir($victro_folder . $victro_dir)){
            if (file_exists($victro_folder . $victro_dir . '/install.php') and file_exists($victro_folder . $victro_dir . '/robot.php')) {
                include("robot.php");
                $victro_robot = new robot();
                ob_start();
                include($victro_folder . $victro_dir . '/install.php');
                ob_end_clean();
                $victro_robot->victro_botfolder = $victro_dir;
                $victro_robot->connect = $victro_conn;
                $victro_return = $victro_robot->update_bot();
            }
        }
        return($victro_return);
    }

    public function install_robot($victro_method, $victro_param = "") {
        GLOBAL $victro_maker;
        $victro_pasta = "victro_apps/victro_robot/";
        $victro_diretorio = dir($victro_pasta);
        $victro_cont = 1;
        $victro_cont1 = 0;
        while (($victro_arquivo = $victro_diretorio->read()) !== false) {
            $victro_to_install = false;
            if (is_dir($victro_pasta . $victro_arquivo)) {
                if ($victro_arquivo != "." && $victro_arquivo != "..") {
                    if (file_exists($victro_pasta . $victro_arquivo . '/install.php') and file_exists($victro_pasta . $victro_arquivo . '/robot.php')) {
                        $victro_table = array();
                        $victro_menu = array();
                        $victro_submenu = array();
                        include($victro_pasta . $victro_arquivo . '/install.php');
                        if (isset($victro_robot_name) and $victro_robot_name != null and
                                isset($victro_robot_author) and $victro_robot_author != null and
                                isset($victro_robot_description) and $victro_robot_version != null
                        ) {
                            if ($victro_method == "by_number" and $victro_cont == $victro_param) {
                                $victro_to_install = true;
                            }
                            if ($victro_method == "by_author" and $victro_robot_author == $victro_param) {
                                $victro_to_install = true;
                            }
                            if ($victro_method == "by_name" and $victro_robot_name == $victro_param) {
                                $victro_to_install = true;
                            }
                            if ($victro_method == "by_folder" and $victro_arquivo == $victro_param) {
                                $victro_to_install = true;
                            }
                            if ($victro_method == "all") {
                                $victro_to_install = true;
                            }
                            if ($victro_to_install == true) {
                                $victro_bt = $victro_maker->robot_in_db(md5($victro_pasta . $victro_arquivo));
                                if ($victro_bt == 0) {
                                    if (!isset($victro_table)) {
                                        $victro_table = array();
                                    } else {
                                        $victro_maker->robot_create_table($victro_table);
                                        $victro_maker->robot_setquery();
                                    }
                                    if (!isset($victro_submenu)) {
                                        $victro_submenu = array();
                                    }
                                    if (!isset($victro_menu)) {
                                        $victro_menu = array();
                                    }
                                    $victro_robot_icon = (!isset($victro_robot_icon) || $victro_robot_icon == null ? 'fa fa-plug' : $victro_robot_icon);
                                    $victro_tab = serialize($victro_table);
                                    $victro_subm = serialize($victro_submenu);
                                    $victro_menu2 = serialize($victro_menu);
                                    $victro_data = date("Y-m-d");
                                    $victro_user = $_SESSION['iduser'];
                                    $victro_maker->robot_install($victro_robot_name, $victro_pasta . $victro_arquivo, $victro_data, $victro_user, $victro_tab, $victro_subm, $victro_menu2, $victro_robot_author, $victro_robot_version, $victro_robot_icon);
                                    $victro_cont1++;
                                }
                            }
                            $victro_cont++;
                        }

                        $victro_robot_author = null;
                        $victro_robot_description = null;
                        $victro_robot_icon = null;
                        $victro_robot_name = null;
                        $victro_robot_version = null;
                        $victro_submenu = array();
                        $victro_menu = array();
                    }
                }
            }
        }
        return($victro_cont1);
    }

    static $victro_whoami_documentation = "return user information";

    public function whoami() {
        return ("your User Agent " . $_SERVER["HTTP_USER_AGENT"] . " \nyour IP " . $_SERVER['REMOTE_ADDR'] . " \nyou acces this from" . $_SERVER["HTTP_REFERER"]);
    }

    public function design() {
        return("going to design");
    }
    
    public function update_sys($victro_type, $victro_value){
        $victro_conn = $this->defaultConnection();
        include("Sys.php");
        $victro_system = new Sys();
        $victro_system->connect = $victro_conn;
        $victro_return = $victro_system->system_update($victro_type, $victro_value);
        return($victro_return);
    }
}

?>
