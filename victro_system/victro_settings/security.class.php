<?php

require_once("database.php");

class SECURITY extends victro_DBconnect {
    public function getSettings($victro_setting){
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("select * from victro_setting where setting = '{$victro_setting}'");
        $victro_tb->execute();
        $victro_return = $victro_tb->fetch(PDO::FETCH_OBJ);
        //print_r($victro_return);
        return($victro_return);
    }
    
    public function getRobotRoute($victro_name, $victro_type = 0){
        $victro_conn = $this->defaultConnection();
        $victro_tb = $victro_conn->prepare("select id, route from victro_robot where route = :route limit 1");
        $victro_tb->bindParam(":route", $victro_name, PDO::PARAM_STR);
        $victro_tb->execute();
        if ($victro_tb->rowCount() >= 1) {
            $victro_pl = $victro_tb->fetch(PDO::FETCH_OBJ);
            if($victro_type == 0){
                return($victro_pl->id);
            } else {
                return($victro_pl->route);
            }
        } else {
            return(0);
        }
    }

}

?>