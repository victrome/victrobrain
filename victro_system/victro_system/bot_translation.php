<?php

function bot_translate($victro_string, $victro_type = 1, $victro_return = false) {
    if(isset($_SESSION['bot_lang'])){
        $victro_force = $_SESSION['bot_lang'];
    } else {
        $victro_force = false;
    }
    if($victro_force === false){
        GLOBAL $victro_language; //global var
    } else {
        GLOBAL $victro_robot;
        if(isset($_SESSION['local_url'])){
            $victro_robot2 = $_SESSION['local_url'];
        } else {
            $victro_robot2 = $victro_robot['local_url'];
        }
        $victro_language_file = PATH_APP.PATH_ROBOT.$victro_robot2."/language/".$victro_force.".php";
        if(file_exists($victro_language_file)){
            include($victro_language_file);
        }
    }
    $victro_newstring = $victro_string;
    $victro_array = explode(" ", $victro_string);
    $victro_pos = count($victro_array);
    $victro_count = 0;
    for($i = 1; $i <= $victro_pos; $i++){
        $victro_step = $victro_pos - $i + 1;
        for($j = 0; $j < $i; $j++){
            $victro_tra = "";
            for($k = 0; $k < $victro_step; $k++){
                if($victro_tra == ""){
                    $victro_tra = $victro_array[$k+$j];
                } else {
                    $victro_tra = $victro_tra." ".$victro_array[$k+$j];
                }
            }
            //$victro_compare = mb_strtolower(str_replace(" ", "_", $victro_tra));
            $victro_compare = (str_replace(" ", "_", $victro_tra));
            if(isset($victro_language[$victro_compare])){
                $victro_count = $victro_count + $victro_step;
                $victro_newstring = str_replace($victro_tra, $victro_language[$victro_compare], $victro_newstring);
            } else {
                $victro_compare = mb_strtolower(str_replace(" ", "_", $victro_tra));
                if(isset($victro_language[$victro_compare])){
                    $victro_count = $victro_count + $victro_step;
                    $victro_newstring = str_replace($victro_tra, $victro_language[$victro_compare], $victro_newstring);
                }
            }
            if($victro_count >= $victro_pos + 1){
                break;
            }
        }
    }
    //$victro_newstring = mb_strtolower($victro_newstring, "UTF-8");
    if ($victro_type == 2) {
        $victro_newstring = mb_strtoupper($victro_newstring, "UTF-8");
    } else if ($victro_type == 3) {
        $victro_newstring = mb_strtolower($victro_newstring, "UTF-8");
    } else {
        //$victro_newstring = ucfirst($victro_newstring);
    }
    if ($victro_return == false) {
        echo $victro_newstring;
    } else {
        return($victro_newstring);
    }
}

?>