<?php
	if(!defined('PROTECT')){ exit('NO ACCESS'); }
	if(!isset($_SESSION['typeuser'])) header('location: '.SITE_URL.'/system/login');
	require_once('victro_system/victro_settings/mainMethod.class.php');
	$victro_maker = new VictroFunc();
	if(isset($_GET['id']) or isset($_GET['name'])){
		if(isset($_GET['pass'])){
			$victro_ret['pass'] = $_GET['pass'];
		}
		if(isset($_GET['id']) and isset($_GET['name'])){
			$victro_ret['id'] = $_GET['id'];
			$victro_ret['name'] = $_GET['name'];
		} else if(isset($_GET['id'])){
			$victro_ret['id'] = $_GET['id'];
		} else if(isset($_GET['name'])){
			$victro_ret['name'] = $_GET['name'];
		}
		$victro_file = $victro_maker->load_file_db($victro_ret);
		if(!isset($victro_file['error'])){
			$victro_folder = SITE_URL.'victro_apps/victro_storage/';
			$victro_folder1 = 'victro_apps/victro_storage/';
			if($victro_file['type'] == "png" or $victro_file['type'] == "PNG" or $victro_file['type'] == "jpg" or
			   $victro_file['type'] == "JPG" or $victro_file['type'] == "gif" or $victro_file['type'] == "GIF" or
			   $victro_file['type'] == "JPEG" or $victro_file['type'] == "jpeg"){
					if(file_exists($victro_folder1.$victro_file['folder'].'/'.$victro_file['name'])){
						$victro_filecont = file_get_contents($victro_folder.$victro_file['folder'].'/'.$victro_file['name']);
						$victro_cont = explode("+?+|+?+",base64_decode($victro_filecont));
						header('Content-type:image/'.$victro_file['type']);
						echo ($victro_cont[1]);
					} else {
						header("Content-type: image/gif"); //Informa ao browser que o arquivo é uma imagem no formato GIF
						$victro_imagem = ImageCreate(250,250); //Cria uma imagem com as dimensões 100x20
						$victro_vermelho = ImageColorAllocate($victro_imagem, 150, 0, 0); //Cria o segundo plano da imagem e o configura para vermelho
						$victro_branco = ImageColorAllocate($victro_imagem, 255, 255, 255); //Cria a cor de primeiro plano da imagem e configura-a para branco
						ImageString($victro_imagem,150, 100, 110, "404 ;(", $victro_branco); //Imprime na imagem o texto PHPBrasil na cor branca que está na variável $victro_branco
						ImageGif($victro_imagem); //Converte a imagem para um GIF e a envia para o browser
						ImageDestroy($victro_imagem); //Destrói a memória alocada para a construção da imagem GIF.
					}
				} else {
					if(file_exists($victro_folder1.$victro_file['folder'].'/'.$victro_file['name'])){
						$victro_rand = mt_rand(111, 9999999);
						$victro_namefile = $victro_rand.time().'.'.$victro_file['type'];
						$victro_filecont = file_get_contents($victro_folder.$victro_file['folder'].'/'.$victro_file['name']);
						$victro_cont = explode("+?+|+?+",base64_decode($victro_filecont));
						file_put_contents($victro_namefile, $victro_cont[1]);
						header('Content-Description: File Transfer');
						header('Content-Disposition: attachment; filename="'.$victro_namefile.'"');
						header('Content-Transfer-Encoding: binary');
						header("Content-Type: text/html");
						header('Content-Length: ' . filesize($victro_namefile));
						header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
						header('Pragma: public');
						header('Expires: 0');
						ob_end_clean(); //essas duas linhas antes do readfile
						flush();
						readfile($victro_namefile);
						unlink($victro_namefile);
					}
				}
		} else {
			exit($victro_file['error']);
		}
	}
?>
