<?php
if(isset($_POST['check'])){
	$victro_db = $_POST['db'];
	$victro_server = $_POST['host'];
	$victro_dbname = $_POST['name'];
	$victro_user = $_POST['user'];
	$victro_pass = $_POST['pass'];
	try {
		$victro_driver = "";
		$victro_driverArray = "";
		if($victro_db == "pdo_pgsql"){
			$victro_driver = "pgsql";
		} else if($victro_db == "pdo_mysql"){
			$victro_driver = "mysql";
			$victro_driverArray = 'array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")';
		} else if($victro_db == "pdo_firebird"){
			$victro_driver = "firebird";
		} else if($victro_db == "pdo_oci"){
			$victro_driver = "oci";
		} else if($victro_db == "pdo_odbc"){
			$victro_driver = "odbc";
		} else if($victro_db == "pdo_sqlite"){
			$victro_driver = "sqlite";
		}
		$victro_conn1 = new PDO("{$victro_driver}:host=$victro_server;dbname={$victro_dbname}","{$victro_user}","{$victro_pass}");
		$victro_tb = $victro_conn1->prepare("show tables");
		$victro_tb->execute();
		$victro_cont = $victro_tb->rowCount();
		if($victro_cont == 0){
			$victro_database = file_get_contents('install/contromodel/database.txt');
			$victro_tb1 = $victro_conn1->prepare($victro_database);
			$victro_tb1->execute();
			$victro_config = file_get_contents('install/contromodel/config.txt');
			$victro_config = str_replace("[HOST]", $victro_server, $victro_config);
			$victro_config = str_replace("[USER]", $victro_user, $victro_config);
			$victro_config = str_replace("[NAME]", $victro_dbname, $victro_config);
			$victro_config = str_replace("[PASS]", $victro_pass, $victro_config);
			$victro_config = str_replace("[DRIVER]", $victro_driver, $victro_config);
			$victro_config = str_replace("[DRIVER_ARRAY]", $victro_driverArray, $victro_config);

			file_put_contents("install/database.php", $victro_config);
			header("Refresh:0");
		} else {
			echo '<center><h3>DATABASE IS NOT EMPTY!</h3></center>';
		}
	} catch(PDOException $victro_e){ 
		echo '<center><h1>DATABASE ERROR</h1></center>';
	}
}