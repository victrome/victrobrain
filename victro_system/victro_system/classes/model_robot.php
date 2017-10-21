<?php
/**
 * ROBOT MODEL Class.
 * Class required class to access function`s model
 * @author Jean Victor
 */
class model_robot extends victro_DBconnect {
    private $victro_table_db = array();
    private $victro_select_db = array();
    private $victro_update_db = "";
    private $victro_delete_db = "";
    private $victro_order_db = "";
    private $victro_group_db = "";
    private $victro_limit_db = "";
    private $victro_having_db = "";
    private $victro_debug_db = "";
    private $victro_where_db = array();
    private $victro_returnsDb;
    private $victro_last_id;
    private $victro_joins = array();
    /**
     * <b>Connection</b> Should never be used
     * @return defaultConnection();
     */
    private function victro_conn(){
        return($this->defaultConnection());
    }
    /**
     * Select fields from database<BR>
     * Exemple: <i>$this->select("ID");</i>
     * @param String $victro_select_q field name
     */
    protected function select($victro_select_q) {
        $this->victro_select_db[] = $victro_select_q;
    }
    /**
     * set table to query <BR>
     * Exemple: <i>$this->from("Table");</i>
     * @param String $victro_table_q table/view name
     */
    protected function from($victro_table_q) {
        if (substr($victro_table_q, 0, 4) !== 'bot_' && substr($victro_table_q, 0, 7) !== 'victro_'){
            $victro_table_q = "bot_".$victro_table_q;
        }
        $this->victro_table_db[] = $victro_table_q;
    }
    /**
     * set a table join (left join)
     * @param  String $victro_table_q table name
     * @param  String $victro_on      ON query
     */
    protected function left_join($victro_table_q, $victro_on) {
        if (substr($victro_table_q, 0, 4) !== 'bot_' && substr($victro_table_q, 0, 7) !== 'victro_'){
            $victro_table_q = "bot_".$victro_table_q;
        }
        $this->victro_joins[] = "LEFT JOIN ".$victro_table_q." ON ".$victro_on;
    }
    /**
     * set a table join (right join)
     * @param  String $victro_table_q table name
     * @param  String $victro_on      ON query
     */
    protected function right_join($victro_table_q, $victro_on) {
        if (substr($victro_table_q, 0, 4) !== 'bot_' && substr($victro_table_q, 0, 7) !== 'victro_'){
            $victro_table_q = "bot_".$victro_table_q;
        }
        $this->victro_joins[] = "RIGHT JOIN ".$victro_table_q." ON ".$victro_on;
    }
    /**
     * set a table join (inner join)
     * @param  String $victro_table_q table name
     * @param  String $victro_on      ON query
     */
    protected function inner_join($victro_table_q, $victro_on) {
        if (substr($victro_table_q, 0, 4) !== 'bot_' && substr($victro_table_q, 0, 7) !== 'victro_'){
            $victro_table_q = "bot_".$victro_table_q;
        }
        $this->victro_joins[] = "RIGHT JOIN ".$victro_table_q." ON ".$victro_on;
    }
    /**
     * set where clause to query<BR>
     * When you use more than once it takes 'and' to join where clauses<BR>
     * Exemple: <i>$this->where("ID > 1");</i>
     * @param String $victro_where_q where clause
     */
    protected function where($victro_where_q, $victro_where_w = "") {
        if(count($this->victro_where_db) == 0){
            $victro_prefix = "";
        } else {
            $victro_prefix = "and ";
        }
        if(is_string($victro_where_w) && $victro_where_w != ""){
            $victro_where_w = " '".$victro_where_w."'";
        }
        $this->victro_where_db[] = $victro_prefix.$victro_where_q.$victro_where_w;
    }
    /**
     * set where clause to query<BR>
     * When you use more than once it takes 'or' to join where clauses<BR>
     * Exemple: <i>$this->where_or("ID > 1");</i>
     * @param String $victro_where_q where clause
     */
    protected function where_or($victro_where_q, $victro_where_w = "") {
        if(count($this->victro_where_db) == 0){
            $victro_prefix = "";
        } else {
            $victro_prefix = "or ";
        }
        if(is_string($victro_where_w) && $victro_where_w != ""){
            $victro_where_w = " '".$victro_where_w."'";
        }
        $this->victro_where_db[] = $victro_prefix.$victro_where_q.$victro_where_w;
    }
    /**
     * set order by clause to query<BR>
     * Exemple: <i>$this->orderby("ID asc");</i>
     * @param String $victro_order_q order clause
     */
    protected function orderby($victro_order_q) {
        $this->victro_order_db = $victro_order_q;
    }
    /**
     * set limit clause to query<BR>
     * Exemple: <i>$this->limit("10");</i>
     * @param String $victro_limit_q limit clause
     */
    protected function limit($victro_limit_q) {
        $this->victro_limit_db = $victro_limit_q;
    }
    /**
     * set group by clause to query<BR>
     * Exemple: <i>$this->groupby("ID");</i>
     * @param String $victro_groupby_q group by clause
     */
    protected function groupby($victro_groupby_q) {
        $this->victro_group_db = $victro_groupby_q;
    }
    /**
     * set having clause to query<BR>
     * Exemple: <i>$this->having("ID > 10");</i>
     * @before groupby()
     * @param String $victro_having_q having clause
     */
    protected function having($victro_having_q) {
        $this->victro_having_db = $victro_having_q;
    }
    private function set_debug_query($victro_debug) {
        $this->victro_debug_db = $victro_debug;
    }
    private function get_select() {    
        $victro_select = $this->victro_select_db; 
        return($victro_select);
    }
    private function get_from() {
        $victro_table = $this->victro_table_db; 
        return($victro_table);
    }    
    private function get_where() {
        $victro_where = $this->victro_where_db; 
        return($victro_where);
    }
    private function get_orderby() {
        $victro_orderby = $this->victro_order_db;
        //$this->victro_order_db = null;
        return($victro_orderby);
    }
    /**
     * get result count from query<BR>
     * Exemple: <i>$bot_queryNum = $this->get_count();</i>
     * @return Integer number of query result
     */
    public function get_count(){ 
        if(is_object($this->victro_returnsDb)){
            $victro_return = $this->victro_returnsDb->rowCount();
            return($victro_return);
        }
        return(false);
    }
    /**
     * get result as object<BR>
     * Exemple: <i>$bot_queryNum = $this->get_fetch();</i>
     * @return Array->Object array as rows, object as row
     */
    public function get_fetch(){
        if(is_object($this->victro_returnsDb)){
            $victro_return = $this->victro_returnsDb->fetchAll(PDO::FETCH_CLASS);
            return($victro_return);
        }
        return(false);
    }
    /**
     * get result as array<BR>
     * Exemple: <i>$bot_queryNum = $this->get_fetch();</i>
     * @return Array->Object array as rows, array as row
     */
    public function get_fetch_array(){
        if(is_object($this->victro_returnsDb)){
            $victro_return = $this->victro_returnsDb->fetchAll(PDO::FETCH_ASSOC);
            return($victro_return);
        }
        return(false);
    }
    /**
     * get the last result as object<BR>
     * Exemple: <i>$bot_queryNum = $this->get_row();</i>
     * @return Object result (only 1 result)
     */
    public function get_row(){
        if(is_object($this->victro_returnsDb)){
            $victro_return = $this->victro_returnsDb->fetch(PDO::FETCH_OBJ);
            return($victro_return);
        }
        return(false);
    }
    /**
     * get the last result as array<BR>
     * Exemple: <i>$bot_queryNum = $this->get_row_array();</i>
     * @return Array result (only 1 result)
     */
    public function get_row_array(){
        if(is_object($this->victro_returnsDb)){
            $victro_return = $this->victro_returnsDb->fetch(PDO::FETCH_ASSOC);
            return($victro_return);
        }
        return(false);
    }
    /**
     * get the last ID inserted<BR>
     * Exemple: <i>$bot_query = $this->get_last_id();</i>
     * @return Integer last ID inserted
     */
    public function get_last_id(){
        return($this->victro_last_id);
    }
    /**
     * get last query sent to database<BR>
     * Exemple: <i>$bot_query = $this->debug_query();</i>
     * @return String last query
     */
    public function debug_query() {
        return $this->victro_debug_db;
    }
    /**
     * Sent a query to database<BR>
     * Exemple: <i>$this->db_query("select id, name from bot_names");</i>
     * @param String $victro_query SQL Query
     * @param Boolean $victro_successLog insert a log if the query has success (defined as false)
     */
    protected function db_query($victro_query, $victro_successLog = false){
        $victro_words = array("victro_password");
        $victro_check = false;
        $victro_check = $this->stringsecurity($victro_query, $victro_words);
        if($victro_check == false){
            
            $this->set_debug_query($victro_query);
            try {
                $victro_tb = $this->victro_conn()->prepare($victro_query);
                $victro_select = $victro_tb->execute();
                $this->victro_returnsDb = $victro_tb;
                if($victro_successLog == true){
                    $victro_string = "SUCCESS SQL - ".$victro_query;
                    $this->create_dbLog("SQL SUCCESS", $victro_string);
                }
            } catch (PDOException $victro_e){
                $victro_string = "SQL SELECT ERROR- ".$victro_e->getMessage();
                $this->create_dbLog("SQL ERROR", $victro_string);
            }
        } else {
            $victro_string = "DENIED SQL: SQL query contains not allowed fields";
            $this->create_dbLog("DENIED SQL", $victro_string);
        }
        return($this);
    }
    /**
     * Sent a select query to database<BR>
     * Exemple: <i>$this->db_select("id");</i>
     * @param Bolean $victro_cleanFields clean defined params (select, from, where, group by...) (defined as true)
     * @param Boolean $victro_successLog insert a log if the query has success (defined as false)
     */
    protected function db_select($victro_cleanFields = true, $victro_successLog = false){
        $victro_array_select = $this->get_select();
        $victro_array_from   = $this->get_from(); 
        $victro_array_where   = $this->get_where();
        $victro_words = array("victro_");
        $victro_check = true;
        if(count($victro_array_select) > 0 and count($victro_array_from) > 0){
            $victro_check = false;
            foreach($victro_array_from as $victro_from){
                $victro_check = $this->stringsecurity($victro_from, $victro_words);
                if($victro_check == true){
                    foreach($victro_array_select as $victro_select){
                        $victro_check = $this->stringsecurity($victro_select, array('victro_password'));
                        if($victro_check == true){
                            break;
                        }
                    }
                }
            }
        }
        if($victro_check == false){
            
            $victro_selectquery = implode(", ", $victro_array_select);
            $victro_fromquery = implode(", ", $victro_array_from);
            if(count($victro_array_where) > 0){
                $victro_wherequery = " where ".implode(" ", $victro_array_where);
            } else {
                $victro_wherequery = "";
            }
            if ($this->get_orderby() != '') {
                $victro_order = " order by " . $this->get_orderby();
            } else {
                $victro_order = "";
            }
            if ($this->victro_limit_db != '') {
                $victro_limit = " limit " . $this->victro_limit_db;
            } else {
                $victro_limit = "";
            }
            if ($this->victro_group_db != '') {
                $victro_group = " GROUP BY " . $this->victro_group_db;
                if (isset($this->victro_having_db)) {
                    $victro_having = " HAVING " . $this->victro_having_db;
                } else {
                    $victro_having = "";
                }
            } else {
                $victro_group = "";
                $victro_having = "";
            }
            $victro_joinsString = " ".implode(" ", $this->victro_joins);
            $victro_query = 'select ' . $victro_selectquery . ' from ' . $victro_fromquery . $victro_joinsString . $victro_wherequery . $victro_group . $victro_having . $victro_order . $victro_limit;
            $this->set_debug_query($victro_query);
            try {
                $victro_tb = $this->victro_conn()->prepare($victro_query);
                $victro_select = $victro_tb->execute();
                $this->victro_returnsDb = $victro_tb;
                if($victro_successLog == true){
                    $victro_string = "SUCCESS SQL - ".$victro_query;
                    $this->create_dbLog("SQL SUCCESS", $victro_string);
                }
            } catch (PDOException $victro_e){
                $victro_string = "SQL SELECT ERROR- ".$victro_e->getMessage();
                $this->create_dbLog("SQL ERROR", $victro_string);
            }
        } else {
            $victro_string = "DENIED SQL: SQL query contains not allowed fields";
            $this->create_dbLog("DENIED SQL", $victro_string);
        }
        if($victro_cleanFields == true){
            $this->victro_table_db = array();
            $this->victro_select_db = array();
            $this->victro_where_db = array();
            $this->victro_order_db = "";
            $this->victro_group_db = "";
            $this->victro_limit_db = "";
            $this->victro_having_db = "";
        }
        return($this);
    } 
     /**
     * Sent a insert query to database<BR>
     * Exemple:<BR>
      * <pre>
      *     <i>$bot_data = array('name' => 'Jean', 'robot' => victro);</i>
      *     <i>$this->db_insert("owner", $bot_data);</i>
      * </pre>
     * @param String $victro_table table name
     * @param Array $victro_data array with field name and value (check exemple)
     * @param Boolean $victro_successLog insert a log if the query has success (defined as false)
     */
    protected function db_insert($victro_table, $victro_data, $victro_successLog = false){
        $victro_words = array("victro_");
        $victro_check = false;
        $victro_check = $this->stringsecurity($victro_table, $victro_words);
        if (substr($victro_table, 0, 4) !== 'bot_' && substr($victro_table, 0, 7) !== 'victro_'){
            $victro_table = "bot_".$victro_table;
        }
        if($victro_check == false){
            
            $victro_fields_array = array();
            foreach($victro_data as $victro_key => $victro_dat){
                $victro_fields_array[] = "{$victro_key}";
                $victro_values_array[] = ":{$victro_key}";
            }
            $victro_fields = implode(", ", $victro_fields_array);
            $victro_values = implode(", ", $victro_values_array);
            $victro_query =  "insert into {$victro_table} ({$victro_fields}) values ({$victro_values})";
            $victro_query2 = "insert into {$victro_table} ({$victro_fields}) values ({$victro_values})";
            $this->set_debug_query($victro_query);
            try {
                $victro_conn = $this->victro_conn();
                $victro_tb = $victro_conn->prepare($victro_query);
                $victro_valueinsert = array();
                foreach($victro_data as $victro_key => $victro_dat){
                    if (is_numeric($victro_dat)) {
                        $victro_valueinsert[$victro_key] = $victro_dat;
                        $victro_tb->bindParam(":" . $victro_key, $victro_valueinsert[$victro_key], PDO::PARAM_INT);
                        $victro_query2 = str_replace(':'.$victro_key, $victro_dat, $victro_query2);
                    } else {
                        $victro_string = accent($victro_dat);
                        $victro_tb->bindParam(":" . $victro_key, $victro_string, PDO::PARAM_STR);
                        $victro_query2 = str_replace(':'.$victro_key, "'{$victro_dat}'", $victro_query2);
                        unset($victro_string);
                    }
                }
                unset($victro_valueinsert);
                $victro_select = $victro_tb->execute();
                $this->victro_last_id = $victro_conn->lastInsertId();
                $this->victro_returnsDb = $victro_tb;
                if($victro_successLog == true){
                    $victro_string = "SUCCESS SQL - ".$victro_query2;
                    $this->create_dbLog("SQL SUCCESS", $victro_string);
                }
            } catch (PDOException $victro_e){
                $victro_string = "SQL SELECT ERROR- ".$victro_e->getMessage();
                $this->create_dbLog("SQL ERROR", $victro_string);
            }
            $this->set_debug_query($victro_query2);
        } else {
            $victro_string = "DENIED SQL: SQL query contains not allowed fields";
            $this->create_dbLog("DENIED SQL", $victro_string);
        }
        $this->victro_where_db = array();
        return($this);
    }
    /**
     * Sent a update query to database<BR>
     * To database security to send a update query is necessary to use $this->where("") method
     * Exemple:<BR>
      * <pre>
      *     <i>$this->where("ID = 1")</i>
      *     <i>$bot_data = array('name' => 'Jean', 'robot' => victro);</i>
      *     <i>$this->db_update("owner", $bot_data);</i>
      * </pre>
     * @param String $victro_table table name
     * @param Array $victro_data array with field name and value (check exemple)
     * @param Boolean $victro_successLog insert a log if the query has success (defined as false)
     */
    protected function db_update($victro_table, $victro_data, $victro_successLog = false){
        $victro_array_where   = $this->get_where();
        $victro_words = array("victro_");
        $victro_check = false;
        $victro_check = $this->stringsecurity($victro_table, $victro_words);
        if (substr($victro_table, 0, 4) !== 'bot_' && substr($victro_table, 0, 7) !== 'victro_'){
            $victro_table = "bot_".$victro_table;
        }
        if($victro_check == false){
            
            if(count($victro_array_where) > 0){
                $victro_wherequery = " where ".implode(" ", $victro_array_where);
                $victro_fields_array = array();
                foreach($victro_data as $victro_key => $victro_dat){
                    $victro_fields_array[] = "{$victro_key} = :{$victro_key}";
                }
                $victro_fields = implode(", ", $victro_fields_array);
                $victro_query = "update {$victro_table} set {$victro_fields} {$victro_wherequery}";
                $victro_query2 = "update {$victro_table} set {$victro_fields} {$victro_wherequery}";
                $this->set_debug_query($victro_query);
                try {
                    $victro_tb = $this->victro_conn()->prepare($victro_query);
                    foreach($victro_data as $victro_key => $victro_dat){
                        if (is_numeric($victro_dat)) {
                            $victro_tb->bindValue(":" . $victro_key, $victro_dat, PDO::PARAM_INT);
                            $victro_query2 = str_replace(':'.$victro_key, $victro_dat, $victro_query2);
                        } else {
                            $dbString = accent($victro_dat);
                            $victro_tb->bindValue(":" . $victro_key, $dbString, PDO::PARAM_STR);
                            $victro_query2 = str_replace(':'.$victro_key, "'{$victro_dat}'", $victro_query2);
                        }
                    }
                    $victro_select = $victro_tb->execute();
                    $this->victro_returnsDb = $victro_tb;
                    if($victro_successLog == true){
                        $victro_string = "SUCCESS SQL - ".$victro_query2;
                        $this->create_dbLog("SQL SUCCESS", $victro_string);
                    }
                } catch (PDOException $victro_e){
                    $victro_string = "SQL SELECT ERROR- ".$victro_e->getMessage();
                    $this->create_dbLog("SQL ERROR", $victro_string);
                }
                $this->set_debug_query($victro_query2);
            } else {
                $victro_string = "DENIED SQL: You need to specify at least one where clause";
                $this->create_dbLog("DENIED SQL", $victro_string);
            }
        } else {
            $victro_string = "DENIED SQL: SQL query contains not allowed fields";
            $this->create_dbLog("DENIED SQL", $victro_string);
        }
        $this->victro_where_db = array();
        return($this);
    }
    /**
     * Sent a delete query to database<BR>
     * To database security to send a delete query is necessary to use $this->where("") method
     * Exemple:<BR>
      * <pre>
      *     <i>$this->where("ID = 1")</i>
      *     <i>$this->db_delete("owner");</i>
      * </pre>
     * @param String $victro_table table name
     * @param Boolean $victro_successLog insert a log if the query has success (defined as false)
     */
    protected function db_delete($victro_table, $victro_successLog = false){
        $victro_array_where   = $this->get_where();
        $victro_words = array("victro_");
        $victro_check = false;
        $victro_check = $this->stringsecurity($victro_table, $victro_words);
        
        if($victro_check == false){
            
            if(count($victro_array_where) > 0){
                $victro_wherequery = " where ".implode(" ", $victro_array_where);
                $victro_query = "delete from {$victro_table} {$victro_wherequery}";
                $this->set_debug_query($victro_query);
                try {
                    $victro_tb = $this->victro_conn()->prepare($victro_query);
                    $victro_select = $victro_tb->execute();
                    $this->victro_returnsDb = $victro_tb;
                    if($victro_successLog == true){
                        $victro_string = "SUCCESS SQL - ".$victro_query;
                        $this->create_dbLog("SQL SUCCESS", $victro_string);
                    }
                } catch (PDOException $victro_e){
                    $victro_string = "SQL SELECT ERROR- ".$victro_e->getMessage();
                    $this->create_dbLog("SQL ERROR", $victro_string);
                }
            } else {
                $victro_string = "DENIED SQL: You need to specify at least one where clause";
                $this->create_dbLog("DENIED SQL", $victro_string);
            }
        } else {
            $victro_string = "DENIED SQL: SQL query contains not allowed fields";
            $this->create_dbLog("DENIED SQL", $victro_string);
        }
        $this->victro_where_db = array();
        return($this);
    }
    private function create_dbLog($victro_type, $victro_string){
        $victro_logTable = "(".implode(",", $this->victro_table_db)."";
        $victro_method = debug_backtrace()[1]['function'];
        $victro_paginalink = debug_backtrace()[1]['file'];
        $victro_pagina = explode("\\", $victro_paginalink);
        $victro_log = str_replace("[METHOD]", $victro_method, $victro_string);
        $victro_log = str_replace("[TABLE]", $victro_logTable, $victro_string);
        $victro_log = str_replace("[PAGE]", $victro_pagina[count($victro_pagina) - 1], $victro_log);
        $victro_datalog = date('Y-m-d h:i:s');
        $victro_tb = $this->victro_conn()->prepare('insert into victro_log VALUES(null, :log, :date, :user, "'.$victro_type.'"); ');
        $victro_tb->bindParam(":log", $victro_log, PDO::PARAM_STR);
        $victro_tb->bindParam(":date", $victro_datalog, PDO::PARAM_STR);
        $victro_tb->bindParam(":user", $_SESSION['iduser'], PDO::PARAM_INT);
        $victro_tb = $victro_tb->execute();
        $_SESSION['victro_log_session'][] = array('ID' => $this->victro_conn()->lastInsertId($victro_tb),'USER' => $_SESSION['iduser'], 'TYPE' => $victro_type, 'DATE' => $victro_datalog, 'MESSAGE' => $victro_log);
    }
    /**
     * Set robot params to be GLOBAL<BR>
     * Exemple: <i>$bot_global = $this->global_robot()</i>
     * @return Array Robot params (id, url, full_url...)
     */
    protected function global_robot() {
        GLOBAL $victro_robot;
        return($victro_robot);
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
        if (is_array($victro_data) and count($victro_data) > 0) {
            extract($victro_data, EXTR_PREFIX_SAME, "bot");
        }
        if (file_exists($victro_robot['local_url'] . 'controller/' . $victro_name_controller . '.php')) {
            require_once($victro_robot['local_url'] . 'controller/' . $victro_name_controller . '.php');
            return(true);
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
        GLOBAL $victro_robot;
        if ($victro_name_model == "") {
            $victro_name_model = $victro_robot['action'];
        }
        if (is_array($victro_data) and count($victro_data) > 0) {
            extract($victro_data, EXTR_PREFIX_SAME, "bot");
        }
        if (file_exists($victro_robot['local_url'] . '/model/' . $victro_name_model . '.php')) {
            require_once($victro_robot['local_url'] . '/model/' . $victro_name_model . '.php');
            $victro_act_robot = new $victro_name_model;
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
        if (is_array($victro_data) and count($victro_data) > 0) {
            extract($victro_data, EXTR_PREFIX_SAME, "bot");
        }
        if (file_exists($victro_robot['local_url'] . 'view/' . $victro_name_view . '.php')) {
            if ($victro_mode == false) {
                require_once($victro_robot['local_url'] . 'view/' . $victro_name_view . '.php');
            } else {
                $victro_content_file = file_get_contents($victro_robot['local_url'] . 'view/' . $victro_name_view . '.php');
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
        if (is_array($victro_data) and count($victro_data) > 0) {
            extract($victro_data, EXTR_PREFIX_SAME, "bot");
        }
        if (file_exists($victro_robot['local_url'] . '/view/' . $victro_name_view . '.php')) {
            $victro_content = $victro_robot['local_url'] . '/view/' . $victro_name_view . '.php';
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
    protected function input($victro_name, $victro_type = "POST", $victro_filter = "default") {
        $victro_type_array = explode("_", $victro_type);
        $victro_value = false;
        $victro_filter = "FILTER_" . mb_strtoupper($victro_filter, "UTF-8");
        foreach ($victro_type_array as $victro_type_array2) {
            $victro_type = mb_strtoupper($victro_type_array2, "UTF-8");
            echo $victro_value1 = filter_input(constant("INPUT_" . $victro_type), $victro_name, constant($victro_filter));
            if ($victro_value1 != false and $victro_value1 != null) {
                $victro_value = $victro_value1;
            }
        }
        return($victro_value);
    }
    /**
     * Create a new notification<BR>
     * Create new notification to system
     * @param String $victro_noti_subject subject
     * @param String $victro_noti_text text
     * @param String $victro_noti_icon icon
     * @param String $victro_noti_touser who see (t:LEVEL or u:ID_USER)
     * @param String $victro_noti_link link when click over notification
     * @param String $victro_whosend who send this notification
     */
    protected function victro_newnotification($victro_noti_subject, $victro_noti_text = '', $victro_noti_icon = 'fa fa-bell-o', $victro_noti_touser = 't:1', $victro_noti_link = 'system/notification', $victro_whosend = null) {
        GLOBAL $victro_robot;
        
        $victro_tb = $this->victro_conn()->prepare("insert into victro_notifications values (null, :subject , :text, :icon, :link, '0', :touser, :send)");
        $victro_tb->bindParam(":subject", $victro_noti_subject, PDO::PARAM_STR);
        $victro_tb->bindParam(":text", $victro_noti_text, PDO::PARAM_STR);
        $victro_tb->bindParam(":icon", $victro_noti_icon, PDO::PARAM_STR);
        $victro_tb->bindParam(":touser", $victro_noti_touser, PDO::PARAM_STR);
        $victro_tb->bindParam(":link", $victro_noti_link, PDO::PARAM_STR);
        if ($victro_whosend == null) {
            $victro_tb->bindParam(":send", $victro_robot['name'], PDO::PARAM_STR);
        } else {
            $victro_tb->bindParam(":send", $victro_whosend, PDO::PARAM_STR);
        }
        $victro_tb->execute();
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
    protected function allow_user_type_by($victro_id) {
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
        if ($victro_permited == 0) {
            header('location:' . $victro_site['full_url'] . 'system/home');
        }
    }
    /**
     * Start Session<BR>
     * Start a session in security mode
     * @param String $victro_name Session name
     * @param Object $victro_value session data (integer, array, string...)
     * @param String $victro_empty clean session before set the new value
     * @param String $victro_unique session with unique values (check array_unique in PHP documentation)
     * @param String $victro_bot get session of another robot plugin (default 0)
     */
    protected function start_session($victro_name, $victro_value, $victro_empty = null, $victro_unique = null, $victro_bot = 0) {
        GLOBAL $victro_datap;
        if ($victro_bot == 0) {
            $victro_id_bot = $victro_datap[1];
        } else {
            if (is_array($victro_bot)) {
                if (isset($victro_bot['name']) and isset($victro_bot['author'])) {
                    $this->select('id');
                    $this->from('victro_robot');
                    $this->where("name = '{$victro_bot['name']}' and author = '{$victro_bot['author']}'");
                    $victro_query = $this->db_select();
                    if ($victro_query->get_count() > 0) {
                        $victro_id_bot = $victro_query->get_row()->id;
                    } else {
                        $victro_id_bot = $victro_datap[1];
                        $victro_log = "Actual WK ID was used because the WK passed does not exist";
                        $this->create_dbLog("SESSION", $victro_log);
                    }
                } else {
                    $victro_id_bot = $victro_datap[1];
                    $victro_log = "Actual WK ID was used because the WK passed does not exist";
                    $this->create_dbLog("SESSION", $victro_log);
                }
            } else {
                $this->select('id');
                $this->from('victro_robot');
                $this->where("id = '{$victro_bot}'");
                $victro_query = $this->db_select();
                if ($victro_query->get_count() > 0) {
                    $victro_id_bot = $victro_query->get_row()->id;
                } else {
                    $victro_id_bot = $victro_datap[1];
                    $victro_log = "Actual WK ID was used because the WK passed does not exist";
                    $this->create_dbLog("SESSION", $victro_log);
                }
            }
        }
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
        return($this);
    }
    /**
     * Get Session<BR>
     * Get a session in security mode
     * @param String $victro_name Session name
     * @param String $victro_bot get session of another robot plugin (default 0)
     * @return Object If session is not set returns false else return Session's value
     */
    protected function get_session($victro_name, $victro_bot = 0) {
        GLOBAL $victro_datap;
        if ($victro_bot == 0) {
            $victro_id_bot = $victro_datap[1];
        } else {
            if (is_array($victro_bot)) {
                if (isset($victro_bot['name']) and isset($victro_bot['author'])) {
                    $this->select('id');
                    $this->from('victro_robot');
                    $this->where("name = '{$victro_bot['name']}' and author = '{$victro_bot['author']}'");
                    $victro_query = $this->db_select();
                    if ($victro_query->get_count() > 0) {
                        $victro_id_bot = $victro_query->get_row()->id;
                    } else {
                        $victro_id_bot = $victro_datap[1];
                        $victro_log = "Actual WK ID was used because the WK passed does not exist";
                        $this->create_dbLog("SESSION", $victro_log);
                    }
                } else {
                    $victro_id_bot = $victro_datap[1];
                    $victro_log = "Actual WK ID was used because the WK passed does not exist";
                    $this->create_dbLog("SESSION", $victro_log);
                }
            } else {
                $this->select('id');
                $this->from('victro_robot');
                $this->where("id = '{$victro_bot}'");
                $victro_query = $this->db_select();
                if ($victro_query->get_count() > 0) {
                    $victro_id_bot = $victro_query->get_row()->id;
                } else {
                    $victro_id_bot = $victro_datap[1];
                    $victro_log = "Actual WK ID was used because the WK passed does not exist";
                    $this->create_dbLog("SESSION", $victro_log);
                }
            }
        }
        if (isset($_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)])) {
            return($_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)]);
        } else {
            return(false);
        }
    }
    /**
     * Delete Session<BR>
     * Delete a session in security mode
     * @param String $victro_name Session name
     * @param String $victro_bot get session of another robot plugin (default 0)
     */
    protected function unset_session($victro_name, $victro_bot = 0) {
        GLOBAL $victro_datap;
        if ($victro_bot == 0) {
            $victro_id_bot = $victro_datap[1];
        } else {
            if (is_array($victro_bot)) {
                if (isset($victro_bot['name']) and isset($victro_bot['author'])) {
                    $this->select('id');
                    $this->from('victro_robot');
                    $this->where("name = '{$victro_bot['name']}' and author = '{$victro_bot['author']}'");
                    $victro_query = $this->db_select();
                    if ($victro_query->get_count() > 0) {
                        $victro_id_bot = $victro_query->get_row()->id;
                    } else {
                        $victro_id_bot = $victro_datap[1];
                        $victro_log = "Actual WK ID was used because the WK passed does not exist";
                        $this->create_dbLog("SESSION", $victro_log);
                    }
                } else {
                    $victro_id_bot = $victro_datap[1];
                    $victro_log = "Actual WK ID was used because the WK passed does not exist";
                    $this->create_dbLog("SESSION", $victro_log);
                }
            } else {
                $this->select('id');
                $this->from('victro_robot');
                $this->where("id = '{$victro_bot}'");
                $victro_query = $this->db_select();
                if ($victro_query->get_count() > 0) {
                    $victro_id_bot = $victro_query->get_row()->id;
                } else {
                    $victro_id_bot = $victro_datap[1];
                    $victro_log = "Actual WK ID was used because the WK passed does not exist";
                    $this->create_dbLog("SESSION", $victro_log);
                }
            }
        }
        if (isset($_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)])) {
            unset($_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)]);
        }
        return($this);
    }
    /**
     * Unset value of a Session<BR>
     * If session is an array it will search a value then unset it.
     * @param String $victro_name Session name
     * @param String $victro_search value search
     * @param String $victro_bot get session of another robot plugin (default 0)
     */
    protected function unset_value_session($victro_name, $victro_search, $victro_bot = 0) {
        GLOBAL $victro_datap;
        if ($victro_bot == 0) {
            $victro_id_bot = $victro_datap[1];
        } else {
            if (is_array($victro_bot)) {
                if (isset($victro_bot['name']) and isset($victro_bot['author'])) {
                    $this->select('id');
                    $this->from('victro_robot');
                    $this->where("name = '{$victro_bot['name']}' and author = '{$victro_bot['author']}'");
                    $victro_query = $this->db_select();
                    if ($victro_query->get_count() > 0) {
                        $victro_id_bot = $victro_query->get_row()->id;
                    } else {
                        $victro_id_bot = $victro_datap[1];
                        $victro_log = "Actual WK ID was used because the WK passed does not exist";
                        $this->create_dbLog("SESSION", $victro_log);
                    }
                } else {
                    $victro_id_bot = $victro_datap[1];
                    $victro_log = "Actual WK ID was used because the WK passed does not exist";
                    $this->create_dbLog("SESSION", $victro_log);
                }
            } else {
                $this->select('id');
                $this->from('victro_robot');
                $this->where("id = '{$victro_bot}'");
                $victro_query = $this->db_select();
                if ($victro_query->get_count() > 0) {
                    $victro_id_bot = $victro_query->get_row()->id;
                } else {
                    $victro_id_bot = $victro_datap[1];
                    $victro_log = "Actual WK ID was used because the WK passed does not exist";
                    $this->create_dbLog("SESSION", $victro_log);
                }
            }
        }
        if (isset($_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)])) {
            if (($victro_key = array_search($victro_search, $_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)])) !== false) {
                unset($_SESSION[base64_encode(date('m') . $victro_id_bot . $victro_name)][$victro_key]);
                return(true);
            } else {
                return(false);
            }
        }
    }
    /**
     * Send a file to Victro_Storage<BR>
     * This method sends file to victro_storage encrypted.
     * @param Array $victro_type allowed types of files ex: array('pdf', 'jpg', 'doc')
     * @param FILE $victro_sendfile file content
     * @param integer $victro_size max file size in mb (default 2 mb)
     * @param integer $victro_access requires password to access? (1= no, 0 = yes)
     * @param integer $victro_pass file password (default null)
     * @return Array array with file id and name (KEYS ['id', 'name'])
     */
    protected function send_file(Array $victro_type, $victro_sendfile, $victro_size = 2, $victro_access = 1, $victro_pass = null) {
        global $victro_robot;
        global $victro_maker;
        if ($victro_pass == null) {
            $victro_pass = time();
        }
        $victro_pasta = explode("victro_apps/victro_robot/", $victro_robot['local_url']);
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
    /**
     * This method set a language to bot_translate function
     */
    protected function language($victro_lang){
        $_SESSION['bot_lang'] = $victro_lang;
    }
}
?>