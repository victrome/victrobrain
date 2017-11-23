<?php

require_once("database.php");
date_default_timezone_set('America/Sao_Paulo');
require_once("accents.php");
require_once("translation.php");

class VictroFunc extends victro_DBconnect {
    public function notification_ajax() {
        $victro_conn = $this->defaultConnection();
        $victro_id = $_SESSION['iduser'];
        $victro_idmask1 = '%,' . $victro_id . ',%';
        $victro_idmask2 = $victro_id . ',%';
        $victro_idmask3 = '%,' . $victro_id;
        $victro_idmask4 = $victro_id;
        $victro_tb = $victro_conn->prepare("Select * from victro_notifications where seen_by_user not like :id1 and seen_by_user not like :id2 and seen_by_user not like :id3 and seen_by_user <> :id4 order by ID DESC");
        $victro_tb->bindParam(":id1", $victro_idmask1, PDO::PARAM_STR);
        $victro_tb->bindParam(":id2", $victro_idmask2, PDO::PARAM_STR);
        $victro_tb->bindParam(":id3", $victro_idmask3, PDO::PARAM_STR);
        $victro_tb->bindParam(":id4", $victro_idmask4, PDO::PARAM_STR);
        $victro_tb->execute();
        $victro_cont = $victro_tb->rowCount();
        if ($victro_cont >= 1) {
            $victro_numnoti = 0;
            while ($victro_notif = $victro_tb->fetch(PDO::FETCH_ASSOC)) {
                $victro_explodeu = explode(':', $victro_notif['ntype']);
                if (count($victro_explodeu) == 1) {
                    if ($this->allow_user_type_by($victro_notif['ntype']) == 1) {
                        $victro_notif1['subject'][] = $victro_notif['subject'];
                        $victro_notif1['icon'][] = $victro_notif['icon'];
                        $victro_notif1['id'][] = $victro_notif['id'];
                        $victro_notif1['link'][] = $victro_notif['link'];
                        $victro_notif1['ntype'][] = $victro_notif['ntype'];
                        $victro_notif1['who_send'][] = $victro_notif['who_send'];
                        $victro_numnoti++;
                    }
                } else {
                    if ($victro_explodeu[0] == 't') {
                        if ($this->allow_user_type_by($victro_explodeu[1]) == 1) {
                            $victro_notif1['subject'][] = $victro_notif['subject'];
                            $victro_notif1['icon'][] = $victro_notif['icon'];
                            $victro_notif1['id'][] = $victro_notif['id'];
                            $victro_notif1['link'][] = $victro_notif['link'];
                            $victro_notif1['ntype'][] = $victro_notif['ntype'];
                            $victro_notif1['who_send'][] = $victro_notif['who_send'];
                            $victro_numnoti++;
                        }
                    } else if ($victro_explodeu[0] == 'u' and $victro_explodeu[1] == $_SESSION['iduser']) {
                        $victro_notif1['subject'][] = $victro_notif['subject'];
                        $victro_notif1['icon'][] = $victro_notif['icon'];
                        $victro_notif1['id'][] = $victro_notif['id'];
                        $victro_notif1['link'][] = $victro_notif['link'];
                        $victro_notif1['ntype'][] = $victro_notif['ntype'];
                        $victro_notif1['who_send'][] = $victro_notif['who_send'];
                        $victro_numnoti++;
                    }
                }
                $victro_notif1['count'] = $victro_numnoti;
            }
            return($victro_notif1);
        } else {
            $victro_notif1['count'] = 0;
            return($victro_notif1);
        }
    }

    public function victro_sendfile(Array $victro_type, $victro_sendfile, $victro_size = 2, $victro_access = 1, $victro_pass = null) {
        global $victro_maker;
        if ($victro_pass == null) {
            $victro_pass = time();
        }
        $_UP['pasta'] = 'victro_apps/victro_storage/system/';
        if (!is_dir($_UP['pasta'])) {
            mkdir($_UP['pasta']);
        }
        $_UP['tamanho'] = 1024 * 1024 * $victro_size; // ex 1024 * 1024 * 2 = 2mb
        $victro_ext = explode('.', $victro_sendfile['name']);
        $victro_extensao = strtolower(end($victro_ext));
        if (array_search($victro_extensao, $victro_type) === false) {
            exit;
        }
        if ($_UP['tamanho'] < $victro_sendfile['size']) {
            return('error');
            exit;
        }
        $victro_numfile = mt_rand(0, 1000);
        $victro_convert_to_victro = file_get_contents($victro_sendfile['tmp_name']);
        $victro_convert_to_victro1 = base64_encode(SESSION_NAME . '+?+|+?+' . $victro_convert_to_victro . '+?+|+?+victrohihi');
        $victro_nome_final = md5(time() . $victro_sendfile['name'] . $victro_numfile) . '.victro';
        $victro_fp = fopen($_UP['pasta'] . $victro_nome_final, "a");
        $victro_escreve = fwrite($victro_fp, $victro_convert_to_victro1);
        fclose($victro_fp);
        $victro_value['name'] = $victro_nome_final;
        $victro_value['type'] = $victro_extensao;
        $victro_value['accessible'] = $victro_access;
        $victro_value['data'] = date('Y-m-d');
        $victro_value['pass'] = $victro_pass;
        $victro_value['folder'] = 'system';
        $victro_id = $this->send_file_db($victro_value);
        $victro_ret['id'] = $victro_id;
        $victro_ret['name'] = $victro_nome_final;
        return($victro_ret);
    }

    public function search_ajax($victro_search) {
        $victro_conn = $this->defaultConnection();
        $victro_id = $_SESSION['typeuser'];
        $victro_smask1 = '%' . $victro_search . '%';
        $victro_smask2 = $victro_search . '%';
        $victro_smask3 = '%' . $victro_search;
        $victro_smask4 = $victro_search;
        $victro_tb = $victro_conn->prepare("Select * from victro_search where questions like :sm1 or questions like :sm2 or questions like :sm3 or questions = :sm4 order by ID DESC");
        $victro_tb->bindParam(":sm1", $victro_smask1, PDO::PARAM_STR);
        $victro_tb->bindParam(":sm2", $victro_smask2, PDO::PARAM_STR);
        $victro_tb->bindParam(":sm3", $victro_smask3, PDO::PARAM_STR);
        $victro_tb->bindParam(":sm4", $victro_smask4, PDO::PARAM_STR);
        $victro_tb->execute();
        echo $victro_cont = $victro_tb->rowCount();
        if ($victro_cont >= 1) {
            $victro_numnoti = 0;
            while ($victro_notif = $victro_tb->fetch(PDO::FETCH_ASSOC)) {
                if ($this->allow_user_type_by($victro_notif['who_can_see']) == 1) {
                    $victro_notif1['action'][] = $victro_notif['action'];
                    $victro_notif1['info'][] = $victro_notif['info'];
                    $victro_notif1['id'][] = $victro_notif['id'];
                    $victro_notif1['who_install'][] = $victro_notif['who_install'];
                    $victro_notif1['questions'][] = $victro_notif['questions'];
                    $victro_numnoti++;
                }
                $victro_notif1['count'] = $victro_numnoti;
            }
            return($victro_notif1);
        } else {
            $victro_notif1['count'] = 0;
            return($victro_notif1);
        }
    }

