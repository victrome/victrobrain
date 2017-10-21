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
 * Description of system
 *
 * @author Jean
 */
include_once(PATH_SYSTEM.PATH_SETTINGS.'translation.php');
class Sys {

    public $connect;

    public function system_update($victro_type, $victro_value) {
        $victro_valueNew = str_replace("..", " ", $victro_value);
        $victro_fields['LANG'] = "VICTRO_LANGUAGE";
        $victro_fields['INDEX'] = "VICTRO_INDEX";
        $victro_fields['THEME'] = "VICTRO_THEME";
        $victro_fields['LOGO'] = "VICTRO_LOGO";
        $victro_fields['SESSION'] = "VICTRO_SESSION_NAME";
        $victro_fields['VERSION'] = "VICTRO_VERSION";
        $victro_fields['TITLE'] = "VICTRO_TITLE";
        $victro_fields['URL'] = "VICTRO_URL";
        $victro_fields['TYPE'] = "VICTRO_TYPE_URL";
        $victro_fields['FOLDERS'] = "VICTRO_FOLDERS";
        $victro_fields['USER'] = "VICTRO_USER";
        $victro_fields['PASSWORD'] = "VICTRO_PASSWORD";
        $victro_return = victro_translate("Type of update was not found!", 1, true);
        if (isset($victro_fields[$victro_type])) {
            $victro_field = $victro_fields[$victro_type];
            try {
                $victro_return = victro_translate($victro_field." updated successfully!", 1, true);
                $victro_tb = $this->connect->prepare("update victro_setting set value = :update where setting = '{$victro_field}'");
                $victro_tb->bindParam(":update", $victro_valueNew, PDO::PARAM_STR);
                $victro_tb->execute();
            } catch (PDOException $victro_e) {
                $this->create_dbLog("INTERNAL SQL ERROR", $victro_e);
            }
        }
        return($victro_return);
    }

    private function create_dbLog($victro_type, $victro_string) {
        $victro_method = debug_backtrace()[1]['function'];
        $victro_paginalink = debug_backtrace()[1]['file'];
        $victro_pagina = explode("\\", $victro_paginalink);
        $victro_log = str_replace("[METHOD]", $victro_method, $victro_string);
        $victro_log = str_replace("[PAGE]", $victro_pagina[count($victro_pagina) - 1], $victro_log);
        $victro_datalog = date('Y-m-d h:i:s');
        $victro_tb = $this->connect->prepare('insert into victro_log VALUES(null, :log, :date, :user, "' . $victro_type . '"); ');
        $victro_tb->bindParam(":log", $victro_log, PDO::PARAM_STR);
        $victro_tb->bindParam(":date", $victro_datalog, PDO::PARAM_STR);
        $victro_tb->bindParam(":user", $_SESSION['iduser'], PDO::PARAM_INT);
        $victro_tb = $victro_tb->execute();
        $_SESSION['victro_log_session'][] = array('ID' => $this->connect->lastInsertId($victro_tb), 'USER' => $_SESSION['iduser'], 'TYPE' => $victro_type, 'DATE' => $victro_datalog, 'MESSAGE' => $victro_log);
    }

}
