<?php 
    class Commands {
        function list_robot($victro_type = "all") {
            GLOBAL $victro_maker;
            $victro_pasta = "victro_apps/victro_robot/";
            $victro_diretorio = dir($victro_pasta);
            $victro_cont = 1;
            $victro_robot_data = str_pad("", 70, "_") . "\n";
            while (($victro_arquivo = $victro_diretorio->read()) !== false) {
                $victro_to_install = false;
                if (is_dir($victro_pasta . $victro_arquivo)) {
                    if ($victro_arquivo != "." && $victro_arquivo != "..") {
                        if (file_exists($victro_pasta . $victro_arquivo . '/install.php') and
                                file_exists($victro_pasta . $victro_arquivo . '/robot.php') and
                                is_dir($victro_pasta . $victro_arquivo . '/model')
                        ) {
                            $victro_table = array();
                            $victro_menu = array();
                            $victro_submenu = array();
                            ob_start();
                            include($victro_pasta . $victro_arquivo . '/install.php');
                            if (isset($victro_robot_name) and $victro_robot_name != null and
                                    isset($victro_robot_author) and $victro_robot_author != null and
                                    isset($victro_robot_description) and $victro_robot_version != null
                            ) {
                                if ($victro_type == 'all') {
                                    $victro_robot_data .= str_pad($victro_cont, 5) . " | " . str_pad($victro_robot_name, 50) . " | " . str_pad($victro_arquivo, 50) . "\n";
                                    $victro_cont++;
                                } else {
                                    $victro_bt = $victro_maker->robot_in_db(md5($victro_pasta . $victro_arquivo));
                                    if ($victro_bt == 1 and $victro_type == 'installed') {
                                        $victro_robot_data .= str_pad($victro_cont, 5) . " | " . str_pad($victro_robot_name, 50) . " | " . str_pad($victro_arquivo, 50) . "\n";
                                        $victro_cont++;
                                    } else
                                    if ($victro_bt == 0 and $victro_type == 'uninstalled') {
                                        $victro_robot_data .= str_pad($victro_cont, 5) . " | " . str_pad($victro_robot_name, 50) . " | " . str_pad($victro_arquivo, 50) . "\n";
                                        $victro_cont++;
                                    }
                                }
                            }

                            $victro_robot_author = null;
                            $victro_robot_description = null;
                            $victro_robot_icon = null;
                            $victro_robot_name = null;
                            $victro_robot_version = null;
                            $victro_submenu = array();
                            $victro_menu = array();
                            ob_end_clean();
                        }
                    }
                }
            }
            return($victro_robot_data);
        }

        function uninstall_robot($victro_method, $victro_param = "") {
            GLOBAL $victro_maker;
            $victro_pasta = "victro_apps/victro_robot/";
            $victro_diretorio = dir($victro_pasta);
            $victro_cont = 1;
            $victro_cont1 = 0;
            while (($victro_arquivo = $victro_diretorio->read()) !== false) {
                $victro_to_install = false;
                if (is_dir($victro_pasta . $victro_arquivo)) {
                    if ($victro_arquivo != "." && $victro_arquivo != "..") {
                        if (file_exists($victro_pasta . $victro_arquivo . '/install.php') and
                                file_exists($victro_pasta . $victro_arquivo . '/robot.php') and
                                is_dir($victro_pasta . $victro_arquivo . '/controller') and
                                is_dir($victro_pasta . $victro_arquivo . '/model') and
                                is_dir($victro_pasta . $victro_arquivo . '/view')
                        ) {
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
                                    if ($victro_bt == 1) {
                                        $victro_maker->robot_uninstall(md5($victro_pasta . $victro_arquivo));
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

        function install_robot($victro_method, $victro_param = "") {
            GLOBAL $victro_maker;
            $victro_pasta = "victro_apps/victro_robot/";
            $victro_diretorio = dir($victro_pasta);
            $victro_cont = 1;
            $victro_cont1 = 0;
            while (($victro_arquivo = $victro_diretorio->read()) !== false) {
                $victro_to_install = false;
                if (is_dir($victro_pasta . $victro_arquivo)) {
                    if ($victro_arquivo != "." && $victro_arquivo != "..") {
                        if (file_exists($victro_pasta . $victro_arquivo . '/install.php') and
                                file_exists($victro_pasta . $victro_arquivo . '/robot.php') and
                                is_dir($victro_pasta . $victro_arquivo . '/controller') and
                                is_dir($victro_pasta . $victro_arquivo . '/model') and
                                is_dir($victro_pasta . $victro_arquivo . '/view')
                        ) {
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
    }
?>