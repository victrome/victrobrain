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

/**
 * Description of addon
 *
 * @author Jean
 */
class addon {
    private $victro_addon = 0;
    private $victro_hard = "";
    private $victro_type = "";
    private $victro_pin = 0;
    private $victro_volt = "";
    private $victro_setter = "";
    private $victro_robot = null;
    public function set_addon($victro_addon_id, $victro_robot){
        $this->victro_addon = $victro_addon_id;
        $this->victro_robot = $victro_robot;
        return($this);
    }
    public function hard($victro_hard){
        $this->victro_hard = $victro_hard;
        return($this);
    }
    public function pin($victro_pin){
        $this->victro_pin = $victro_pin;
        return($this);
    }
    public function type($victro_type){
        $this->victro_type = $victro_type;
        return($this);
    }
    public function volt($victro_volt){
        $this->victro_volt = $victro_volt;
        return($this);
    }
    public function set(){
        $victro_before = "";
        if($this->victro_setter != ""){
            $victro_before = " ";
        }
        $this->victro_setter .= $victro_before ."P:".$this->victro_pin."&T:".$this->victro_type."&H:".$this->victro_hard."&V:".$this->victro_volt;    
    }
    public function send(){
        $this->victro_setter = base64_encode($this->victro_setter);
        include_once("model_robot.php");
        $victro_model = new model_robot();
        $victro_model->select("ID, ID_CHIP, MODEL, VERSION, TOKEN, TOKEN_OFFLINE, LOCAL, CHIP_TYPE");
        $victro_model->from("victro_addon");
        $victro_model->where("ID = ".$this->victro_addon);
        $victro_query = $victro_model->db_select();
        $victro_hex='';
        for ($i=0; $i < strlen($this->victro_setter); $i++){
            $victro_hex .= dechex(ord($this->victro_setter[$i]));
        }
        $victro_row = $victro_query->get_row();
        $victro_token1 = base64_encode($victro_row->TOKEN); 
        $victro_token='';
        for ($i=0; $i < strlen($victro_token1); $i++){
            $victro_token .= dechex(ord($victro_token1[$i]));
        }
        $victro_get = "?TOKEN=".$victro_token."&PIN=".$victro_hex;
        echo $victro_row->LOCAL.$victro_get;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $victro_row->LOCAL.$victro_get);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }
    public function authenticate($victro_id, $victro_token){
        include_once("model_robot.php");
        $victro_model = new model_robot();
        $victro_model->select("");
        echo "iiiii";
    }
    public function input($victro_name, $victro_type = "POST", $victro_filter = "default"){
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
}