    public function read_notification($victro_id) {
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("Select * from victro_notifications where id = :id");
        $victro_tb->bindParam(":id", $victro_id, PDO::PARAM_STR);
        $victro_tb->execute();
        $victro_datas = $victro_tb->fetch(PDO::FETCH_ASSOC);
        $victro_cont = $victro_tb->rowCount();
        if ($victro_cont >= 1) {
            $victro_seen1 = explode(',', $victro_datas['seen_by_user']);
            if (in_array($_SESSION['iduser'], $victro_seen1)) {
                return($victro_datas);
            } else {
                if ($victro_seen1[0] == 0 or $victro_seen1[0] == '' or $victro_seen1[0] == ' ') {
                    unset($victro_seen1[0]);
                }
                $victro_seen1[] = $_SESSION['iduser'];
                $victro_seen = implode(',', $victro_seen1);
                $victro_tb = $victro_conn->prepare("update victro_notifications set seen_by_user = :seen where id = :id");
                $victro_tb->bindParam(":id", $victro_id, PDO::PARAM_STR);
                $victro_tb->bindParam(":seen", $victro_seen, PDO::PARAM_STR);
                $victro_tb->execute();
                return($victro_datas);
            }
        }
    }
    public function getmenurobot(){
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("select * from victro_menu where active = 1 and submenu = 0 order by id_robot asc");
        $victro_tb->execute();
        $victro_cont = $victro_tb->rowCount();
        $victro_menu = array();
        if ($victro_cont >= 1) {
            while ($victro_menus = $victro_tb->fetch(PDO::FETCH_ASSOC)) {
                if($this->allow_user_type_by($victro_menus['who_see']) == 1){
                    $victro_url_bot = $this->get_url_plugin('bot/' . $victro_menus['id_robot']);
                    $victro_tb_submenu = $victro_conn->prepare("select * from victro_menu where active = 1 and submenu = {$victro_menus['id']}  order by id asc");
                    $victro_tb_submenu->execute();
                    $victro_tbBot = $victro_conn->prepare("select local_url from victro_robot where id = {$victro_menus['id_robot']}");
                    $victro_tbBot->execute();
                    while ($victro_robotSQL = $victro_tbBot->fetch(PDO::FETCH_ASSOC)) {
                        unset($_SESSION['local_url']);
                        $_SESSION['local_url'] = $victro_robotSQL['local_url'];
                        require_once(PATH_SYSTEM.PATH_SYSTEM."bot_translation.php");
                    }
                    if($victro_tb_submenu->rowCount() > 0){
                        $victro_link = SITE_URL . $victro_url_bot . str_replace(' ', '', strtolower(removeaccent($victro_menus['name'])));
                        $victro_menus['link'] = $victro_link;
                    } else {
                        $victro_menus['link'] = '#';
                    }
                    $victro_menus['name'] = bot_translate($victro_menus['name'], 1 ,true);
                    $victro_menu[$victro_menus['id']]['submenu'] = array();
                    $victro_menu[$victro_menus['id']]['menu'] = $victro_menus;
                    while ($victro_submenus = $victro_tb_submenu->fetch(PDO::FETCH_ASSOC)) {
                        if($this->allow_user_type_by($victro_submenus['who_see']) == 1){
                            $victro_link = SITE_URL . $victro_url_bot . str_replace(' ', '', strtolower(removeaccent($victro_submenus['name'])));
                            $victro_submenus['link'] = $victro_link;
                            $victro_submenus['name'] = bot_translate($victro_submenus['name'], 1 ,true);
                            $victro_menu[$victro_menus['id']]['submenu'][] = $victro_submenus;
                        }
                    }
                    unset($victro_language);
                    unset($_SESSION['local_url']);
                }
            }
        }
        $victro_page = THEME_FULLDIR . "verticalmenu.php";
        include($victro_page);
        unset($_SESSION['local_url']);
        //return($victro_menu);
    }
    public function getmenurobot1() {
        GLOBAL $victro_language;
        GLOBAL $victro_action;
        GLOBAL $victro_url;
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("select * from victro_robot where active = 1");
        $victro_tb->execute();
        $victro_cont = $victro_tb->rowCount();
        if ($victro_cont >= 1) {
            while ($victro_menus = $victro_tb->fetch(PDO::FETCH_ASSOC)) {
                $victro_id[] = $victro_menus['id'];
                $victro_name[] = $victro_menus['name'];
                $victro_local_url[] = $victro_menus['local_url'];
                $victro_data[] = $victro_menus['data'];
                $victro_user[] = $victro_menus['user'];
                $victro_icon[] = $victro_menus['icon'];
                $victro_submenus[] = unserialize($victro_menus['submenus']);
                $victro_menu[] = unserialize($victro_menus['menu']);
            }
            $victro_i = 0;
            foreach ($victro_menu as $victro_itens) {
                $victro_permition_menu = $this->allow_user_type_by($victro_itens['user']);
                if ($victro_permition_menu == 1) {
                    $victro_t_menu[] = $victro_itens['name'];
                    $victro_t_icon[] = $victro_icon[$victro_i];
                    $victro_url_plugin[] = $this->get_url_plugin('bot/' . $victro_id[$victro_i]);
                    foreach ($victro_submenus[$victro_i] as $victro_subs) {
                        $victro_permit_sub = (!isset($victro_subs['user']) || $victro_subs['user'] == "" ? 0 : $victro_subs['user']);
                        $victro_permition_sub = $this->allow_user_type_by($victro_permit_sub);
                        $victro_url_plugin1 = $this->get_url_plugin('bot/' . $victro_id[$victro_i]);
                        if ($victro_permition_sub == 1) {
                            $victro_t_sub[$victro_i][] = '<a href="' . SITE_URL . $victro_url_plugin1 . str_replace(' ', '', strtolower(removeaccent($victro_subs['name']))) . '">' . $victro_subs['name'] . '</a>';
                            ;
                        } else {
                            $victro_t_sub[$victro_i][] = "ERROR";
                        }
                    }
                } else {
                    $victro_t_menu[$victro_i] = "ERROR";
                    $victro_t_icon[$victro_i] = "fa fa-plug";
                    $victro_t_sub[$victro_i][] = "ERROR";
                }
                $victro_i++;
            }
            $victro_page = THEME_FULLDIR . "verticalmenu.php";
            include($victro_page);
        }
    }

    public function allow_user_type_by($victro_id) {
        global $victro_site;
        $victro_permited = 0;
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
        return($victro_permited);
    }

    public function getmenuplugin() {
        GLOBAL $victro_language;
        GLOBAL $victro_link;
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("select * from victro_robot where active = 1");
        $victro_tb->execute();
        $victro_cont = $victro_tb->rowCount();
        if ($victro_cont >= 1) {
            while ($victro_menus = $victro_tb->fetch(PDO::FETCH_ASSOC)) {
                $victro_id[] = $victro_menus['id'];
                $victro_name[] = $victro_menus['name'];
                $victro_local_url[] = $victro_menus['local_url'];
                $victro_data[] = $victro_menus['data'];
                $victro_user[] = $victro_menus['user'];
                $victro_tables[] = $victro_menus['tables'];
                $victro_submenus[] = $victro_menus['submenus'];
                $victro_requires[] = $victro_menus['requires'];
                $victro_menu[] = $victro_menus['menu'];
                $victro_func = self::gettheme();
                GLOBAL $victro_url;
                GLOBAL $victro_action;
            }
            $victro_i = 0;
            foreach ($victro_menu as $victro_itens) {
                $victro_t_menu[] = $victro_itens;
                if ($victro_submenus[$victro_i] != '' or $victro_submenus[$victro_i] != '*') {
                    $victro_subarray = explode("!?!", $victro_submenus[$victro_i]);
                    foreach ($victro_subarray as $victro_subs) {
                        $victro_subdata = explode('>', $victro_subs);
                        if (!isset($victro_subdata[1]) or empty($victro_subdata[1])) {
                            $victro_t_sub[] = '<a>ERROR</a>';
                        } else {
                            $victro_link = self::getsiteurl();
                            $victro_link1 = $victro_link['full_url'];
                            $victro_empty = 0;
                            $victro_url_plugin = $this->get_url_plugin('bot/' . $victro_id[$victro_i]);
                            if ($victro_subdata[1] == '1' and $_SESSION['typeuser'] >= 1) {
                                $victro_empty = 1;
                                $victro_t_sub[] = '<a href="' . $victro_link1 . $victro_url_plugin . invert_accent(str_replace(' ', '', strtolower($victro_subdata[0]))) . '">' . $victro_subdata[0] . '</a>';
                            }
                            if ($victro_subdata[1] == '=1' and $_SESSION['typeuser'] == 1) {
                                $victro_empty = 1;
                                $victro_t_sub[] = '<a href="' . $victro_link1 . $victro_url_plugin . invert_accent(str_replace(' ', '', strtolower($victro_subdata[0]))) . '">' . $victro_subdata[0] . '</a>';
                            }
                            if ($victro_subdata[1] == '2' and $_SESSION['typeuser'] >= 2) {
                                $victro_empty = 1;
                                $victro_t_sub[] = '<a href="' . $victro_link1 . $victro_url_plugin . invert_accent(str_replace(' ', '', strtolower($victro_subdata[0]))) . '">' . $victro_subdata[0] . '</a>';
                            }
                            if ($victro_subdata[1] == '=2' and $_SESSION['typeuser'] == 2) {
                                $victro_empty = 1;
                                $victro_t_sub[] = '<a href="' . $victro_link1 . $victro_url_plugin . str_replace(' ', '', strtolower($victro_subdata[0])) . '">' . $victro_subdata[0] . '</a>';
                            }
                            if ($victro_subdata[1] == '3' and $_SESSION['typeuser'] >= 3) {
                                $victro_empty = 1;
                                $victro_t_sub[] = '<a href="' . $victro_link1 . $victro_url_plugin . invert_accent(str_replace(' ', '', strtolower($victro_subdata[0]))) . '">' . $victro_subdata[0] . '</a>';
                            }
                            if ($victro_subdata[1] == '=3' and $_SESSION['typeuser'] == 3) {
                                $victro_empty = 1;
                                $victro_t_sub[] = '<a href="' . $victro_link1 . $victro_url_plugin . invert_accent(str_replace(' ', '', strtolower($victro_subdata[0]))) . '">' . $victro_subdata[0] . '</a>';
                            }
                            if ($victro_subdata[1] == '4' and $_SESSION['typeuser'] >= 4) {
                                $victro_empty = 1;
                                $victro_t_sub[] = '<a href="' . $victro_link1 . $victro_url_plugin . str_replace(' ', '', strtolower($victro_subdata[0])) . '">' . $victro_subdata[0] . '</a>';
                            }
                            if ($victro_subdata[1] == '=4' and $_SESSION['typeuser'] == 4) {
                                $victro_empty = 1;
                                $victro_t_sub[] = '<a href="' . $victro_link1 . $victro_url_plugin . invert_accent(str_replace(' ', '', strtolower($victro_subdata[0]))) . '">' . $victro_subdata[0] . '</a>';
                            }
                            if ($victro_subdata[1] == '5' and $_SESSION['typeuser'] == 5) {
                                $victro_empty = 1;
                                $victro_t_sub[] = '<a href="' . $victro_link1 . $victro_url_plugin . invert_accent(str_replace(' ', '', strtolower($victro_subdata[0]))) . '">' . $victro_subdata[0] . '</a>';
                            }
                            if ($victro_empty == 0) {
                                $victro_t_sub[] = 'empty';
                            }
                        }
                    }
                    if (isset($victro_t_sub)) {
                        $victro_t_subs[] = implode('%%', $victro_t_sub);
                        $victro_t_sub = array();
                    }
                }
                $victro_i++;
            }
            $victro_page = THEME_FULLDIR . "verticalmenu.php";
            include($victro_page);
        }
    }

