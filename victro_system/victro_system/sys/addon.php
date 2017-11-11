<?php

/*
 * The MIT License
 *
 * Copyright 2017 contato.
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
if (!defined('PROTECT')) {
    exit('NO ACCESS');
}
require_once('victro_system/victro_system/classes/addon.php');
$victro_maker = new addon();
$victro_params = (array) json_decode($victro_maker->input("JSON_VAR", "POST"));
$victro_token = $victro_params['TOKEN'];
$victro_id = $victro_params['ID'];
$victro_return = $victro_maker->authenticate($victro_id, $victro_token);
$victro_TOKENS = "";
if($victro_return['ID_CHIP'] > 0){
  $victro_TOKENS = "TOKEN=OK&";
  $victro_TOKENS .= "MODEL=".$victro_return['MODEL']."&";
  $victro_TOKENS .= "ID_ADDON=V".$victro_return['ID_CHIP']."R&";
  $victro_TOKENS .= "ID=v".$victro_return['ID_CHIP']."&";
  $victro_TOKENS .= "VERSION=".$victro_return['VERSION']."&";
} else {
  $victro_TOKENS = "TOKEN=ERROR&";
  $victro_TOKENS .= "MODEL=".$victro_return['MODEL']."&";
  $victro_TOKENS .= "VERSION=".$victro_return['VERSION']."&";
  $victro_TOKENS .= "ID_ADDON=".$victro_return['ID_CHIP']."&";
}
echo "(VICTRO_ADDON){$victro_TOKENS}(END_VICTRO_ADDON)";
?>
