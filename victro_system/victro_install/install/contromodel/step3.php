<?php

	if(isset($_POST['check'])){
		if($_POST['pass1'] == $_POST['pass2']){
			$victro_sendfile = $_FILES['picuser'];
			$victro_pass = time();
			$_UP['pasta'] = '../../victro_apps/victro_storage/system/users/';
			if(!is_dir($_UP['pasta'])){
				mkdir($_UP['pasta']);
			}
			$_UP['tamanho'] = 1024 * 1024 * 2; // ex 1024 * 1024 * 2 = 2mb
			$victro_ext = explode('.', $victro_sendfile['name']);
			$victro_extensao = strtolower(end($victro_ext));
			$victro_type = array('png', 'PNG', 'gif', 'GIF', 'jpg', 'jpeg');
			if (array_search($victro_extensao, $victro_type) === false) {
				header("Refresh:0");
			}
			if ($_UP['tamanho'] < $victro_sendfile['size']) {
				header("Refresh:0");
			}
			$victro_numfile = mt_rand(0,1000);
			$victro_convert_to_victro = file_get_contents($victro_sendfile['tmp_name']);
			$victro_convert_to_victro1 = base64_encode('12345678+?+|+?+'.$victro_convert_to_victro.'+?+|+?+victrohihi');
			$victro_nome_final = md5(time().$victro_sendfile['name'].$victro_numfile).'.victro';
			$victro_fp = fopen($_UP['pasta'].$victro_nome_final, "a");
			$victro_escreve = fwrite($victro_fp, $victro_convert_to_victro1);
			fclose($victro_fp); 
			$victro_value['name'] = $victro_nome_final;
			$victro_value['type'] = $victro_extensao;
			$victro_access = 1;
			$victro_value['data'] = date('Y-m-d');
			$victro_value['pass'] = $victro_pass;
			$victro_tb3 = $victro_connect->prepare("insert into victro_file values(null, '{$victro_nome_final}', '{$victro_extensao}', '{$victro_access}', '{$victro_value['data']}', 1, '{$victro_pass}', 'system/users')");
			$victro_tb3->execute();
			$victro_name =  $_POST['name'];
			$victro_username = $_POST['username'];
			$victro_email = $_POST['email'];
			$victro_pass = md5($_POST['pass1']);
			$victro_type = 5;
			$victro_fall = 0;
			$victro_tb2 = $victro_connect->prepare("insert into victro_user values(null, :type, :name, :username, :email, :pass, :fall, '{$victro_nome_final}')");
			$victro_tb2->bindParam(":name", $victro_name, PDO::PARAM_STR); 
			$victro_tb2->bindParam(":type", $victro_type, PDO::PARAM_INT);
			$victro_tb2->bindParam(":username", $victro_username, PDO::PARAM_STR);
			$victro_tb2->bindParam(":email", $victro_email, PDO::PARAM_STR);
			$victro_tb2->bindParam(":pass", $victro_pass, PDO::PARAM_STR);
			$victro_tb2->bindParam(":fall", $victro_fall, PDO::PARAM_INT);
			$victro_tb2->execute();
header("Refresh:0");
		} else {
			echo '<center><h1>PASSWORDS ARE NOT THE SAME</h1></center>';
		}
	}