    public function robot_in_db($victro_id_install) {
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("Select active from victro_robot where id_install = :id and active = 1");
        $victro_tb->bindParam(":id", $victro_id_install, PDO::PARAM_INT);
        $victro_tb->execute();
        if ($victro_tb->rowCount() >= 1) {
                $victro_bt = 1;
        } else {
            $victro_bt = 0;
        }
        return($victro_bt);
    }

    public function robot_uninstall($victro_id) {
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("update victro_robot set active = 2 where id_install = :id");
        $victro_tb->bindParam(":id", $victro_id, PDO::PARAM_INT);
        $victro_tb->execute();
    }

    public function power_update_table($victro_id) {
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("update victro_power set install_db = 1 where id_power = :id");
        $victro_tb->bindParam(":id", $victro_id, PDO::PARAM_STR);
        $victro_tb->execute();
    }

    public function load_power($victro_id) {
        $victro_conn = $this->defaultConnection();
        $victro_idmask1 = '%' . $victro_id . '%';
        $victro_idmask2 = $victro_id . '%';
        $victro_idmask3 = '%' . $victro_id;
        $victro_idmask4 = $victro_id;
        $victro_tb = $victro_conn->prepare("Select * from victro_power where robot like :id1 or robot like :id2 or robot like :id3 or robot = :id4");
        $victro_tb->bindParam(":id1", $victro_idmask1, PDO::PARAM_STR);
        $victro_tb->bindParam(":id2", $victro_idmask2, PDO::PARAM_STR);
        $victro_tb->bindParam(":id3", $victro_idmask3, PDO::PARAM_STR);
        $victro_tb->bindParam(":id4", $victro_idmask4, PDO::PARAM_STR);
        $victro_tb->execute();
        if ($victro_tb->rowCount() >= 1) {
            while ($victro_data = $victro_tb->fetch(PDO::FETCH_ASSOC)) {
                include_once($victro_data['local_url'] . '/functions.php');
            }
        }
    }

    public function insert_new_user(array $victro_user) {
        $victro_conn = $this->defaultConnection();
        $victro_tb1 = $victro_conn->prepare("INSERT INTO victro_users VALUES (NULL, :type, :name, :username, :email, :pass, 0, :pic)");
        $victro_tb1->bindParam(":type", $victro_user['type'], PDO::PARAM_STR);
        $victro_tb1->bindParam(":name", $victro_user['name'], PDO::PARAM_STR);
        $victro_tb1->bindParam(":username", $victro_user['username'], PDO::PARAM_STR);
        $victro_tb1->bindParam(":email", $victro_user['email'], PDO::PARAM_STR);
        $victro_tb1->bindParam(":pass", $victro_user['pass'], PDO::PARAM_STR);
        $victro_tb1->bindParam(":pic", $victro_user['pic'], PDO::PARAM_STR);
        $victro_tb1->execute();
    }

    public function update_edit_user(array $victro_user) {
        $victro_conn = $this->defaultConnection();
        if ($_SESSION['typeuser'] == 5) {
            $victro_tb1 = $victro_conn->prepare("update victro_users set type = {$victro_user['type']} , name = '{$victro_user['name']}', username = '{$victro_user['username']}', email = '{$victro_user['email']}' where id = {$victro_user['id']}");
            $victro_tb1->execute();
        }
    }

    public function robot_install($victro_name, $victro_dir, $victro_data, $victro_user, $victro_tables, $victro_submenus, $victro_menu, $victro_author, $victro_version, $victro_icon) {
        $victro_conn = $this->defaultConnection();
        $victro_name = trim($victro_name);
        $victro_author = trim($victro_author);
        $victro_tables = trim($victro_tables);
        $victro_menu = trim($victro_menu);
        $victro_submenus = trim($victro_submenus);
        $victro_dir = trim($victro_dir);
        $victro_id_install = md5($victro_dir);
        $victro_tb = $victro_conn->prepare("Select * from victro_robot where id_install = :id_install and active = 2");
        $victro_tb->bindParam(":id_install", $victro_id_install, PDO::PARAM_STR);
        $victro_tb->execute();
        if ($victro_tb->rowCount() <= 0) {
            $victro_tb1 = $victro_conn->prepare("INSERT INTO victro_robot VALUES (NULL, :name, :local, :data, :user, :table, :submenu, :menu, :author, '1', :version, :id_install, :icon)");
            $victro_tb1->bindParam(":name", $victro_name, PDO::PARAM_STR);
            $victro_tb1->bindParam(":local", $victro_dir, PDO::PARAM_STR);
            $victro_tb1->bindParam(":data", $victro_data, PDO::PARAM_STR);
            $victro_tb1->bindParam(":user", $victro_user, PDO::PARAM_STR);
            $victro_tb1->bindParam(":table", $victro_tables, PDO::PARAM_STR);
            $victro_tb1->bindParam(":submenu", $victro_submenus, PDO::PARAM_STR);

            $victro_tb1->bindParam(":menu", $victro_menu, PDO::PARAM_STR);
            $victro_tb1->bindParam(":author", $victro_author, PDO::PARAM_STR);
            $victro_tb1->bindParam(":version", $victro_version, PDO::PARAM_STR);
            $victro_tb1->bindParam(":id_install", $victro_id_install, PDO::PARAM_STR);
            $victro_tb1->bindParam(":icon", $victro_icon, PDO::PARAM_STR);
            $victro_tb1->execute();
        } else {
            $victro_tb2 = $victro_conn->prepare("update victro_robot set active = 1, menu = :menu, submenus = :submenu, version = :version, icon = :icon, tables = :table, name = :name where id_install = :id_install and active = 2");
            $victro_tb2->bindParam(":id_install", $victro_id_install, PDO::PARAM_STR);
            $victro_tb2->bindParam(":table", $victro_tables, PDO::PARAM_STR);
            $victro_tb2->bindParam(":submenu", $victro_submenus, PDO::PARAM_STR);
            $victro_tb2->bindParam(":menu", $victro_menu, PDO::PARAM_STR);
            $victro_tb2->bindParam(":version", $victro_version, PDO::PARAM_STR);
            $victro_tb2->bindParam(":icon", $victro_icon, PDO::PARAM_STR);
            $victro_tb2->bindParam(":name", $victro_name, PDO::PARAM_STR);
            $victro_tb2->execute();
        }
    }

    public function power_create_table($victro_tables) {
        foreach ($victro_tables as $victro_table) {
            if (isset($victro_table['name']) and $victro_table['name'] != null) {
                foreach ($victro_table['column'] as $victro_column) {
                    if (isset($victro_column['name']) and $victro_column['name'] != null and isset($victro_column['type']) and $victro_column['type'] != null and isset($victro_column['value'])) {
                        if ($victro_column['value'] == null || $victro_column['value'] == '' || $victro_column['value'] == " ") {
                            $victro_value = '';
                        } else {
                            $victro_value = '(' . $victro_column['value'] . ')';
                        }
                        $victro_sqlcreate = "`{$victro_column['name']}` {$victro_column['type']}" . $victro_value;
                        if (isset($victro_column['default']) and $victro_column['default'] != null) {
                            $victro_sqlcreate .= " DEFAULT '{$victro_column['default']}'";
                        }
                        if (isset($victro_column['collation']) and $victro_column['collation'] != null) {
                            $victro_sqlcreate .= " COLLATE {$victro_column['collation']}";
                        }
                        if (isset($victro_column['null']) and $victro_column['null'] != null) {
                            if ($victro_column['null'] == true) {
                                $victro_nulo = "NULL";
                            } else {
                                $victro_nulo = "NOT NULL";
                            }
                            $victro_sqlcreate .= " {$victro_nulo}";
                        }
                        if (isset($victro_column['attribute']) and $victro_column['attribute'] != null) {
                            $victro_sqlcreate .= " {$victro_column['attribute']}";
                        }
                        if (isset($victro_column['autoincrement']) and $victro_column['autoincrement'] == true) {
                            $victro_sqlcreate .= " AUTO_INCREMENT";
                        }
                        if (isset($victro_column['comments']) and $victro_column['comments'] != null) {
                            $victro_sqlcreate .= " COMMENT '{$victro_column['comments']}'";
                        }
                        if (isset($victro_column['index']) and $victro_column['index'] != null) {
                            $victro_sqlcreate .= " , {$victro_column['index']}(`{$victro_column['name']}`)";
                        }
                        $victro_sqlcolumn[] = $victro_sqlcreate;
                        $victro_sqlcreate = null;
                    }
                }
                $victro_sqlcolumns = implode(", ", $victro_sqlcolumn);

                $victro_conn = $this->defaultConnection();
                $victro_tb = $victro_conn->prepare("CREATE TABLE IF NOT EXISTS bs_{$victro_table['name']}({$victro_sqlcolumns}) ENGINE=MyISAM;");
                //echo "CREATE TABLE IF NOT EXISTS bot_{$victro_table['name']}({$victro_sqlcolumns}) ENGINE=MyISAM;<BR>";
                try {
                    $victro_tb->execute();
                    return(true);
                } catch (PDOException $victro_e) {
                    return(false);
                    echo "<script>alert('" . $victro_e->getMessage() . "'); </script>";
                }
                $victro_sqlcolumn = array();
                $victro_sqlcolumns = null;
            }
        }
    }

