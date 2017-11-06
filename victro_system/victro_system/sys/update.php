<?php
if (!defined('PROTECT')) {
    exit('NO ACCESS');
}
if (!isset($_SESSION['typeuser'])){
    header('location: ' . SITE_URL . '/sys/login');
}
if($_SESSION['typeuser'] <= 4){header('location: /sys/home');}

function delTree($dir) {
 $files = array_diff(scandir($dir), array('.','..'));
  foreach ($files as $file) {
    (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
  }
  return rmdir($dir);
}

require_once(PATH_SYSTEM.PATH_SETTINGS.'mainMethod.class.php');
$victro_maker = new VictroFunc();

if (!extension_loaded('zip')) {
    exit("zip extension is not loaded!");
}

$c = curl_init('http://victrobrain.com/site/site/checkupdate/1/'.VERSION);
curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

$html = curl_exec($c);

if (curl_error($c))
    die(curl_error($c));

$status = curl_getinfo($c, CURLINFO_HTTP_CODE);

if(isset($_GET['update'])){
  $filezip = 'http://victrobrain.com/site/site/updateForce/'.VERSION;
  if(!is_dir("victro_system/victro_update/")){
    mkdir('victro_system/victro_update');
  }
  if(!is_dir("victro_system/victro_update/victro_new")){
    mkdir('victro_system/victro_update/victro_new');
  }
  $download = 'victro_system/victro_update/victro_new/update.zip';
  //if(!file_exists($filezip)){ $errorupdate = $language['nofile'];  } else {
  if ( @copy( $filezip, $download ) ) {
    $zipU = new ZipArchive;
    if ($zipU->open($download) === TRUE) {
        $zipU->extractTo('victro_system/victro_update/victro_new');
        $zipU->close();
        unlink($download);
    }
    $zip_version = str_replace(".", "_", VERSION);
    if(!is_dir("victro_system/victro_update/victro_old")){
      mkdir('victro_system/victro_update/victro_old');
    }
    $zip = new ZipArchive();
    $zip->open('victro_system/victro_update/victro_old/'.date('d-m-Y').'- Version: '.$zip_version.'.zip' , ZipArchive::CREATE );
    if (is_dir('victro_system/victro_update/victro_new/updater/') === true){
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("victro_system/victro_update/victro_new/updater"), RecursiveIteratorIterator::SELF_FIRST);
        $upFolder = "victro_system/victro_update/victro_new/updater/";
        foreach ($files as $file){
          $file = str_replace('\\', '/', $file);
          // Ignore "." and ".." folders
          if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
              continue;
          //$file = realpath($file);
          $ftu_system = str_replace("victro_system/victro_update/victro_new/updater/", "", $file);
          if (is_dir($file) === true){
            if(!is_dir($ftu_system)){
              mkdir($ftu_system);
            }
          } else if (is_file($file) === true){
            if(file_exists($ftu_system)){
              $zip->addFile($ftu_system, $ftu_system);
            }
            rename($file, $ftu_system);
          }
        }
        $zip->addFromString("changelog.html", file_get_contents("victro_system/victro_update/victro_new/changelog.html"));
        if(file_exists("victro_system/victro_update/victro_new/info.txt")){
          $handle = fopen("victro_system/victro_update/victro_new/info.txt", "r");
          if($handle) {
              $i_line = 0;
              while (($line = fgets($handle)) !== false) {
                if($i_line == 0){
                  $queryup = "update victro_setting set value = $line where setting = 'VICTRO_VERSION'";
                  $victro_maker->execute_db_update($queryup);
                } else {
                  if(file_exists($line)){
                    unlink($line);
                  }
                }
                $i_line++;
              }
              fclose($handle);
          }
          $zip->addFromString("info.txt", file_get_contents("victro_system/victro_update/victro_new/info.txt"));
        }
    }
    $zip->close();
    delTree('victro_system/victro_update/victro_new');
    mkdir('victro_system/victro_update/victro_new');
    header("location: ".SITE_URL.'sys/update');
  }
}

curl_close($c);
require_once(THEME_FULLDIR . 'update.php');
?>
