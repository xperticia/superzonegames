<?php
	require("../config.php");

	// If you want to ignore the uploaded files, 
	// set $demo_mode to true;
	$demo_mode = false;
	$upload_dir = '../../images/fotogaleria/';
	$allowed_ext = array('jpg','jpeg','png','gif');

	if(strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
		exit_status('Error! Wrong HTTP method!');
	}

	if(array_key_exists('pic',$_FILES) && $_FILES['pic']['error'] == 0 ){
		
		//	obtengo el proximo ID
		$conexion->Query("INSERT INTO fotogaleria (ID_FOTO,FECHA_ALTA,ACTIVO,CLICK) VALUES(null,'".date("Y/m/d")."','1', '0')");
		$id_foto = $conexion->Insert_ID();

		$pic = $_FILES['pic'];
		$nombre_completo = sprintf("I%07s",$id_foto).".".get_extension($pic['name']);

		if(!in_array(get_extension($pic['name']),$allowed_ext)){
			exit_status('Solo se permiten los siguientes archivos: '.implode(',',$allowed_ext));
		}	

		if($demo_mode){			
			// File uploads are ignored. We only log them.			
			$line = implode('		', array( date('r'), $_SERVER['REMOTE_ADDR'], $pic['size'], $pic['name']));
			file_put_contents('log.txt', $line.PHP_EOL, FILE_APPEND);			
			exit_status('Uploads are ignored in demo mode.');
		}
				
		// Move the uploaded file from the temporary 
		// directory to the uploads folder:		
		if(move_uploaded_file($pic['tmp_name'], $upload_dir.$nombre_completo)){
			//exit_status('File was uploaded successfuly!');

			//si no hubo inconvenientes registra los datos del archivo
			$conexion->Query("UPDATE fotogaleria SET nombre = '".$nombre_completo."' WHERE (id_foto = '".$id_foto."')");
			//exit_status($nuevo_id_registro);
			exit_status($id_foto);
		}		
	}

	exit_status('Something went wrong with your upload!');

	// Helper functions
	function exit_status($str){
		//echo(json_encode(array('status'=>$str)));
		echo("{ \"status\" : \"$str\" }");
		exit;
	}

	function get_extension($file_name){
		$ext = explode('.', $file_name);
		$ext = array_pop($ext);
		return strtolower($ext);
	}
?>