    public function robot_setquery() {
        $victro_conn = $this->defaultConnection();
        $_SESSION['debug_create_tables'] = $_SESSION['query_bot'];
        if (isset($_SESSION['query_bot']['create'])) {
            foreach ($_SESSION['query_bot']['create'] as $victro_create) {
                $victro_tb = $victro_conn->prepare($victro_create);
                try {
                    $victro_tb->execute();
                } catch (PDOException $victro_e) {

                }
            }
        }
        if (isset($_SESSION['query_bot']['alter'])) {
            foreach ($_SESSION['query_bot']['alter'] as $victro_alter) {
                $victro_tb = $victro_conn->prepare($victro_alter);
                try {
                    $victro_tb->execute();
                } catch (PDOException $victro_e) {

                }
            }
        }
        if (isset($_SESSION['query_bot']['delete'])) {
            foreach ($_SESSION['query_bot']['delete'] as $victro_delete) {
                $victro_tb = $victro_conn->prepare($victro_delete);
                try {
                    $victro_tb->execute();
                } catch (PDOException $victro_e) {

                }
            }
        }
        if (isset($_SESSION['query_bot']['add'])) {
            foreach ($_SESSION['query_bot']['add'] as $victro_add) {
                $victro_tb = $victro_conn->prepare($victro_add);
                try {
                    $victro_tb->execute();
                } catch (PDOException $victro_e) {

                }
            }
        }
        if (isset($_SESSION['query_bot']['index'])) {
            foreach ($_SESSION['query_bot']['index'] as $victro_indexq) {
                $victro_tb = $victro_conn->prepare($victro_indexq);
                try {
                    $victro_tb->execute();
                } catch (PDOException $victro_e) {

                }
            }
        }
        unset($_SESSION['query_bot']);
    }

    public function robot_create_table($victro_tables) {
        unset($_SESSION['query_bot']);
        $_SESSION['query_bot']['delete'] = array();
        $_SESSION['query_bot']['alter'] = array();
        $_SESSION['query_bot']['create'] = array();
        $_SESSION['query_bot']['add'] = array();
        $_SESSION['query_bot']['index'] = array();
        foreach ($victro_tables as $victro_table) {
            if (isset($victro_table['name']) and $victro_table['name'] != null) {
                $victro_table['name'] = 'bot_' . str_replace("bot_", "", $victro_table['name']);
                $victro_conn = $this->defaultConnection();
                $victro_tb = $victro_conn->prepare("describe {$victro_table['name']}");
                try {
                    $victro_tb->execute();
                } catch (PDOException $victro_e) {
                    $victro_notable = true;
                }
                if (isset($victro_notable) and $victro_notable == true and $victro_tb->rowCount() <= 0) {
                    foreach ($victro_table['column'] as $victro_column) {
                        if (isset($victro_column['name']) and $victro_column['name'] != null and isset($victro_column['type']) and $victro_column['type'] != null and isset($victro_column['value'])) {
                            if ($victro_column['value'] == null || $victro_column['value'] == '' || $victro_column['value'] == " ") {
                                $victro_value = '';
                            } else {
                                $victro_value = '(' . $victro_column['value'] . ')';
                            }
                            $victro_sqlcreate = "`{$victro_column['name']}` {$victro_column['type']}" . $victro_value;
                            if (isset($victro_column['default']) and $victro_column['default'] != null) {
                                $victro_sqlcreate .= " DEFAULT '{$victro_column['default']}'";
                            }
                            if (isset($victro_column['collation']) and $victro_column['collation'] != null) {
                                $victro_sqlcreate .= " COLLATE {$victro_column['collation']}";
                            }
                            if (isset($victro_column['null']) and $victro_column['null'] != null) {
                                if ($victro_column['null'] == true) {
                                    $victro_nulo = "NULL";
                                } else {
                                    $victro_nulo = "NOT NULL";
                                }
                                $victro_sqlcreate .= " {$victro_nulo}";
                            }
                            if (isset($victro_column['attribute']) and $victro_column['attribute'] != null) {
                                $victro_sqlcreate .= " {$victro_column['attribute']}";
                            }
                            if (isset($victro_column['autoincrement']) and $victro_column['autoincrement'] == true) {
                                $victro_sqlcreate .= " AUTO_INCREMENT";
                            }
                            if (isset($victro_column['comments']) and $victro_column['comments'] != null) {
                                $victro_sqlcreate .= " COMMENT '{$victro_column['comments']}'";
                            }
                            if (isset($victro_column['index']) and $victro_column['index'] != null) {
                                $victro_sqlcreate .= " , {$victro_column['index']}(`{$victro_column['name']}`)";
                            }
                            $victro_sqlcolumn[] = $victro_sqlcreate;
                            $victro_sqlcreate = null;
                        }
                    }
                    $victro_sqlcolumns = implode(", ", $victro_sqlcolumn);


                    $victro_sqlcreatequery = "CREATE TABLE IF NOT EXISTS {$victro_table['name']}({$victro_sqlcolumns}) ENGINE=MyISAM;";
                    $_SESSION['query_bot']['create'][] = $victro_sqlcreatequery;
                    //echo "CREATE TABLE IF NOT EXISTS bot_{$victro_table['name']}({$victro_sqlcolumns}) ENGINE=MyISAM;<BR>";
                    $victro_sqlcolumn = array();
                    $victro_sqlcolumns = null;
                } else {
                    $victro_field = array();
                    $victro_type = array();
                    $victro_null = array();
                    $victro_key_ar = array();
                    $victro_default = array();
                    $victro_extra = array();
                    while ($victro_camp = $victro_tb->fetch(PDO::FETCH_ASSOC)) {
                        $victro_field[] = $victro_camp['Field'];
                        $victro_type[] = $victro_camp['Type'];
                        $victro_null[] = $victro_camp['Null'];
                        $victro_key_ar[] = $victro_camp['Key'];
                        $victro_default[] = $victro_camp['Default'];
                        $victro_extra[] = $victro_camp['Extra'];
                    }
                    foreach ($victro_table['column'] as $victro_column) {
                        if (isset($victro_column['name']) and $victro_column['name'] != null and isset($victro_column['type']) and $victro_column['type'] != null and isset($victro_column['value'])) {
                            if ($victro_column['value'] == null || $victro_column['value'] == '' || $victro_column['value'] == " ") {
                                $victro_value = '';
                            } else {
                                $victro_value = '(' . $victro_column['value'] . ')';
                            }

                            if (in_array($victro_column['name'], $victro_field)) {
                                $victro_key = array_search($victro_column['name'], $victro_field);
                                if ($victro_type[$victro_key] != $victro_column['type'] . $victro_value or ( isset($victro_column['null']) and $victro_column['null'] == TRUE and $victro_null[$victro_key] == 'NO')) {
                                    $victro_sqlalter = "`{$victro_column['name']}` {$victro_column['type']}" . $victro_value;
                                    if (isset($victro_column['default']) and $victro_column['default'] != null) {
                                        $victro_sqlalter .= " DEFAULT '{$victro_column['default']}'";
                                    }
                                    if (isset($victro_column['collation']) and $victro_column['collation'] != null) {
                                        $victro_sqlalter .= " COLLATE {$victro_column['collation']}";
                                    }
                                    if (isset($victro_column['null']) and $victro_column['null'] != null) {
                                        if ($victro_column['null'] == true) {
                                            $victro_nulo = "NULL";
                                        } else {
                                            $victro_nulo = "NOT NULL";
                                        }
                                        $victro_sqlalter .= " {$victro_nulo}";
                                    }
                                    if (isset($victro_column['attribute']) and $victro_column['attribute'] != null) {
                                        $victro_sqlalter .= " {$victro_column['attribute']}";
                                    }
                                    if (isset($victro_column['autoincrement']) and $victro_column['autoincrement'] == true) {
                                        $victro_sqlalter .= " AUTO_INCREMENT";
                                    }
                                    if (isset($victro_column['comments']) and $victro_column['comments'] != null) {
                                        $victro_sqlalter .= " COMMENT '{$victro_column['comments']}'";
                                    }
                                    if (isset($victro_column['index']) and $victro_column['index'] != null) {
                                        $victro_sqlindex = "Alter {$victro_table['name']} ADD {$victro_column['index']} (`{$victro_column['name']}`);";
                                        $_SESSION['query_bot']['index'][] = $victro_sqlindex;
                                    }
                                    $victro_sqlalter2 = "Alter table " . $victro_table['name'] . " modify column " . $victro_sqlalter . ';';
                                    $_SESSION['query_bot']['alter'][] = $victro_sqlalter2;
                                }
                                unset($victro_field[$victro_key]);
                                unset($victro_type[$victro_key]);
                                unset($victro_null[$victro_key]);
                                unset($victro_key[$victro_key]);
                                unset($victro_default[$victro_key]);
                                unset($victro_extra[$victro_key]);
                                //alterar campo
                            } else {
                                $victro_sqladd = "`{$victro_column['name']}` {$victro_column['type']}" . $victro_value;
                                if (isset($victro_column['default']) and $victro_column['default'] != null) {
                                    $victro_sqladd .= " DEFAULT '{$victro_column['default']}'";
                                }
                                if (isset($victro_column['collation']) and $victro_column['collation'] != null) {
                                    $victro_sqladd .= " COLLATE {$victro_column['collation']}";
                                }
                                if (isset($victro_column['null']) and $victro_column['null'] != null) {
                                    if ($victro_column['null'] == true) {
                                        $victro_nulo = "NULL";
                                    } else {
                                        $victro_nulo = "NOT NULL";
                                    }
                                    $victro_sqladd .= " {$victro_nulo}";
                                }
                                if (isset($victro_column['attribute']) and $victro_column['attribute'] != null) {
                                    $victro_sqladd .= " {$victro_column['attribute']}";
                                }
                                if (isset($victro_column['autoincrement']) and $victro_column['autoincrement'] == true) {
                                    $victro_sqladd .= " AUTO_INCREMENT";
                                }
                                if (isset($victro_column['comments']) and $victro_column['comments'] != null) {
                                    $victro_sqladd .= " COMMENT '{$victro_column['comments']}'";
                                }
                                if (isset($victro_column['index']) and $victro_column['index'] != null) {
                                    $victro_sqlindex = "Alter {$victro_table['name']} ADD {$victro_column['index']} (`{$victro_column['name']}`);";
                                    $_SESSION['query_bot']['index'][] = $victro_sqlindex;
                                }
                                $victro_sqladd2 = "Alter table " . $victro_table['name'] . " ADD column " . $victro_sqladd . ';';
                                if (!in_array($victro_sqladd2, $_SESSION['query_bot']['add'])) {
                                    $_SESSION['query_bot']['add'][] = $victro_sqladd2;
                                }
                                //adicionar campo
                                unset($victro_field[$victro_key]);
                                unset($victro_type[$victro_key]);
                                unset($victro_null[$victro_key]);
                                unset($victro_key[$victro_key]);
                                unset($victro_default[$victro_key]);
                                unset($victro_extra[$victro_key]);
                            }
                        }
                    }
                    foreach ($victro_field as $victro_fiel) {
                        $victro_sqldel = "Alter table " . $victro_table['name'] . " DROP column " . $victro_fiel . ';';
                        $_SESSION['query_bot']['delete'][] = $victro_sqldel;
                    }
                }
            }
            unset($victro_field);
            unset($victro_type);
            unset($victro_null);
            unset($victro_key);
            unset($victro_default);
            unset($victro_extra);
        }
    }

