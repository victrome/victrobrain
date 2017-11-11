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

if (!file_exists(PATH_SYSTEM.PATH_SETTINGS . 'database.php')) {
  if (is_dir(PATH_SYSTEM.PATH_INSTALL)) {
    if (file_exists(PATH_SYSTEM.PATH_INSTALL . 'install.php')) {
      header('location: ' . PATH_SYSTEM.PATH_INSTALL . 'install.php');
      exit();
    } else {
      goto errorfile;
    }
  } else {
    errorfile:
    echo "VictroBrain can't be started, it is not installed!";
    exit();
  }
}
require_once(PATH_SYSTEM.PATH_SETTINGS.'security.php'); //load security line

//Check if no robot is the index and if 'index' is in the url the system redirect user to home/login
if (VICTRO_INDEX == 'index' and ( URL_0 == "index" or URL_0 == "index/")) {
  header('location:' . SITE_URL);
}
if (defined('URL_0')) {
  if (URL_0 == "terminal") {
    include(PATH_SYSTEM.PATH_SYSTEM. 'sys/terminal.php');
    exit;
  }
  if (URL_0 == "sys" and defined("URL_1") and URL_1 == "command") {
    include(PATH_SYSTEM.PATH_SYSTEM. 'sys/command.php');
    exit;
  }
  if (URL_0 == "sys" and defined("URL_1") and URL_1 == "addon") {
    include(PATH_SYSTEM.PATH_SYSTEM. 'sys/addon.php');
    exit;
  }
}

if (IS_LOOGED == false) {
  include(PATH_SYSTEM. PATH_SYSTEM. 'sys/login.php');
} else {
  if (defined('URL_0') and URL_0 == 'victro_js' and defined('URL_1')) {
    if (file_exists(PATH_SYSTEM. PATH_SYSTEM. 'js/' . URL_1)) {
      $victro_extension = explode(".", URL_1);
      if($victro_extension[count($victro_extension) -1] == "js"){
        header('Content-Type: application/javascript');
      } else if($victro_extension[count($victro_extension) -1] == "css"){
        header("Content-type: text/css", true);
      }
      $victro_content = file_get_contents(PATH_SYSTEM. PATH_SYSTEM. 'js/' . URL_1);
      echo $victro_content;
      exit;
    }
  } else {
    $victro_sys_url = implode("/", $victro_url);
    if (file_exists(PATH_SYSTEM. 'victro_system/' . $victro_sys_url . '.php')) {
      require_once( PATH_SYSTEM. 'victro_system/' . $victro_sys_url . '.php');
      exit;
    }
  }

  // end system file to load
  if (URL_0 == "bot" and defined('URL_1') and !empty(URL_1)) {
    $victro_datas_robot = $victro_link;
    if(defined('URL_2') and !empty(URL_2)){
      $victro_action = URL_2;
    }
    require_once(PATH_SYSTEM. PATH_SYSTEM. 'robot_controller.php');
    exit;
  }
  if (ROUTE_BOT_ID != 0) {
    if (isset($victro_url[1]) and ! empty($victro_url[1])) {
      $victro_action = $victro_url[1];
    } else {
      $victro_action = 'index';
    }
    $victro_datas_robot = 'bot/' . ROUTE_BOT_ID . '/' . $victro_action;
    require_once(PATH_SYSTEM. PATH_SYSTEM. 'robot_controller.php');
    exit;
  }
  if (VICTRO_INDEX != 'index') {
    $victro_datas_robot = VICTRO_INDEX;
    require_once(PATH_SYSTEM. PATH_SYSTEM. 'robot_controller.php');
    exit;
  }
  if (isset($_SESSION['user']) && isset($_SESSION['typeuser']) and ((URL_0 == "sys" and defined('URL_1') and URL_1 == "home") || URL_0 == '')) {
    require_once(PATH_SYSTEM. PATH_SYSTEM. 'sys/home.php');
    exit;
  }
  require_once(PATH_SYSTEM. PATH_SYSTEM. 'sys/404.php');
}
