<?php
	
	$victro_pasta = '../../victro_system/victro_themes/';
	if(is_dir($victro_pasta)){
		$victro_diretorio = dir($victro_pasta);
		$victro_theme_dir = Array();
		while(($victro_arquivo = $victro_diretorio->read()) !== false){
			if($victro_arquivo != "." && $victro_arquivo != ".."){
				if(is_dir($victro_pasta.$victro_arquivo)){
					$victro_errol = 0;
					if(!file_exists($victro_pasta.$victro_arquivo.'/info.txt')){ $victro_errol = 1; } else {
						$victro_ponteiro = fopen ($victro_pasta.$victro_arquivo.'/info.txt', "r");
						$victro_i =1;
						while (!feof ($victro_ponteiro)) {
							$victro_linha = fgets($victro_ponteiro, 4096);
							$victro_n = explode(":", $victro_linha);
							if($victro_i == 1 and !empty($victro_n[1])){ $victro_nome[] = $victro_n[1]; $victro_ultimo = $victro_n[1];}
							if($victro_i == 2 and !empty($victro_n[1])){ $victro_autor[] = $victro_ultaut = $victro_n[1]; }
							$victro_i++;
						}
						if($victro_errol == 0 and isset($victro_ultimo) and isset($victro_ultaut)){
							$victro_theme_dir['id'][] = $victro_arquivo;
							$victro_theme_dir['name'][] = $victro_ultimo;
						}
					}
				}
			}
		}
	} else {
		$victro_theme_dir['id'][] = '';
		$victro_theme_dir['name'][] = '';
	}

	$victro_site_url = str_replace('victro_system/victro_install/install.php', '', $_SERVER ['REQUEST_URI']);
	$victro_numsla = substr_count($victro_site_url, '/') - 1;
	if(isset($_POST['check'])){
		$victro_lang = $_POST['lang'];
		$victro_theme = $_POST['theme'];
		$victro_name =  $_POST['namesite'];
		$victro_http = $_POST['http'];
		if(substr($victro_site_url, -1) != '/'){
			$victro_site_url = $victro_site_url.'/';
		}
		
		$victro_startin = 'index';
		$victro_session1 = explode(" ", $victro_name);
		$victro_session2 = $victro_session1[0]."_".mt_rand(1111,9999);
		$victro_free = 0;
		$victro_set[] = array('VICTRO_LANGUAGE', $victro_lang);	
		$victro_set[] = array('VICTRO_INDEX', $victro_startin);	
		$victro_set[] = array('VICTRO_THEME', $victro_theme);	
		$victro_set[] = array('VICTRO_SESSION_NAME', $victro_session2);	
		$victro_set[] = array('VICTRO_VERSION', $victro_version);	
		$victro_set[] = array('VICTRO_TITLE', $victro_name);	
		$victro_set[] = array('VICTRO_URL', $victro_site_url);	
		$victro_set[] = array('VICTRO_TYPE_URL', $victro_http);	
		$victro_set[] = array('VICTRO_FOLDERS', $victro_numsla);	
		$victro_set[] = array('VICTRO_LOGO', '');	
		foreach ($victro_set as $victro_value) {
			$victro_tb2 = $victro_connect->prepare("insert into victro_setting values(null, :set, :value)");
			$victro_tb2->bindParam(":set", $victro_value[0], PDO::PARAM_STR); 
			$victro_tb2->bindParam(":value", $victro_value[1], PDO::PARAM_STR);
			$victro_tb2->execute();
		}
		file_put_contents("install/htaccess", "RewriteEngine On \n");
		file_put_contents("install/htaccess", "RewriteBase $victro_site_url \n", FILE_APPEND);
		file_put_contents("install/htaccess", "RewriteCond %{REQUEST_FILENAME} !-f \n", FILE_APPEND);
		file_put_contents("install/htaccess", "RewriteCond %{REQUEST_FILENAME} !-d \n", FILE_APPEND);
		file_put_contents("install/htaccess", "RewriteRule .(/)?$ index.php \n", FILE_APPEND);
		file_put_contents("install/htaccess", "Options -Indexes \n", FILE_APPEND);
		$victro_site_url2 = str_replace('victro_system/victro_install/install.php', '', $_SERVER['SERVER_NAME'].$_SERVER ['REQUEST_URI']);
		setcookie('siteurl', $victro_http."://".$victro_site_url2, (time() + (30 * 24 * 3600)));

		header("Refresh:0");
	}