    public function listusers($victro_type, $victro_upp, $victro_start, $victro_typeuser, $victro_searchby, $victro_search) {
        $victro_conn = $this->defaultConnection();
        if ($victro_typeuser == null or $victro_searchby == null or $victro_search == null) {
            $victro_tb = $victro_conn->prepare("select id, username, type, name, email from victro_users where type <= :type LIMIT :start , :end");
            $victro_tb->bindParam(":type", $victro_type, PDO::PARAM_INT);
            $victro_tb->bindParam(":start", $victro_start, PDO::PARAM_INT);
            $victro_tb->bindParam(":end", $victro_upp, PDO::PARAM_INT);
            $victro_tb->execute();
            $victro_tb2 = $victro_conn->prepare("select id, username, type, name, email from victro_users where type <= :type");
            $victro_tb2->bindParam(":type", $victro_type, PDO::PARAM_INT);
            $victro_tb2->execute();
        } else {
            if ($victro_typeuser > $_SESSION['typeuser']) {
                $victro_typeuser = $_SESSION['typeuser'];
            }
            $victro_tb = $victro_conn->prepare("select id, username, type, name, email from victro_users where type <= :typeuser and ($victro_searchby like '%$victro_search%' or $victro_searchby like '%$victro_search' or $victro_searchby like '$victro_search%') LIMIT :start , :end");
            $victro_tb->bindParam(":typeuser", $victro_typeuser, PDO::PARAM_INT);
            $victro_tb->bindParam(":start", $victro_start, PDO::PARAM_INT);
            $victro_tb->bindParam(":end", $victro_upp, PDO::PARAM_INT);
            $victro_tb->execute();
            $victro_tb2 = $victro_conn->prepare("select id, username, type, name, email from victro_users where type <= :typeuser and ($victro_searchby like '%$victro_search%' or $victro_searchby like '%$victro_search' or $victro_searchby like '$victro_search%')");
            $victro_tb2->bindParam(":typeuser", $victro_typeuser, PDO::PARAM_INT);
            $victro_tb2->execute();
        }
        if ($victro_tb->rowCount() > 0) {
            while ($victro_data = $victro_tb->fetch(PDO::FETCH_ASSOC)) {
                $victro_id[] = $victro_data['id'];
                $victro_username[] = $victro_data['username'];
                $victro_types[] = $victro_data['type'];
                $victro_name[] = $victro_data['name'];
                $victro_email[] = $victro_data['email'];
            }
            $victro_total = $victro_tb2->rowCount();
            $victro_numbers = ceil($victro_total / $victro_upp);
            $victro_usersdata['id'] = $victro_id;
            $victro_usersdata['username'] = $victro_username;
            $victro_usersdata['type'] = $victro_types;
            $victro_usersdata['name'] = $victro_name;
            $victro_usersdata['email'] = $victro_email;
            $victro_usersdata['lastnum'] = $victro_start;
            $victro_usersdata['numberspgs'] = $victro_numbers;
        } else {
            $victro_usersdata = 0;
        }
        return($victro_usersdata);
    }

    public function pagnation($victro_numpg, $victro_ipp) {
        if ($victro_numpg == 1) {
            $victro_n = 0;
        } else
        if ($victro_numpg == 2) {
            $victro_n = $victro_ipp;
        } else {
            if (($victro_numpg % 2) == 0) {
                $victro_ii = $victro_numpg - ($victro_ipp - 2);
                $victro_n = $victro_ii + $victro_ipp;
            } else {
                $victro_ii = $victro_numpg - ($victro_ipp - 1);
                $victro_n = $victro_ii + $victro_ipp;
            }
        }
        return($victro_n);
    }

    public function load_ajax() {
        $victro_site = $this->getsiteurl();
        echo '<script src="' . $victro_site['full_url'] . 'system/js/ajax_global.js"></script>';
    }

    public function getindex() {
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("select index_load from victro_system");
        $victro_tb->execute();
        $victro_data = $victro_tb->fetch(PDO::FETCH_ASSOC);
        if ($victro_data['index_load'] == 'index' or $victro_data['index_load'] == '' or $victro_data['index_load'] == ' ') {
            $victro_index = 'index';
        } else {
            $victro_index = $victro_data['index_load'];
        }
        return($victro_index);
    }

    public function power_in_db($victro_id) {
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("select * from victro_power where id_power = '{$victro_id}'");
        $victro_tb->execute();
        $victro_data['array'] = $victro_tb->fetch(PDO::FETCH_ASSOC);
        $victro_data['count'] = $victro_tb->rowCount();
        return($victro_data);
    }

    public function insert_power(array $victro_power) {
        $victro_conn = $this->defaultConnection();
        $victro_indb = $this->power_in_db($victro_power['id_power']);
        if ($victro_indb['count'] == 0) {
            $victro_tb = $victro_conn->prepare("insert into victro_power(`name`, `local_url`, `robot`,`id_power`, `author`, `description`, `version`) values(:name, :url, :robot, :id_power, :author, :desc, :version)");
            $victro_tb->bindParam(":name", $victro_power['name'], PDO::PARAM_STR);
            $victro_tb->bindParam(":url", $victro_power['url'], PDO::PARAM_STR);
            $victro_tb->bindParam(":robot", $victro_power['robots'], PDO::PARAM_STR);
            $victro_tb->bindParam(":id_power", $victro_power['id_power'], PDO::PARAM_STR);
            $victro_tb->bindParam(":author", $victro_power['author'], PDO::PARAM_STR);
            $victro_tb->bindParam(":desc", $victro_power['description'], PDO::PARAM_STR);
            $victro_tb->bindParam(":version", $victro_power['version'], PDO::PARAM_STR);
            $victro_tb->execute();
        } else {
            $victro_tb = $victro_conn->prepare("update victro_power set name = :name, local_url = :url, robot = :robot, author = :author, description = :desc, version = :version where  id_power = :id_power");
            $victro_tb->bindParam(":name", $victro_power['name'], PDO::PARAM_STR);
            $victro_tb->bindParam(":url", $victro_power['url'], PDO::PARAM_STR);
            $victro_tb->bindParam(":robot", $victro_power['robots'], PDO::PARAM_STR);
            $victro_tb->bindParam(":id_power", $victro_power['id_power'], PDO::PARAM_STR);
            $victro_tb->bindParam(":author", $victro_power['author'], PDO::PARAM_STR);
            $victro_tb->bindParam(":desc", $victro_power['description'], PDO::PARAM_STR);
            $victro_tb->bindParam(":version", $victro_power['version'], PDO::PARAM_STR);
            $victro_tb->execute();
        }
    }

    public function robot_load($victro_id) {
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("select name,local_url from victro_robot where id = :id and active = 1");
        $victro_tb->bindParam(":id", $victro_id, PDO::PARAM_INT);
        $victro_tb->execute();
        $victro_data[0] = $victro_tb->fetch(PDO::FETCH_ASSOC);
        $victro_data[1] = $victro_tb->rowCount();
        return($victro_data);
    }

    public function list_plugins() {
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("select id, name from victro_robot where active = 1");
        $victro_tb->execute();
        while ($victro_p = $victro_tb->fetch(PDO::FETCH_ASSOC)) {
            $victro_dados['id'][] = $victro_p['id'];
            $victro_dados['name'][] = $victro_p['name'];
        }
        return($victro_dados);
    }

    public function update_system($victro_col, $victro_update) {
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("update victro_system set $victro_col = :update where 1 = 1");
        $victro_tb->bindParam(":update", $victro_update, PDO::PARAM_INT);
        $victro_tb->execute();
        header('location: index');
    }

    public function check_friendly_plugin($victro_name) {
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("select url, id_robot from victro_robot_url where url = :url limit 1");
        $victro_tb->bindParam(":url", $victro_name, PDO::PARAM_INT);
        $victro_tb->execute();
        if ($victro_tb->rowCount() >= 1) {
            while ($victro_pl = $victro_tb->fetch(PDO::FETCH_ASSOC)) {
                $victro_dados['id_plugin'][] = $victro_pl['id_robot'];
                $victro_dados['url'][] = $victro_pl['url'];
            }
            return($victro_dados);
        } else {
            return(0);
        }
    }

