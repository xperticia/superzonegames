<?php
if (!isset($_GET['menu'])) {
    $_GET['menu'] = "ayuda";
}
include("../config.php");
include("../verifica.php");
$nivel_acceso = 4; // Nivel de acceso para esta página.
if ($nivel_acceso < $_SESSION['usuario_nivel']) {
    header("Location: ./?error_login=5");
    exit;
}
?>
<!doctype html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title><?php echo(armarHead()); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">		
		<meta name="copyright" content="XPERTICIA.com" />
		<meta name="author" content="Walter E. Irahola" />
		<meta name="email" content="info@xperticia.com" />
		<link rel="shortcut icon" href="../favicon.ico" />

		<link rel="stylesheet" href="../images/xpanel.css" type="text/css" />
	</head>

	<body>
		<?php include("../head.php"); ?>

		<h3>Manual de uso v2.0</h3>

		<ul class="nav nav-tabs">
			<li class="dropdown active">
				<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">Contenidos <b class="caret"></b></a>
				<ul class="dropdown-menu">
				<li><a href="#s01" data-toggle="tab">Que es XPANEL?</a></li>
				<li><a href="#s02" data-toggle="tab">Como comenzar a trabajar con XPANEL?</a></li>
				<li><a href="#s03" data-toggle="tab">Como acceder a los M&oacute;dulos?</a></li>
				<li><a href="#s04" data-toggle="tab">Como es el entorno de trabajo de cada Modulo?</a></li>
				<li><a href="#s05" data-toggle="tab">Como insertar nuevos datos?</a></li>
				<li><a href="#s06" data-toggle="tab">Como puedo insertar im&aacute;genes en un &aacute;rea de texto?</a></li>
				<li><a href="#s07" data-toggle="tab">Como puedo eliminar im&aacute;genes en un &aacute;rea de texto?</a></li>
				<li><a href="#s08" data-toggle="tab">Como puedo adjuntar archivos a un registro?</a></li>
				<li><a href="#s09" data-toggle="tab">Como puedo eliminar archivos adjuntos a un registro?</a></li>
				<li><a href="#s10" data-toggle="tab">Como deshabilitar registros?</a></li>
				</ul>
			</li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane active" id="s01">
				<h3>Que es XPANEL?</h3>
				<p>Es un sistema en l&iacute;nea, desarrollado por <a href="http://www.xperticia.com" target="_blank">XPERTICIA.com</a>, que permite administrar un sitio web y sus contenidos. </p>
				<p>Este sistema trabaja de forma modulizada, es decir, que los contenidos a administrar son m&oacute;dulos independientes y f&aacute;cilmente configurables, los cuales se pueden adquirir de forma separada o por paquetes. Adem&aacute;s, se desarrollan m&oacute;dulos a medida, seg&uacute;n sus requerimientos o necesidades. </p>
				<p>Puede ser utilizado como soporte para aquellas personas que no posean conocimientos en programaci&oacute;n o dise&ntilde;o de p&aacute;ginas web, o no desean depender de forma permanente de un dise&ntilde;ador para realizar las actualizaciones. Tambi&eacute;n esta orientado para aquellas peque&ntilde;as o medianas empresas que desean la promoci&oacute;n de sus productos o servicios a trav&eacute;s de internet.</p>
				<p>XPANEL env&iacute;a a los administradores del sistema, mensajes de aviso sobre los eventos ocurridos en su sitio, de esta forma el administrador estar&aacute; al tanto, en cualquier momento, de que es lo que realizan los visitantes o usuarios en su sitio. El sistema permite obtener reportes peri&oacute;dicos (por d&iacute;a, semana, mes y a&ntilde;o) de las actividades de usuarios y visitantes.</p>
				<p>Actualmente se posee dos versiones en el mercado:</p>
				<ul>
					<li><strong>XPANEL Individual</strong>: incluye el sitio web y su &aacute;rea de administraci&oacute;n. Es ideal para sitios personales, peque&ntilde;os o medianas empresas u organizaciones p&uacute;blicas o privadas.</li>
					<li><strong>XPANEL Corporativo</strong>: incluye el sitio web, el cual permite la administraci&oacute;n de sub-sitios relacionados, y su &aacute;rea de administraci&oacute;n. Es ideal para aquellas empresas u organizaciones que poseen sedes o sucursales, ubicadas en zonas geogr&aacute;ficas alejadas, que tienen informaci&oacute;n y actividades relacionadas, pero que requieren una administraci&oacute;n de forma centralizada.</li>
				</ul>
			</div>
			<div class="tab-pane" id="s02">
				<h3>Como comenzar a trabajar con XPANEL?</h3>
				<p>Una vez que el sistema esta instalado en un servidor web, se debe proceder a:</p>
				<h5>1.	Registrar usuarios</h5>
				<p>XPANEL permite la registraci&oacute;n de n cantidad de usuarios, esto significa que no existe limite alguno. Estos pueden ser de tipo Administrador, Responsable del Sitio, Operador y Visitante. Se pueden agregar otros tipos de usuarios, pero esto depende de las caracteristicas de la actividad a la que se dedique el cliente que adquiere el sistema.</p>
				<p>Todos los usuarios deben tener asignado, como minimo Nombre y Apellido completo, Direcci&oacute;n de Correo Electr&oacute;nico, Tipo y Nombre de usuario, y una Contrase&ntilde;a de acceso.</p>
				<p>Cada uno de los usuarios pueden ser habilitados o deshabilitados en su acceso al sistema, a traves de la opci&oacute;n "Usuario Activo".</p>
				<h5>2.	Otorgar privilegios de acceso</h5>
				<p>Una vez que el sistema posee usuarios registrados, se debe otorgar los privilegios de acceso a XPANEL, para la administracion del sitio web y sus contenidos, a traves del modulo Privilegios. Este mostrara una listado de todos los usuarios registrados.</p>
				<p>Para otorgar privilegios de acceso, se debe EDITAR el registro o usuarios deseado. A continuaci&oacute;n, se desplegara un listado con todos los modulos disponibles, ordenados por grupos, y con cuatro columnas a su lado: VER, INSERTAR, EDITAR y ELIMINAR. Estas columnas representan las acciones que pueden realizar los usuarios en el modulo seleccionado.</p>
				<p>Se recomienda no otorgar acceso al modulo Privilegios, y solo destinar para aquellos usuarios administradores del sistema unicamente, a fin de evitar futuros inconvenientes que puedan ocasionar usuarios con malas intenciones.</p>
			</div>
			<div class="tab-pane" id="s03">
				<h3>Como acceder a los M&oacute;dulos?</h3>
				<p>Los m&oacute;dulos adquiridos se muestran en una lista desplegable, ubicada debajo del logo de XPANEL. El mismo los muestra ordenados por grupos.</p>
				<p>Dependiendo de los privilegios de acceso que posea el usuario, se podr&aacute;n visualizar algunos o todos seg&uacute;n el privilegio VER que se le otorgue.</p>
			</div>
			<div class="tab-pane" id="s04">
				<h3>Como es el entorno de trabajo de cada M&oacute;dulo?</h3>
				<p>Todos los m&oacute;dulos posean una similitud en su entorno de trabajo, de forma tal que sea m&aacute;s amigable su forma de operaci&oacute;n.</p>
				<p>Cada m&oacute;dulo posee los siguientes elementos:</p>
				<h5>1.	Opciones:</h5>
				<p>Estas representan las acciones que puede realizar un usuario con la lista de registros. Las acciones m&aacute;s comunes son:</p>
				<ul>
					<li>NUEVO: permite insertar nuevos registros.</li>
					<li>VER: permite visualizar los datos de un registro seleccionado.</li>
					<li>EDITAR: permite visualizar y actualizar los datos de un registro seleccionado.</li>
					<li>ELIMINAR: permite borrar los datos de un registro seleccionado.</li>
				</ul>
				<p>Dependiendo de los privilegios de acceso que posea el usuario al m&oacute;dulo, podr&aacute; visualizarlos o no. Pero como m&iacute;nimo la opci&oacute;n VER estar&aacute; disponible.</p>
				<p>Para poder seleccionar un registro se debe hacer clic sobre la fila o en la casilla de verificaci&oacute;n.  Para poder seleccionar todos los registros de la p&aacute;gina actual, se debe hacer clic en la casilla de verificaci&oacute;n de la primera columna.</p>
				<p>Tanto la opci&oacute;n VER como EDITAR son acciones individuales, es decir, permiten trabajar con un solo registro a la vez, sin embargo la opci&oacute;n ELIMINAR es una acci&oacute;n grupal, es decir, que permite trabajar con varios registros a la vez.</p>
				<h5>2.	Cantidad de registros: </h5>
				<p>Permite visualizar la cantidad de registros disponibles para visualizar y el total de registros almacenados. Este elemento cambiara seg&uacute;n el criterio de ordenamiento aplicado a los registros. </p>
				<h5>3.	Paginador: </h5>
				<p>Permite desplazarse por cada una de las p&aacute;ginas de registros disponibles. Cada m&oacute;dulo desplegara trece (13) registros por p&aacute;gina, a fin de poder visualizar y trabajar de una manera m&aacute;s c&oacute;moda.</p>
				<p>Este elemento muestra el n&uacute;mero de p&aacute;gina actual y el n&uacute;mero total de p&aacute;ginas. Adem&aacute;s muestra opciones que permiten desplazarse a la primera, anterior, siguiente y &uacute;ltima p&aacute;gina.</p>
				<h5>4.	Buscador: </h5>
				<p>Todos los m&oacute;dulos permiten localizar registros de una manera m&aacute;s r&aacute;pida. Solo debe seleccionar de una lista desplegable el dato por el cual desea buscar, luego introducir parte del texto. </p>
				<p>Dependiendo la cantidad de datos solicitados en cada m&oacute;dulo y las caracter&iacute;sticas de los mismos, los datos pueden aparecer ordenados en grupos.</p>
				<h5>5.	Ordenamiento de registros: </h5>
				<p>Todos los m&oacute;dulos tienen un ordenamiento predeterminado, que es la primera columna. Pero se puede dar otros tipos de ordenamientos, haciendo clic en el t&iacute;tulo de columna del dato deseado.</p>
				<p>Todos los ordenamientos, en esta versi&oacute;n de XPANEL, son en forma ascendente.</p>
				<p>Una vez aplicado un ordenamiento, la cantidad de registros y el paginador cambiaran.</p>
				<h5>6.	Lista de registros: </h5>
				<p>Est&aacute; organizada en filas, llamadas registros, y cada una de ellas muestra los datos m&aacute;s importantes de cada m&oacute;dulo, adem&aacute;s, de una casilla de verificaci&oacute;n para poder seleccionar el registro.</p>
				<p>Cada fila tiene, como &uacute;ltima columna, el s&iacute;mbolo cardinal (#) que representa la cantidad de registros repetidos con el mismo tipo de ordenamiento aplicado.</p>
			</div>
			<div class="tab-pane" id="s05">
				<h3>Como insertar nuevos datos?</h3>
				<p>Una vez accedido al m&oacute;dulo con el que se desea trabajar, se debe elegir la opci&oacute;n NUEVO. Luego de esto, se desplegara un formulario, donde se debe ingresar los datos. Existen diversos tipos de campos de formularios a completar:</p>
				<h5>Campos de texto:</h5>
				<p>Permite ingresar n&uacute;meros o letras. Dependiendo del dato que se trate, permite ingresar hasta 255 letras o n&uacute;meros.</p>

				<h5>Listas desplegables: </h5>
				<p>Permite elegir un &uacute;nico valor de una lista predefinida de valores.</p>

				<h5>Grupo de opciones:</h5>
				<p>Permite elegir una &uacute;nica opci&oacute;n, de un grupo de opciones.</p>

				<h5>Casillas de verificaci&oacute;n: </h5>
				<p>Permite elegir varias opciones, de un grupo de opciones.</p>

				<h5>Campos de archivo: </h5>
				<p>Permite la selecci&oacute;n de un archivo. Este elemente se caracteriza por mostrar un cuadro de texto donde aparece el nombre del archivo seleccionado y un bot&oacute;n Examinar que permite navegar por las carpetas y archivos de la computadora del usuario. </p>
				<p>Este elemento aparecer&aacute; cuando se permite insertar im&aacute;genes en el contenido de un &aacute;rea de texto o adjuntar archivos a un registro.</p>
				<p>En algunos casos, se muestra el bot&oacute;n Agregar otro archivo, el cual permite la selecci&oacute;n de varios archivos a insertar:</p>
				<p>A medida que se van agregando campos de archivo, tambi&eacute;n aparece el bot&oacute;n Eliminar, el cual permite deshacer la selecci&oacute;n del archivo seleccionado.</p>

				<h5>&Aacute;reas de texto: </h5>
				<p>permite introducir gran cantidad de texto, con la ventaja de permitir aplicar formatos y estilos a los textos, as&iacute; como insertar im&aacute;genes o enlaces.</p>
				<p>Si al momento de aplicar formatos a los textos en el campo &aacute;rea de texto, esta es muy chica o estrecha, se puede acceder a una ventana para trabajar de una manera m&aacute;s c&oacute;moda, con solo hacer clic en el bot&oacute;n Ampliar Editor. Una vez terminado, se puede hacer clic en el bot&oacute;n Minimizar Editor, a fin de volver al formulario de carga de datos.</p>

				<h5>Campos de fecha: </h5>
				<p>Permiten introducir fechas, en el formato dd/mm/yyyy. En el lado derecho de este elemento, tiene un bot&oacute;n que permite desplegar un calendario din&aacute;mico, que permite desplazarse entre los meses y a&ntilde;os.</p>
				<p>Luego de haber seleccionado la fecha deseada, solo se debe hacer clic en el bot&oacute;n Aceptar, en caso de desear cerrar la ventana, hacer clic en el bot&oacute;n Cancelar.</p>

				<p>&nbsp;</p>
				<p>Una vez ingresado los datos en el formulario, se debe hacer clic en el bot&oacute;n AGREGAR. En caso de querer deshacer la acci&oacute;n seleccionada, hacer clic en el bot&oacute;n CANCELAR.</p>
				<p>Todos los m&oacute;dulos solicitan datos obligatorios, estos se indican con un mensaje en una ventana emergente. </p>
				<p>Si no se completan estos datos, el registro no podr&aacute; insertarse en la base de datos.</p>

			</div>
			<div class="tab-pane" id="s06">
				<h3>Como puedo insertar im&aacute;genes en un &aacute;rea de texto?</h3>
				<p>Si debajo de un &aacute;rea de texto, tiene la leyenda "Im&aacute;genes en el contenido", a continuaci&oacute;n contendr&aacute; los siguientes elementos para proceder a insertar im&aacute;genes:</p>
				<p>- Agregar otro archivo: este bot&oacute;n le permite habilitar n cantidad de campos de archivo para insertar im&aacute;genes.</p>
				<p>- Campos de archivo: estos elementos les permitir&aacute;n seleccionar las im&aacute;genes deseadas.</p>
				<p>- Eliminar: cada vez que se habilitan campos de archivo, este bot&oacute;n permite eliminarlos.</p>
				<p>Existen una serie de restricciones a cumplir, para la inserci&oacute;n de im&aacute;genes en los elementos de &aacute;rea de texto:</p>
				<ul>
					<li>pueden ser de formato JPG, PNG o GIF.</li>
					<li>no deben superar los 8 MBytes de tama&ntilde;o.</li>
					<li>en el lugar donde se desee que aparezca cada una de las im&aacute;genes seleccionadas, se debe colocar el siguiente indicador: [IMAGEN01], donde el n&uacute;mero (de dos d&iacute;gitos) representa el orden de las im&aacute;genes seleccionadas.</li>
				</ul>
				<p>Una caracter&iacute;stica distintiva y destacable de XPANEL, es que el sistema modifica el tama&ntilde;o de las im&aacute;genes insertadas en el contenido, de forma proporcional (200 pixeles de ancho  x 200 pixeles de alto), al momento de visualizarlas. </p>
				<p>Este accionar se debe a que se busca que los contenidos con im&aacute;genes no distorsionen el dise&ntilde;o del sitio web, m&aacute;s cuando las im&aacute;genes tienen tama&ntilde;os o resoluciones muy grandes. Y adem&aacute;s, reducir el tiempo utilizado en la carga de informaci&oacute;n.</p>
				<p>En el mejor de los casos, sistemas similares sugieren resoluciones de im&aacute;genes a insertar en los contenidos, sumando, al usuario administrador, tiempo adicional para adecuar la imagen a la resoluci&oacute;n recomendada o el aprender a utilizar alg&uacute;n programa de retoque fotogr&aacute;fico para hacerlo. </p>
				<p>En el peor de los casos, algunos sistemas similares no sugieren ni recomiendan resoluciones, sino que el usuario administrador o el visitante del sitio, notaran los contenidos desproporcionados y se ver&aacute;n en la necesidad de dar soluci&oacute;n, por si solos, al problema que se plantee, seg&uacute;n sea su situaci&oacute;n.</p>
				<p>Otra caracter&iacute;stica destacable y relacionada, es que el sistema coloca un enlace, desde la imagen modificada, a la imagen original, a fin de poder visualizarla en una nueva ventana del navegador.</p>
			</div>
			<div class="tab-pane" id="s07">
				<h3>Como puedo eliminar im&aacute;genes en un &aacute;rea de texto?</h3>
				<p>Primero deber&aacute; EDITAR el registro que contenga las im&aacute;genes que desea eliminar del &aacute;rea de texto.</p>
				<p>Una vez que est&aacute; visualizando los datos, debajo del &aacute;rea de texto habr&aacute; una lista numerada con las im&aacute;genes insertadas:</p>
				<p>-	el primer enlace, muestra el nombre del archivo asignado por el sistema y permite visualizar la imagen en una ventana nueva.</p>
				<p>-	el segundo enlace, permite eliminar la imagen.</p>
				<p>Luego de eliminar la imagen deseada, tambi&eacute;n se debe eliminar el indicador de posici&oacute;n de la imagen, y actualizar la numeraci&oacute;n de los restantes indicadores.</p>
			</div>
			<div class="tab-pane" id="s08">
				<h3>Como puedo adjuntar archivos a un registro?</h3>
				<p>Todos los formularios que permite adjuntar archivos a los registros, tienen la leyenda "Archivos adjuntos", y a continuaci&oacute;n contendr&aacute; los siguientes elementos:</p>
				<p>-	Agregar otro archivo: este bot&oacute;n le permite habilitar n cantidad de campos de archivo para adjuntar archivos.</p>
				<p>-	Campos de archivo: estos elementos les permitir&aacute;n seleccionar los archivos deseados.</p>
				<p>-	Eliminar: cada vez que se habilitan campos de archivo, este bot&oacute;n permite eliminarlos.</p>
				<p>La &uacute;nica restricci&oacute;n que deben cumplir cada uno de los archivos a adjuntar a un registro, es que no deben superar los 8 MBytes de tama&ntilde;o.</p>
			</div>
			<div class="tab-pane" id="s09">
				<h3>Como puedo eliminar archivos adjuntos a un registro?</h3>
				<p>Primero deber&aacute; EDITAR el registro que contenga los archivos adjuntos que desea eliminar.</p>
				<p>Una vez que est&aacute; visualizando los datos, en el &aacute;rea Archivos Adjuntos habr&aacute; una lista numerada con los archivos subidos:</p>
				<ul>
					<li>el primer enlace, muestra el nombre del archivo asignado por el sistema y permite visualizar o descargar el archivo en una ventana nueva.</li>
					<li>el segundo enlace, permite eliminar el archivo.</li>
				</ul>
			</div>
			<div class="tab-pane" id="s10">
				<h3>Como deshabilitar registros?</h3>
				<p>En algunas ocasiones puede ser necesario que no se quiera eliminar un registro, sino que solamente queremos que no est&aacute; visible o accesible desde nuestro sitio web. Para ello, muchos formularios poseen el campo "Activo", con las opciones SI y NO, el cual permite habilitar y deshabilitar registros respectivamente.</p>
				<p>Esta opci&oacute;n estar&aacute; disponible al momento de agregar y editar un registro, siempre y cuando se posean los privilegios de acceso para ello.</p>
				<p>Esta opci&oacute;n es ideal en los siguientes casos:</p>
				<ul>
					<li>Cuando se desea deshabilitar el acceso a un usuario.</li>
					<li>Cuando se desea agregar nuevos registros al sistema, pero que los mismos no est&aacute;n disponibles por el momento.</li>
					<li>Cuando se posee registros o contenidos del sitio que no se desea est&aacute;n visibles o accesibles por los visitantes.</li>
				</ul>
			</div>
		</div>
		<div class="form-actions">
		</div>

		<p>&nbsp;</p>
		<?php include("../head.php"); ?>
	</body>
</html>
