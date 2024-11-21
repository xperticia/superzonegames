<!-- inicio contenido -->
<?php
	$contenido = "";
	$categorias = "";
	$categoriasActual = "";

	$titulo = "Iniciar sesión";
	$path .= " &raquo; ".$titulo;
	$url = urlencode($sitio_url."?menu=".$_GET['menu']);

	//	armo los enlaces para compartir
	$Compartir_ContenidoLateral = str_replace("{URL}",$url,$Compartir_ContenidoLateral); 
	$Compartir_ContenidoHorizontal = str_replace("{URL}",$url,$Compartir_ContenidoHorizontal); 
	?>
<h1><?php echo($titulo); ?></h1>
<header>
	<?php echo($Compartir_ContenidoHorizontal); ?>
</header>
<p>Como usuario registrado en <strong><?php echo($sitio_nombre); ?></strong> usted contara con los siguientes beneficios:</p>
<table width="100%">
<tr>
	<td>
		<ul>
			<li></li>
		</ul>
	</td>
	<td>
		<ul>
			<li></li>
		</ul>
	</td>
</tr>
</table>
<p>&nbsp;</p>
<p>A continuaci&oacute;n debe ingresar su nombre de usuario y la contrase&ntilde;a de acceso, remitida a la direcci&oacute;n de correo electr&oacute;nico suministrada en el momento de hacerse efectivo su registraci&oacute;n a <strong><?php echo($sitio_nombre); ?></strong>.</p>
<div class="row-fluid">
	<div class="span4"></div>
	<div class="span5">
		<form method="post" action="./usuarios.php" id="signup" style="padding: 20px;" class="well">
			<?php
			//	muestro los mensajes de error
			if(isset($_GET['error_login'])) {
				?>
			<div class="alert alert-error">
				<a class="close" data-dismiss="alert" href="#">&times;</a>
				<p style="margin: 0px;"><strong>Error: </strong><?php echo($msg_error[$_GET['error_login']]); ?></p>
			</div>
				<?php
			}
			?>
			<div class="control-group" style="margin-top: 20px;">
				<label class="control-label" for="user">Usuario</label>
				<div class="controls">
					<input type="text" id="user" name="user" size="30" maxlength="255" placeholder="usuario@dominio" required />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="pass">Contrase&ntilde;a</label>
				<div class="controls">
					<input type="password" id="pass" name="pass" size="30" maxlength="255" required />
				</div>
			</div>

			<div class="form-actions" style="margin-bottom: 0px;">
				<input type="submit" class="btn btn-large btn-danger" value="&raquo; iniciar sesi&oacute;n" />
			</div>
		</form>
		<p style="text-align: center;">Se recomienda utilizar Mozilla Firefox<br /><a href="https://affiliates.mozilla.org/link/banner/8955/3/20"><img src="http://affiliates-cdn.mozilla.org/media/uploads/banners/download-small-red-ES.png" alt="" /></a></p>
	</div>
	<div class="span3"></div>
</div>

<p>Si olvido su contrase&ntilde;a y desea recuperarla, haga clic en el siguiente enlace: <a href="<?php echo("./"."?menu=recuperarclave"); ?>">recuperar contrase&ntilde;a</a>.</p>
<p>Si usted se registr&oacute; en alg&uacute;n momento, y olvido su nombre de usuario y/o contrase&ntilde;a, envi&eacute; un mensaje con su Tipo y Nro. de Documento, y su Nombre y Apellido completo a la direcci&oacute;n de correo electr&oacute;nico: <a href="mailto:<?php echo($sitio_email); ?>"><?php echo($sitio_email); ?></a>.</p>

<p>&nbsp;</p>
<!-- fin contenido -->