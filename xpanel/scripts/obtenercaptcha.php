<?php
	//require_once('recaptchalib.php');
	$publickey = "6Ld5ZWgUAAAAAJqTUmvORdua-Ztm8ILxSjxAYuuI";
	$privatekey = "6Ld5ZWgUAAAAAIH4T5FdxJ0TncTbVwyci0gq2yL-";
	# the response from reCAPTCHA
	$resp = null;
	# the error code from reCAPTCHA, if any
	$error = null;

	if($_GET['t'] == "obtener") {
		echo(recaptcha_get_html($publickey, $error));			
	}
	if($_GET['t'] == "verificar") {
		$recaptcha = $_POST["g-recaptcha-response"];
	 
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = array(
			'secret' => $privatekey,
			'response' => $recaptcha
		);
		$options = array(
			'http' => array (
				'method' => 'POST',
				'content' => http_build_query($data)
			)
		);
		$context  = stream_context_create($options);
		$verify = file_get_contents($url, false, $context);
		$captcha_success = json_decode($verify);

		if ($captcha_success->success) {
			echo("{ \"label\" : \"OK\", \"value\" : \"OK\" }");
		} else {
			echo("{ \"label\" : \"ERROR\", \"value\" : \"\" }");
		}
	}
?>