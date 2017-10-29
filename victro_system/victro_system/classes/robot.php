<?php

/*
 * The MIT License
 *
 * Copyright 2017 Jean Victor Mendes dos Santos.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
require_once(PATH_SYSTEM.PATH_SETTINGS."translation.php");
class robot {
    //DATABASE FUNCTIONS
    private $array_table = array();
    private $last_table;
    private $last_table_key = 0;
    private $last_column;
    private $last_column_key = 0;
    private $last_foreignkey_key = 0;
    private $last_menu_key = 0;
    private $name;
    private $version;
    private $route = array();
    private $author;
    private $description;
    private $icon;
    public $connect;
    public $menu;
    public $victro_botfolder;

    public function table($victro_name) {
        if (substr($victro_name, 0, 4) !== 'bot_'){
            $victro_name = "bot_".$victro_name;
        }
        $this->last_table = $victro_name;
        $this->array_table[$this->last_table_key]["name"] = $this->last_table;
        $this->last_table_key++;
        return($this);
    }

    public function engine($victro_engine) {
        if ($this->last_table_key > 0) {
            $this->array_table[($this->last_table_key - 1)]["engine"] = $victro_engine;
            return($this);
        }
    }

    public function if_table($victro_if) {
        if ($this->last_table_key > 0) {
            $this->array_table[($this->last_table_key - 1)]["if_table"] = $victro_if;
            return($this);
        }
    }

    public function column($victro_column) {
        if ($this->last_table_key > 0) {
            $this->last_column = $victro_column;
            $this->array_table[($this->last_table_key - 1)]["column"][$this->last_column_key]["name"] = $victro_column;
            $this->last_column_key++;
            return($this);
        }
    }

    public function type($victro_type) {
        if ($this->last_column_key > 0) {
            $this->array_table[($this->last_table_key - 1)]["column"][($this->last_column_key - 1)]["type"] = $victro_type;
            return($this);
        }
    }

    public function value($victro_value) {
        if ($this->last_column_key > 0) {
            $this->array_table[($this->last_table_key - 1)]["column"][($this->last_column_key - 1)]["value"] = $victro_value;
            return($this);
        }
    }

    public function autoincrement($victro_ai) {
        if ($this->last_column_key > 0) {
            $this->array_table[($this->last_table_key - 1)]["column"][($this->last_column_key - 1)]["autoincrement"] = $victro_ai;
            return($this);
        }
    }

    public function index($victro_index) {
        if ($this->last_column_key > 0) {
            $this->array_table[($this->last_table_key - 1)]["column"][($this->last_column_key - 1)]["index"] = $victro_index;
            return($this);
        }
    }

    public function foreignkey($victro_foreignkey) {
        if ($this->last_table_key > 0) {
            $this->array_table[($this->last_table_key - 1)]["foreignkey"][$this->last_foreignkey_key]['foreignkey'] = $victro_foreignkey;
            $this->last_foreignkey_key++;
            return($this);
        }
    }

    public function from($victro_from) {
        if (substr($victro_from, 0, 4) !== 'bot_'){
            $victro_from = "bot_".$victro_from;
        }
        if ($this->last_foreignkey_key > 0) {
            $this->array_table[($this->last_table_key - 1)]["foreignkey"][($this->last_foreignkey_key - 1)]['from'] = $victro_from;
            return($this);
        }
    }

    public function field($victro_field) {
        if ($this->last_foreignkey_key > 0) {
            $this->array_table[($this->last_table_key - 1)]["foreignkey"][($this->last_foreignkey_key - 1)]['field'] = $victro_field;
            return($this);
        }
    }

    // END DATABASE FUNCTIONS
    //BASIC INFO
    public function name($victro_name) {
        $this->name = $victro_name;
        return($this);
    }
    public function version($victro_version) {
        $this->version = $victro_version;
    }

    public function update($victro_update) {
        return($this);
    }

    public function icon($victro_icon) {
        $this->icon = $victro_icon;
        return($this);
    }

    public function author($victro_author) {
        $this->author = $victro_author;
        return($this);
    }

    public function description($victro_description) {
        $this->description = $victro_description;
        return($this);
    }

    public function try_route($victro_try_route) {
        $this->route[] = $victro_try_route;
        return($this);
    }

    // END BASIC INFO
    // MENU INFO
    public function menu($type, $value, $icon = "fa fa-microchip") {
        $this->menu[$this->last_menu_key]['menu'] = array($value, $type, $icon);
        $this->last_menu_key++;
        return($this);
    }

    public function submenu($value, $type, $icon = "fa fa-circle") {
        $this->menu[$this->last_menu_key][$value] = array($type, $icon);
        return($this);
    }

    // END MENU INFO
    public function install_db() {
        $victro_tables = $this->array_table;
        unset($_SESSION['query_bot']);
        $_SESSION['query_bot']['delete'] = array();
        $_SESSION['query_bot']['alter'] = array();
        $_SESSION['query_bot']['create'] = array();
        $_SESSION['query_bot']['add'] = array();
        $_SESSION['query_bot']['index'] = array();
        foreach ($victro_tables as $victro_table) {
            if (isset($victro_table['name']) and $victro_table['name'] != null) {
                $victro_table['name'] = 'bot_' . str_replace("bot_", "", $victro_table['name']);
                $victro_conn = $this->connect;
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

                    $victro_engine = "MyISAM";
                    if(isset($victro_table['engine'])){
                        $victro_engine = $victro_table['engine'];
                    }
                    $victro_if = "IF NOT EXISTS";
                    if(isset($victro_table['if_table'])){
                        if($victro_table['if_table'] != ""){
                            $victro_if = "IF ".$victro_table['if_table'];
                        } else {
                            $victro_if = "";
                        }
                    }

                    $victro_sqlcreatequery = "CREATE TABLE {$victro_if} {$victro_table['name']}({$victro_sqlcolumns}) ENGINE={$victro_engine};";
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
            if(isset($victro_table['foreignkey'])){
                $victro_sqlforeignkey = array();
                foreach($victro_table['foreignkey'] as $victro_foreignkey){
                    $victro_sqlforeignkey = "ALTER TABLE `{$victro_table['name']}` ADD CONSTRAINT `fk_{$victro_table['name']}_{$victro_foreignkey['foreignkey']}` FOREIGN KEY (`{$victro_foreignkey['foreignkey']}`) REFERENCES `{$victro_foreignkey['from']}`(`{$victro_foreignkey['field']}`) ON DELETE RESTRICT ON UPDATE RESTRICT;";
                    //$victro_sqlforeignkey = "ALTER TABLE {$victro_table['name']} ADD CONSTRAINT fk_{$victro_table['name']}_{$victro_foreignkey['foreignkey']} FOREIGN KEY({$victro_foreignkey['foreignkey']}) REFERENCES {$victro_foreignkey['from']} ({$victro_foreignkey['field']});";
                    $_SESSION['query_bot']['foreignkey'][] = $victro_sqlforeignkey;
                    $victro_sqlforeignkey = null;
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
    public function robot_setquery() {
        $victro_conn = $this->connect;
        $_SESSION['debug_create_tables'] = $_SESSION['query_bot'];
        if (isset($_SESSION['query_bot']['create'])) {
            foreach ($_SESSION['query_bot']['create'] as $victro_create) {
                $victro_tb = $victro_conn->prepare($victro_create);
                try {
                    $victro_tb->execute();
                } catch (PDOException $victro_e) {
                    $this->create_dbLog("INTERNAL SQL ERROR", "CREATE ROBOT BD - ".$victro_e." ({$victro_create})");
                }
            }
        }
        if (isset($_SESSION['query_bot']['alter'])) {
            foreach ($_SESSION['query_bot']['alter'] as $victro_alter) {
                $victro_tb = $victro_conn->prepare($victro_alter);
                try {
                    $victro_tb->execute();
                } catch (PDOException $victro_e) {
                    $this->create_dbLog("INTERNAL SQL ERROR", "ALTER ROBOT BD - ".$victro_e." ({$victro_alter})");
                }
            }
        }
        if (isset($_SESSION['query_bot']['delete'])) {
            foreach ($_SESSION['query_bot']['delete'] as $victro_delete) {
                $victro_tb = $victro_conn->prepare($victro_delete);
                try {
                    $victro_tb->execute();
                } catch (PDOException $victro_e) {
                    $this->create_dbLog("INTERNAL SQL ERROR", "DELETE ROBOT BD - ".$victro_e." ({$victro_delete})");
                }
            }
        }
        if (isset($_SESSION['query_bot']['add'])) {
            foreach ($_SESSION['query_bot']['add'] as $victro_add) {
                $victro_tb = $victro_conn->prepare($victro_add);
                try {
                    $victro_tb->execute();
                } catch (PDOException $victro_e) {
                    $this->create_dbLog("INTERNAL SQL ERROR", "ADD ROBOT BD - ".$victro_e." ({$victro_add})");
                }
            }
        }
        if (isset($_SESSION['query_bot']['index'])) {
            foreach ($_SESSION['query_bot']['index'] as $victro_indexq) {
                $victro_tb = $victro_conn->prepare($victro_indexq);
                try {
                    $victro_tb->execute();
                } catch (PDOException $victro_e) {
                    $this->create_dbLog("INTERNAL SQL ERROR", "INDEX ROBOT BD - ".$victro_e." ({$victro_indexq})");
                }
            }
        }
        if (isset($_SESSION['query_bot']['foreignkey'])) {
            foreach ($_SESSION['query_bot']['foreignkey'] as $victro_foreignkeyq) {
                $victro_tb = $victro_conn->prepare($victro_foreignkeyq);
                try {
                    $victro_tb->execute();
                } catch (PDOException $victro_e) {
                    $this->create_dbLog("INTERNAL SQL ERROR", "FOREIGN KEY ROBOT BD - ".$victro_e." ({$victro_foreignkeyq})");
                }
            }
        }
        unset($_SESSION['query_bot']);
    }
    private function create_dbLog($victro_type, $victro_string){
        $victro_method = debug_backtrace()[1]['function'];
        $victro_paginalink = debug_backtrace()[1]['file'];
        $victro_pagina = explode("\\", $victro_paginalink);
        $victro_log = str_replace("[METHOD]", $victro_method, $victro_string);
        $victro_log = str_replace("[PAGE]", $victro_pagina[count($victro_pagina) - 1], $victro_log);
        $victro_datalog = date('Y-m-d h:i:s');
        $victro_tb = $this->connect->prepare('insert into victro_log VALUES(null, :log, :date, :user, "'.$victro_type.'"); ');
        $victro_tb->bindParam(":log", $victro_log, PDO::PARAM_STR);
        $victro_tb->bindParam(":date", $victro_datalog, PDO::PARAM_STR);
        $victro_tb->bindParam(":user", $_SESSION['iduser'], PDO::PARAM_INT);
        $victro_tb = $victro_tb->execute();
        $_SESSION['victro_log_session'][] = array('ID' => $this->connect->lastInsertId($victro_tb),'USER' => $_SESSION['iduser'], 'TYPE' => $victro_type, 'DATE' => $victro_datalog, 'MESSAGE' => $victro_log);
    }
    public function robot_in_db() {
        $victro_id_install = md5($this->victro_botfolder);
        $victro_conn = $this->connect;
        $victro_tb = $victro_conn->prepare("Select id, active from victro_robot where id_install = :id and active = 1");
        $victro_tb->bindParam(":id", $victro_id_install, PDO::PARAM_STR);
        $victro_tb->execute();
        if($victro_tb->rowCount() >= 1) {
            $victro_row = $victro_tb->fetch(PDO::FETCH_ASSOC);
            $victro_bt = $victro_row['id'];
        } else {
            $victro_bt = 0;
        }
        return($victro_bt);
    }
    public function install(){
        $victro_conn = $this->connect;
        $victro_check = $this->robot_in_db();
        $victro_return = victro_translate($this->name." installed successfully", 1, true);
        if($victro_check == 0){
            $victro_install = md5($this->victro_botfolder);
            $victro_tb = $victro_conn->prepare("insert into victro_robot(`name`, `local_url`, `author`, `active`, `version`, `id_install`, `icon`) values(:name, :url, :author, 1, :version, :install, :icon)");
            $victro_tb->bindParam(":name", $this->name, PDO::PARAM_STR);
            $victro_tb->bindParam(":url", $this->victro_botfolder, PDO::PARAM_STR);
            $victro_tb->bindParam(":author", $this->author, PDO::PARAM_STR);
            $victro_tb->bindParam(":version", $this->version, PDO::PARAM_STR);
            $victro_tb->bindParam(":install", $victro_install, PDO::PARAM_STR);
            $victro_tb->bindParam(":icon", $this->icon, PDO::PARAM_STR);
            $victro_tb->execute();
            $this->install_db();
            $this->robot_setquery();
            foreach($this->route as $victro_route){
                $victro_tb2 = $victro_conn->prepare("select id from victro_robot where route = :route");
                $victro_tb2->bindParam(":route", $victro_route, PDO::PARAM_STR);
                $victro_tb2->execute();
                if($victro_tb2->rowCount() == 0) {
                    $victro_tb3 = $victro_conn->prepare("update victro_robot set route = :route where id_install = :id");
                    $victro_tb3->bindParam(":route", $victro_route, PDO::PARAM_STR);
                    $victro_tb3->bindParam(":id", $victro_install, PDO::PARAM_STR);
                    $victro_tb3->execute();
                    break;
                }
            }
        } else {
            $victro_return = victro_translate($this->name." is already installed", 1, true);
        }
        $this->iu_menu();
        return($victro_return);
    }
    public function iu_menu(){
        $victro_conn = $this->connect;
        $victro_id = $this->get_id();
        $victro_tb1 = $victro_conn->prepare("update victro_menu set active = '0' where id_robot = :id");
        $victro_tb1->bindParam(":id", $victro_id, PDO::PARAM_STR);
        $victro_tb1->execute();
        $victro_submenu = 0;
        if($victro_id > 0){
            foreach($this->menu as $victro_key1 => $victro_menu1){

                foreach($victro_menu1 as $victro_key => $victro_menu){
                    if($victro_key == 'menu'){
                        $victro_tb4 = $victro_conn->prepare("select id from victro_menu where name = :name and id_robot = :id_robot");
                        $victro_tb4->bindParam(":name", $victro_menu[1], PDO::PARAM_STR);
                        $victro_tb4->bindParam(":id_robot", $victro_id, PDO::PARAM_INT);
                        $victro_tb4->execute();
                        if($victro_tb4->rowCount() == 0){
                            $victro_tb2 = $victro_conn->prepare("insert into victro_menu (name, submenu, id_robot, active, who_see, icon) values (:name, 0, :id_robot, 1, :permission, :icon)");
                            $victro_tb2->bindParam(":name", $victro_menu[1], PDO::PARAM_STR);
                            $victro_tb2->bindParam(":icon", $victro_menu[2], PDO::PARAM_STR);
                            $victro_tb2->bindParam(":id_robot", $victro_id, PDO::PARAM_INT);
                            $victro_tb2->bindParam(":permission", $victro_menu[0], PDO::PARAM_STR);
                            $victro_tb2 = $victro_tb2->execute();
                            $victro_submenu = $victro_conn->lastInsertId($victro_tb2);
                        } else {
                            $victro_row = $victro_tb4->fetch(PDO::FETCH_ASSOC);
                            $victro_botid = $victro_row['id'];
                            $victro_tb5 = $victro_conn->prepare("update victro_menu set name = :name, active = 1, who_see = :permission, icon = :icon where id = :id");
                            $victro_tb5->bindParam(":name", $victro_menu[1], PDO::PARAM_STR);
                            $victro_tb5->bindParam(":icon", $victro_menu[2], PDO::PARAM_STR);
                            $victro_tb5->bindParam(":id", $victro_botid, PDO::PARAM_INT);
                            $victro_tb5->bindParam(":permission", $victro_menu[0], PDO::PARAM_STR);
                            $victro_tb5 = $victro_tb5->execute();
                            $victro_submenu = $victro_botid;
                        }
                    } else if($victro_submenu > 0){
                        $victro_tb4 = $victro_conn->prepare("select id from victro_menu where name = :name and id_robot = :id_robot and submenu = :submenu");
                        $victro_tb4->bindParam(":name", $victro_key, PDO::PARAM_STR);
                        $victro_tb4->bindParam(":id_robot", $victro_id, PDO::PARAM_INT);
                        $victro_tb4->bindParam(":submenu", $victro_submenu, PDO::PARAM_INT);
                        $victro_tb4->execute();
                        if($victro_tb4->rowCount() == 0){
                            $victro_tb2 = $victro_conn->prepare("insert into victro_menu (name, submenu, id_robot, active, who_see, icon) values (:name, :submenu, :id_robot, 1, :permission, :icon)");
                            $victro_tb2->bindParam(":name", $victro_key, PDO::PARAM_STR);
                            $victro_tb2->bindParam(":icon", $victro_menu[1], PDO::PARAM_STR);
                            $victro_tb2->bindParam(":id_robot", $victro_id, PDO::PARAM_INT);
                            $victro_tb2->bindParam(":permission", $victro_menu[0], PDO::PARAM_STR);
                            $victro_tb2->bindParam(":submenu", $victro_submenu, PDO::PARAM_INT);
                            $victro_tb2 = $victro_tb2->execute();
                        } else {
                            $victro_row = $victro_tb4->fetch(PDO::FETCH_ASSOC);
                            $victro_botid = $victro_row['id'];
                            $victro_tb5 = $victro_conn->prepare("update victro_menu set name = :name, active = 1, who_see = :permission, icon = :icon where id = :id");
                            $victro_tb5->bindParam(":name", $victro_key, PDO::PARAM_STR);
                            $victro_tb5->bindParam(":icon", $victro_menu[1], PDO::PARAM_STR);
                            $victro_tb5->bindParam(":id", $victro_botid, PDO::PARAM_INT);
                            $victro_tb5->bindParam(":permission", $victro_menu[0], PDO::PARAM_STR);
                            $victro_tb5 = $victro_tb5->execute();
                        }
                    }
                }
            }
        }
    }
    public function get_id(){
        $victro_conn = $this->connect;
        $victro_install = md5($this->victro_botfolder);
        $victro_tb2 = $victro_conn->prepare("select id from victro_robot where id_install = :id");
        $victro_tb2->bindParam(":id", $victro_install, PDO::PARAM_STR);
        $victro_tb2->execute();
        $victro_return = 0;
        if($victro_tb2->rowCount() > 0) {
            $victro_row = $victro_tb2->fetch(PDO::FETCH_ASSOC);
            $victro_return = $victro_row['id'];
        }
        return($victro_return);
    }
    public function update_bot(){
        $victro_conn = $this->connect;
        $victro_check = $this->robot_in_db();
        $victro_return = victro_translate($this->name." is not installed", 1, true);
        if($victro_check > 0){
            $victro_install = md5($this->victro_botfolder);
            $victro_tb = $victro_conn->prepare("update victro_robot set name = :name, local_url = :url, author = :author, version = :version, id_install = :install, icon = :icon where id = :id");
            $victro_tb->bindParam(":name", $this->name, PDO::PARAM_STR);
            $victro_tb->bindParam(":url", $this->victro_botfolder, PDO::PARAM_STR);
            $victro_tb->bindParam(":author", $this->author, PDO::PARAM_STR);
            $victro_tb->bindParam(":version", $this->version, PDO::PARAM_STR);
            $victro_tb->bindParam(":install", $victro_install, PDO::PARAM_STR);
            $victro_tb->bindParam(":icon", $this->icon, PDO::PARAM_STR);
            $victro_tb->bindParam(":id", $victro_check, PDO::PARAM_STR);
            $victro_tb->execute();
            $this->install_db();
            $this->robot_setquery();
            $victro_tb1 = $victro_conn->prepare("update victro_robot set route = '' where id = :id");
            $victro_tb1->bindParam(":id", $victro_check, PDO::PARAM_STR);
            $victro_tb1->execute();
            $victro_return = victro_translate($this->name." updated successfully", 1, true);
            foreach($this->route as $victro_route){
                $victro_tb2 = $victro_conn->prepare("select id from victro_robot where route = :route");
                $victro_tb2->bindParam(":route", $victro_route, PDO::PARAM_STR);
                $victro_tb2->execute();
                if($victro_tb2->rowCount() == 0) {
                    $victro_tb3 = $victro_conn->prepare("update victro_robot set route = :route where id = :id");
                    $victro_tb3->bindParam(":route", $victro_route, PDO::PARAM_STR);
                    $victro_tb3->bindParam(":id", $victro_check, PDO::PARAM_STR);
                    $victro_tb3->execute();
                    break;
                }
            }
        }
        $this->iu_menu();
        return($victro_return);
    }

}
?>