    public function check_friendly_plugin_byid($victro_id) {
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("select url, id_robot from victro_robot_url where id_robot = :url limit 1");
        $victro_tb->bindParam(":url", $victro_id, PDO::PARAM_INT);
        $victro_tb->execute();
        if ($victro_tb->rowCount() >= 1) {
            while ($victro_pl = $victro_tb->fetch(PDO::FETCH_ASSOC)) {
                $victro_dados['id_plugin'] = $victro_pl['id_robot'];
                $victro_dados['url'] = $victro_pl['url'];
            }
            return($victro_dados);
        } else {
            return(0);
        }
    }

    public function manage_friendly_plugin($victro_type, $victro_id, $victro_url) {
        $victro_conn = $this->defaultConnection();
        if ($victro_type == 'insert') {
            $victro_tb = $victro_conn->prepare("insert into victro_robot_url values (null, :url , :id)");
            $victro_tb->bindParam(":url", $victro_url, PDO::PARAM_STR);
            $victro_tb->bindParam(":id", $victro_id, PDO::PARAM_INT);
            $victro_tb->execute();
        } else if ($victro_type == 'delete') {
            $victro_tb = $victro_conn->prepare("delete from victro_robot_url where url = :url and id_robot = :id");
            $victro_tb->bindParam(":url", $victro_url, PDO::PARAM_STR);
            $victro_tb->bindParam(":id", $victro_id, PDO::PARAM_INT);
            $victro_tb->execute();
        } else if ($victro_type == 'update') {
            $victro_tb = $victro_conn->prepare("update victro_robot_url set url = :url where id_robot = :id");
            $victro_tb->bindParam(":url", $victro_url, PDO::PARAM_STR);
            $victro_tb->bindParam(":id", $victro_id, PDO::PARAM_INT);
            $victro_tb->execute();
        }
        header('location: index');
    }

    public function get_url_plugin($victro_url) {
        $victro_url1 = explode('/', $victro_url);
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("select id, route from victro_robot where id = :url limit 1");
        $victro_tb->bindParam(":url", $victro_url1[1], PDO::PARAM_INT);
        $victro_tb->execute();
        if ($victro_tb->rowCount() >= 1) {
            while ($victro_pl = $victro_tb->fetch(PDO::FETCH_ASSOC)) {
                $victro_dados['id_robot'] = $victro_pl['id'];
                $victro_dados['url'] = $victro_pl['route'];
            }
            if(strlen($victro_dados['url']) > 3){
                return($victro_dados['url'] . '/');
            } else {
                return('bot/' . $victro_url1[1] . '/');
            }
        } else {
            return('bot/' . $victro_url1[1] . '/');
        }
    }

    public function fs($victro_pasta = ".", $victro_i = 0, $victro_files = array()) {
        $victro_diretorio = opendir($victro_pasta);
        while ($victro_arquivo = readdir($victro_diretorio)) {
            if ($victro_arquivo != "." && $victro_arquivo != "..") {
                $victro_path = $victro_pasta . "/" . $victro_arquivo;
                for ($victro_c = 0; $victro_c < $victro_i; $victro_c++) {

                }
                if (is_dir($victro_path)) {
                    //echo "<b>+ ".$victro_arquivo."</b><br>\n";
                    $this->fs($victro_path, $victro_i + 1, $victro_files);
                } else {
                    $_SESSION['victro_update'][] = $victro_path;
                }
            }
        }

        closedir($victro_diretorio);
    }

    public function new_widget($victro_name, $victro_type, $victro_query, $victro_php, $victro_html, $victro_width, $victro_who, $victro_showb, $victro_textb, $victro_actionb) {
        $victro_conn = $this->defaultConnection();
        $victro_who1 = implode(",", $victro_who);
        if ($victro_type == "HTML") {
            $victro_htmlc = base64_encode($victro_html);
            $victro_content = $victro_htmlc;
        } else if ($victro_type == "PHP") {
            $victro_content = $victro_php;
        } else if ($victro_type == "sql") {
            $victro_content = $victro_query;
        }
        $victro_textb = accent($victro_textb);

        $victro_actionb = base64_encode($victro_actionb);
        $victro_tb = $victro_conn->prepare("insert into victro_widget values(null, :name, :who, :width, :typ1, :content, :showb, :text, :action1 )");
        $victro_tb->bindParam(":name", $victro_name, PDO::PARAM_STR);
        $victro_tb->bindParam(":who", $victro_who1, PDO::PARAM_STR);
        $victro_tb->bindParam(":width", $victro_width, PDO::PARAM_INT);
        $victro_tb->bindParam(":typ1", $victro_type, PDO::PARAM_STR);
        $victro_tb->bindParam(":content", $victro_content, PDO::PARAM_STR);
        $victro_tb->bindParam(":showb", $victro_showb, PDO::PARAM_INT);
        $victro_tb->bindParam(":text", $victro_textb, PDO::PARAM_STR);
        $victro_tb->bindParam(":action1", $victro_actionb, PDO::PARAM_STR);
        $victro_tb->execute();

        header("location: " . SITE_URL . 'system/widgets');
    }

    public function list_widgets() {
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("select * from victro_widget");
        $victro_tb->execute();
        if ($victro_tb->rowCount() >= 1) {
            $victro_i = 0;
            while ($victro_pl = $victro_tb->fetch(PDO::FETCH_ASSOC)) {
                $victro_id[] = $victro_pl['id'];
                $victro_name[] = $victro_pl['name'];
                $victro_who[] = explode(",", $victro_pl['who']);
                $victro_type[] = $victro_pl['type'];
                $victro_width[] = $victro_pl['width'];
                $victro_content[] = $victro_pl['content'];
                $victro_showb[] = $victro_pl['showbutton'];
                $victro_textb[] = $victro_pl['textbutton'];
                $victro_actionb[] = $victro_pl['actionbutton'];
            }
            $victro_widget['id'] = $victro_id;
            $victro_widget['name'] = $victro_name;
            $victro_widget['who'] = $victro_who;
            $victro_widget['type'] = $victro_type;
            $victro_widget['width'] = $victro_width;
            $victro_widget['action'] = $victro_actionb;
            $victro_widget['text'] = $victro_textb;
            $victro_widget['show'] = $victro_showb;
            $victro_widget['content'] = $victro_content;
            return($victro_widget);
        }
        return(1);
    }

    public function delete_widget($victro_id) {
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("delete from victro_widget where id = :id");
        $victro_tb->bindParam(":id", $victro_id, PDO::PARAM_INT);
        $victro_tb->execute();
        header("location: " . SITE_URL . 'system/widgets');
    }

    public function update_widget($victro_id, $victro_name, $victro_type, $victro_query, $victro_php, $victro_html, $victro_width, $victro_who, $victro_showb, $victro_textb, $victro_actionb) {
        $victro_conn = $this->defaultConnection();
        $victro_who1 = implode(",", $victro_who);
        if ($victro_type == "HTML") {
            $victro_htmlc = base64_encode($victro_html);
            $victro_content = $victro_htmlc;
        } else if ($victro_type == "PHP") {
            $victro_content = $victro_php;
        } else if ($victro_type == "sql") {
            $victro_content = $victro_query;
        }
        $victro_textb = accent($victro_textb);
        $victro_actionb = base64_encode($victro_actionb);
        $victro_tb = $victro_conn->prepare("update victro_widget set name = :name, who = :who, width = :width, type = :typ1, content = :content, showbutton = :showb, textbutton = :text, actionbutton = :action1 where id = :id ");
        $victro_tb->bindParam(":id", $victro_id, PDO::PARAM_INT);
        $victro_tb->bindParam(":name", $victro_name, PDO::PARAM_STR);
        $victro_tb->bindParam(":who", $victro_who1, PDO::PARAM_STR);
        $victro_tb->bindParam(":width", $victro_width, PDO::PARAM_INT);
        $victro_tb->bindParam(":typ1", $victro_type, PDO::PARAM_STR);
        $victro_tb->bindParam(":content", $victro_content, PDO::PARAM_STR);
        $victro_tb->bindParam(":showb", $victro_showb, PDO::PARAM_INT);
        $victro_tb->bindParam(":text", $victro_textb, PDO::PARAM_STR);
        $victro_tb->bindParam(":action1", $victro_actionb, PDO::PARAM_STR);
        $victro_tb->execute();
        header("location: " . SITE_URL . 'system/widgets');
    }

    public function send_file_db(array $victro_values) {
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("insert into victro_file values(null, :name, :type, :accessible, :data, :id, :pass, :folder)");
        $victro_tb->bindParam(":name", $victro_values['name'], PDO::PARAM_STR);
        $victro_tb->bindParam(":type", $victro_values['type'], PDO::PARAM_STR);
        $victro_tb->bindParam(":accessible", $victro_values['accessible'], PDO::PARAM_INT);
        $victro_tb->bindParam(":data", $victro_values['data'], PDO::PARAM_STR);
        $victro_tb->bindParam(":id", $_SESSION['iduser'], PDO::PARAM_STR);
        $victro_tb->bindParam(":pass", $victro_values['pass'], PDO::PARAM_STR);
        $victro_tb->bindParam(":folder", $victro_values['folder'], PDO::PARAM_STR);
        $victro_tb = $victro_tb->execute();
        $victro_valor = $victro_conn->lastInsertId($victro_tb);
        return($victro_valor);
    }

