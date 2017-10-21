<?php
if(!file_exists('../victro_settings/database.php')) {
	$victro_version = "1.2";
	if (!file_exists('requires')) {
		$victro_Exs = array();
		$victro_requires = array();
		if (extension_loaded('pdo')) {
			$victro_requires['pdo'] = 'ok';
			$victro_checkExs = array('pdo_firebird', 'pdo_mysql', 'pdo_oci', 'pdo_odbc', 'pdo_pgsql', 'pdo_sqlite', 'pdo_sqlite_external');
			foreach($victro_checkExs as $victro_checkEx){
				if (!extension_loaded($victro_checkEx)) {
					$victro_Exs[$victro_checkEx] = "error";
				} else {
					$victro_Exs[$victro_checkEx] = "ok";
				}
			}
		}
                if(function_exists('apache_get_modules')) {
                    $victro_req = array('mod_rewrite');
                    foreach($victro_req as $victro_r){
                            if(in_array($victro_r, apache_get_modules())){
                                    $victro_requires[$victro_r] =  'ok';
                            } else {
                                    $victro_requires[$victro_r] =  'error';			
                            }
                    }
                } else {
                    $victro_requires['mod_rewrite'] =  'ok';
                }
		$victro_req[] = 'pdo';
		$victro_req[] = 'write_file';
                file_put_contents("writeFile.txt", "Write power ok");
                if(file_exists("writeFile.txt")){
                    $victro_requires['write_file'] = 'ok';
                } else {
                    $victro_requires['write_file'] = 'error';
                }
		include('install/contromodel/step0.php');
		include('install/view/step0.php');
	} else
	if (!file_exists('install/database.php')) {
		include('install/contromodel/step1.php');
		include('install/view/step1.php');
	} else {
		include('install/database.php');

		class installer extends victro_DBconnect
		{
			public function connect_victro()
			{
				$victro_connection = $this->defaultConnection();
				return ($victro_connection);
			}
		}

		$victro_maker = new installer;
		$victro_connect = $victro_maker->connect_victro();
		$victro_tb = $victro_connect->prepare("select * from victro_setting");
		$victro_tb->execute();
		$victro_cont = $victro_tb->rowCount();
		if ($victro_cont == 0) {
			include('install/contromodel/step2.php');
			include('install/view/step2.php');
		} else {
			$victro_tb1 = $victro_connect->prepare("select * from victro_user");
			$victro_tb1->execute();
			$victro_cont1 = $victro_tb1->rowCount();
			if ($victro_cont1 == 0) {
				include('install/contromodel/step3.php');
				include('install/view/step3.php');
			} else {
				include('install/contromodel/step4.php');
				include('install/view/step4.php');
			}
		}
	}
} else {
	header('location: ../index');
}
?>