    public function load_file_db(array $victro_values) {
        $victro_conn = $this->defaultConnection();
        if (isset($victro_values['id']) and isset($victro_values['name'])) {
            $victro_where = "id = :id and name = :name";
        } else if (isset($victro_values['id'])) {
            $victro_where = "id = :id";
        } else if (isset($victro_values['name'])) {
            $victro_where = "name = :name";
        } else {
            $victro_where = "1 = 2";
        }
        $victro_tb = $victro_conn->prepare("select name, type, f_accessible, f_password, folder from victro_file where {$victro_where}");
        if (isset($victro_values['id']) and isset($victro_values['name'])) {
            $victro_tb->bindParam(":id", $victro_values['id'], PDO::PARAM_INT);
            $victro_tb->bindParam(":name", $victro_values['name'], PDO::PARAM_STR);
        } else if (isset($victro_values['id'])) {
            $victro_tb->bindParam(":id", $victro_values['id'], PDO::PARAM_INT);
        } else if (isset($victro_values['name'])) {
            $victro_tb->bindParam(":name", $victro_values['name'], PDO::PARAM_STR);
        } else {
            $victro_ret['error'] = "Id and/or Name is required";
        }
        $victro_tb->execute();
        if ($victro_tb->rowCount() >= 1) {
            $victro_pl = $victro_tb->fetch(PDO::FETCH_ASSOC);
            if ($victro_pl['f_accessible'] == 1) {
                $victro_ret['name'] = $victro_pl['name'];
                $victro_ret['type'] = $victro_pl['type'];
                $victro_ret['folder'] = $victro_pl['folder'];
            } else {
                if (!isset($victro_values['pass'])) {
                    $victro_ret['error'] = "Password requires";
                } else {
                    if ($victro_values['pass'] == $victro_pl['f_password']) {
                        $victro_ret['name'] = $victro_pl['name'];
                        $victro_ret['type'] = $victro_pl['type'];
                        $victro_ret['folder'] = $victro_pl['folder'];
                    } else {
                        $victro_ret['error'] = "File protected";
                    }
                }
            }
        } else {
            $victro_ret['error'] = "404";
        }
        return($victro_ret);
    }

    public function home_widgets($victro_typeuser) {
        $victro_conn = $this->defaultConnection();
        $victro_type1 = "%$victro_typeuser%";
        $victro_type2 = "$victro_typeuser%";
        $victro_type3 = "%$victro_typeuser";
        $victro_tb = $victro_conn->prepare("select * from victro_widget where who like :type1 or who like :type2 or who like :type3 order by id desc ");
        $victro_tb->bindParam(":type1", $victro_type1, PDO::PARAM_INT);
        $victro_tb->bindParam(":type2", $victro_type2, PDO::PARAM_INT);
        $victro_tb->bindParam(":type3", $victro_type3, PDO::PARAM_INT);
        $victro_tb->execute();
        if ($victro_tb->rowCount() >= 1) {
            $victro_i = 0;
            while ($victro_pl = $victro_tb->fetch(PDO::FETCH_ASSOC)) {
                $victro_widget[$victro_i]['name'] = $victro_pl['name'];
                $victro_widget[$victro_i]['btext'] = $victro_pl['textbutton'];
                $victro_widget[$victro_i]['actionb'] = $victro_pl['actionbutton'];
                $victro_widget[$victro_i]['type'] = $victro_pl['type'];
                if ($victro_pl['showbutton'] == true) {
                    $victro_widget[$victro_i]['button1'] = str_replace(":text", $victro_pl['textbutton'], $victro_pl['actionbutton']);
                    $victro_action12 = base64_decode($victro_pl['actionbutton']);
                    $victro_text12 = $victro_pl['textbutton'];
                    $victro_widget[$victro_i]['button'] = "<button class='btn btn-danger' $victro_action12 > $victro_text12 </button>";
                } else {
                    $victro_widget[$victro_i]['button'] = '';
                }
                $victro_widget[$victro_i]['width'] = $victro_pl['width'];
                if ($victro_pl['type'] == "sql") {
                    $victro_tb1 = $victro_conn->prepare($victro_pl['content']);
                    $victro_tb1->execute();
                    if ($victro_tb1->rowCount() >= 1) {
                        $victro_valselect = array();
                        $victro_value['count'] = $victro_num_array = $victro_tb1->rowCount();
                        while ($victro_var = $victro_tb1->fetch(PDO::FETCH_ASSOC)) {
                            $victro_valselect[] = $victro_var;
                        }
                        $victro_j = 0;
                        $victro_titulos = array();
                        $victro_savevar = array();
                        while ($victro_j <= $victro_num_array - 1) {
                            foreach ($victro_valselect[$victro_j] as $victro_ind => $victro_val) {
                                if ($victro_j == 0) {
                                    $victro_titulos[] = $victro_ind;
                                }
                                $victro_savevar[$victro_j][$victro_ind] = $victro_val;
                            }
                            $victro_j++;
                        }
                        $victro_widget[$victro_i]['title'] = $victro_titulos;
                        $victro_widget[$victro_i]['content'] = $victro_savevar;
                    } else {
                        $victro_widget = null;
                    }
                } else {
                    $victro_widget[$victro_i]['content'] = $victro_pl['content'];
                }
                $victro_i++;
            }
        } else {
            $victro_widget = null;
        }
        return($victro_widget);
    }

    public function edit_imgprof($victro_url) {
        $victro_conn = $this->defaultConnection();
        $victro_newurl = STORAGE_NAME . $victro_url;
        $victro_tb = $victro_conn->prepare("update victro_users set user_pic = :url where id = :id");
        $victro_tb->bindParam(":url", $victro_newurl, PDO::PARAM_STR);
        $victro_tb->bindParam(":id", $_SESSION['iduser'], PDO::PARAM_INT);
        $victro_tb = $victro_tb->execute();
    }

    public function edit_passprof($victro_old, $victro_pass) {
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("update victro_users set victro_password = :pass where victro_password = :old and id = :id");
        $victro_tb->bindParam(":pass", $victro_pass, PDO::PARAM_STR);
        $victro_tb->bindParam(":old", $victro_old, PDO::PARAM_STR);
        $victro_tb->bindParam(":id", $_SESSION['iduser'], PDO::PARAM_INT);
        $victro_tb->execute();
        return($victro_tb->rowCount());
    }

    public function edit_infoprof($victro_name, $victro_user, $victro_email) {
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("update victro_users set name = :name, username = :user, email = :email where id = :id");
        $victro_tb->bindParam(":name", $victro_name, PDO::PARAM_STR);
        $victro_tb->bindParam(":email", $victro_email, PDO::PARAM_STR);
        $victro_tb->bindParam(":user", $victro_user, PDO::PARAM_STR);
        $victro_tb->bindParam(":id", $_SESSION['iduser'], PDO::PARAM_INT);
        $victro_tb = $victro_tb->execute();
    }

    public function send_file(Array $victro_type, $victro_sendfile, $victro_folder, $victro_size = 2, $victro_access = 1, $victro_pass = null) {
        global $victro_robot;
        global $victro_maker;
        if ($victro_pass == null) {
            $victro_pass = time();
        }
        $victro_pasta[1] = $victro_folder;
        $_UP['pasta'] = 'victro_apps/victro_storage/' . $victro_pasta[1] . '/';
        if (!is_dir($_UP['pasta'])) {
            mkdir($_UP['pasta']);
        }
        $_UP['tamanho'] = 1024 * 1024 * $victro_size; // ex 1024 * 1024 * 2 = 2mb
        $victro_ext = explode('.', $victro_sendfile['name']);
        $victro_extensao = strtolower(end($victro_ext));
        if (array_search($victro_extensao, $victro_type) === false) {
            exit;
        }
        if ($_UP['tamanho'] < $victro_sendfile['size']) {
            return('error');
            exit;
        }
        $victro_numfile = mt_rand(0, 1000);
        $victro_convert_to_victro = file_get_contents($victro_sendfile['tmp_name']);
        $victro_convert_to_victro1 = base64_encode('12345678+?+|+?+' . $victro_convert_to_victro . '+?+|+?+victrohihi');
        $victro_nome_final = md5(time() . $victro_sendfile['name'] . $victro_numfile) . '.victro';
        $victro_fp = fopen($_UP['pasta'] . $victro_nome_final, "a");
        $victro_escreve = fwrite($victro_fp, $victro_convert_to_victro1);
        fclose($victro_fp);
        $victro_value['name'] = $victro_nome_final;
        $victro_value['type'] = $victro_extensao;
        $victro_value['accessible'] = $victro_access;
        $victro_value['data'] = date('Y-m-d');
        $victro_value['pass'] = $victro_pass;
        $victro_value['folder'] = $victro_pasta[1];
        $victro_id = $victro_maker->send_file_db($victro_value);
        $victro_ret['id'] = $victro_id;
        $victro_ret['name'] = $victro_nome_final;
        return($victro_ret);
    }

    public function execute_db_update($victro_query) {
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare($victro_query);
        $victro_tb->execute();
    }

    public function victro_cronjob() {
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("select * from victro_cronjob");
        $victro_tb->execute();
        if ($victro_tb->rowCount() >= 1) {
            while ($victro_pl = $victro_tb->fetch(PDO::FETCH_ASSOC)) {
                $victro_DataFuturo = date("Y-m-d G:i:s"); //"2011-03-02 20:30:15";
                $victro_datacron = explode(" ", $victro_pl['run']);
                if ($victro_pl['last_run'] != 0) {
                    $victro_DataAtual = $victro_pl['last_run'];
                    $victro_date_time = new DateTime($victro_DataAtual);
                    $victro_diff = $victro_date_time->diff(new DateTime($victro_DataFuturo));
                    //echo $victro_diff->format( '%a %y year(s), %m month(s), %d day(s), %H hour(s), %i minute(s) and %s second(s)' );
                    $victro_total_year = $victro_diff->format('%y');
                    $victro_month = $victro_diff->format('%m');
                    $victro_total_month = ($victro_total_year * 12) + $victro_month;
                    $victro_total_day = $victro_diff->format('%a');
                    if ($victro_datacron[0] == 0 and $victro_datacron[1] == 0 and $victro_datacron != 0) {
                        //echo 'executa por '.$victro_total_day.' vezes';
                        session_write_close();
                        $victro_cronday = curl_init();

                        curl_setopt($victro_cronday, CURLOPT_URL, SITE_URL . 'index.php'); //'bot/'.$victro_pl['id_bot'].'/'.$victro_pl['action']);
                        curl_setopt($victro_cronday, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
                        curl_setopt($victro_cronday, CURLOPT_POST, true);
                        curl_setopt($victro_cronday, CURLOPT_POSTFIELDS, "username=XXXXX&password=XXXXX");
                        curl_setopt($victro_cronday, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($victro_cronday, CURLOPT_COOKIESESSION, true);
                        curl_setopt($victro_cronday, CURLOPT_COOKIEJAR, 'cookie-name');  //could be empty, but cause problems on some hosts
                        curl_setopt($victro_cronday, CURLOPT_COOKIEFILE, '/var/www/ip4.x/file/tmp');  //could be empty, but cause problems on some hosts
                        $victro_post = array('post1' => 'postvalue', 'post2' => 'othervalue');
                        curl_setopt($victro_cronday, CURLOPT_POSTFIELDS, $victro_post);
                        $victro_returncron = curl_exec($victro_cronday);
                        curl_close($victro_cronday);
                        echo $victro_returncron;
                    } else if ($victro_datacron[0] == 0 and $victro_datacron[1] != 0 and $victro_datacron == 0) {
                        echo 'executa por ' . $victro_total_month . ' vezes';
                    } else if ($victro_datacron[0] != 0 and $victro_datacron[1] == 0 and $victro_datacron == 0) {
                        echo 'executa por ' . $victro_total_year . ' vezes';
                    } else {
                        return(FALSE);
                    }
                } else {
                    return(FALSE);
                }
            }
        } else {
            return(FALSE);
        }
    }

    public function backup_tables() {
        $victro_tables = array();
        $victro_conn = $this->defaultConnection();
        $victro_DBH = $victro_conn;
        $victro_DBH->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_NATURAL);

        //Script Variables
        $victro_compression = false;
        $victro_BACKUP_PATH = "victro_system/victro_backups";
        $victro_nowtimename = date('d-m-Y-H-i-s') . '-by-' . $_SESSION['iduser'];


        //create/open files
        if ($victro_compression) {
            $victro_zp = gzopen($victro_BACKUP_PATH . $victro_nowtimename . '.sql.gz', "a9");
        } else {
            $victro_handle = fopen($victro_BACKUP_PATH . '/' . $victro_nowtimename . '.sql', 'a+');
        }


        //array of all database field types which just take numbers
        $victro_numtypes = array('tinyint', 'smallint', 'mediumint', 'int', 'bigint', 'float', 'double', 'decimal', 'real');

        //get all of the tables
        if (empty($victro_tables)) {
            $victro_pstm1 = $victro_DBH->query('SHOW TABLES');
            while ($victro_row = $victro_pstm1->fetch(PDO::FETCH_NUM)) {
                $victro_tables[] = $victro_row[0];
            }
        } else {
            $victro_tables = is_array($victro_tables) ? $victro_tables : explode(',', $victro_tables);
        }

        //cycle through the table(s)

        foreach ($victro_tables as $victro_table) {
            $victro_result = $victro_DBH->query("SELECT * FROM $victro_table");
            $victro_num_fields = $victro_result->columnCount();
            $victro_num_rows = $victro_result->rowCount();

            $victro_return = "";
            //uncomment below if you want 'DROP TABLE IF EXISTS' displayed
            //$victro_return.= 'DROP TABLE IF EXISTS `'.$victro_table.'`;';
            //table structure
            $victro_pstm2 = $victro_DBH->query("SHOW CREATE TABLE $victro_table");
            $victro_row2 = $victro_pstm2->fetch(PDO::FETCH_NUM);
            $victro_ifnotexists = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $victro_row2[1]);
            $victro_return .= "\n\n" . $victro_ifnotexists . ";\n\n";


            if ($victro_compression) {
                gzwrite($victro_zp, $victro_return);
            } else {
                fwrite($victro_handle, $victro_return);
            }
            $victro_return = "";

            //insert values
            if ($victro_num_rows) {
                $victro_return = 'INSERT INTO `' . "$victro_table" . "` (";
                $victro_pstm3 = $victro_DBH->query("SHOW COLUMNS FROM $victro_table");
                $victro_count = 0;
                $victro_type = array();

                while ($victro_rows = $victro_pstm3->fetch(PDO::FETCH_NUM)) {

                    if (stripos($victro_rows[1], '(')) {
                        $victro_type[$victro_table][] = stristr($victro_rows[1], '(', true);
                    } else
                        $victro_type[$victro_table][] = $victro_rows[1];

                    $victro_return .= "`" . $victro_rows[0] . "`";
                    $victro_count++;
                    if ($victro_count < ($victro_pstm3->rowCount())) {
                        $victro_return .= ", ";
                    }
                }

                $victro_return .= ")" . ' VALUES';

                if ($victro_compression) {
                    gzwrite($victro_zp, $victro_return);
                } else {
                    fwrite($victro_handle, $victro_return);
                }
                $victro_return = "";
            }
            $victro_count = 0;
            while ($victro_row = $victro_result->fetch(PDO::FETCH_NUM)) {
                $victro_return = "\n\t(";

                for ($victro_j = 0; $victro_j < $victro_num_fields; $victro_j++) {

                    //$victro_row[$victro_j] = preg_replace("\n","\\n",$victro_row[$victro_j]);


                    if (isset($victro_row[$victro_j])) {

                        //if number, take away "". else leave as string
                        if ((in_array($victro_type[$victro_table][$victro_j], $victro_numtypes)) && (!empty($victro_row[$victro_j])))
                            $victro_return .= $victro_row[$victro_j];
                        else
                            $victro_return .= $victro_DBH->quote($victro_row[$victro_j]);
                    } else {
                        $victro_return .= 'NULL';
                    }
                    if ($victro_j < ($victro_num_fields - 1)) {
                        $victro_return .= ',';
                    }
                }
                $victro_count++;
                if ($victro_count < ($victro_result->rowCount())) {
                    $victro_return .= "),";
                } else {
                    $victro_return .= ");";
                }
                if ($victro_compression) {
                    gzwrite($victro_zp, $victro_return);
                } else {
                    fwrite($victro_handle, $victro_return);
                }
                $victro_return = "";
            }
            $victro_return = "\n\n-- ------------------------------------------------ \n\n";
            if ($victro_compression) {
                gzwrite($victro_zp, $victro_return);
            } else {
                fwrite($victro_handle, $victro_return);
            }
            $victro_return = "";
        }



        $victro_error1 = $victro_pstm2->errorInfo();
        $victro_error2 = $victro_pstm3->errorInfo();
        $victro_error3 = $victro_result->errorInfo();
        echo $victro_error1[2];
        echo $victro_error2[2];
        echo $victro_error3[2];

        if ($victro_compression) {
            gzclose($victro_zp);
        } else {
            fclose($victro_handle);
        }
    }

}

class victroindex extends victro_DBconnect {

    public function loginindex($victro_user, $victro_pass, $victro_redirect = true) {
        $victro_conn = $this->defaultConnection();
        $victro_passhash = md5($victro_pass);
        $victro_tb = $victro_conn->prepare("select id, username, type, name, email, failures, user_pic from victro_user  where username = :user and victro_password = :pass");
        $victro_tb->bindParam(":user", $victro_user, PDO::PARAM_STR);
        $victro_tb->bindParam(":pass", $victro_passhash, PDO::PARAM_STR);
        $victro_tb->execute();
        $victro_l = $victro_tb->fetch(PDO::FETCH_ASSOC);
        if ($victro_tb->rowCount() == 1 and $victro_l['failures'] <= 3) {
            if (!isset($_SESSION)) {
                session_name(SESSION_NAME);
                session_start();
            }
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
            if ($victro_redirect == true) {
                header('location:' . SITE_URL . 'sys/home');
            }
        } else if ($victro_l['failures'] > 3) {
            if (isset($_SESSION['error_victro'])) {
                $victro_errorlogin = $_SESSION['error_victro'];
            } else {
                $victro_errorlogin = 0;
            }
            $_SESSION['error_victro'] = $victro_errorlogin + 1;

            header('location: ?error=block');
        } else {
            $victro_tb2 = $victro_conn->prepare("select id, username from victro_user where username = :user");
            $victro_tb2->bindParam(":user", $victro_user, PDO::PARAM_STR);
            $victro_tb2->execute();
            if ($victro_tb2->rowCount() >= 1) {
                $victro_tb3 = $victro_conn->prepare("update victro_user set failures = (failures + 1) where username = :user");
                $victro_tb3->bindParam(":user", $victro_user, PDO::PARAM_STR);
                $victro_tb3->execute();
            }
            $_SESSION['victro_error_login_' . SESSION_NAME] = "error";
            header('location: ' . SITE_URL . 'system/captcha');
        }
    }

    public function unset_vars() {
        $vars = array_keys(get_defined_vars());
        for ($i = 0; $i < sizeOf($vars); $i++) {
            unset($$vars[$i]);
        }
        unset($vars, $i);
    }

}

?>
