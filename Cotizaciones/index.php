<?php
@session_start();

if (!isset($_GET['op']))		/*--Si la variable $_GET['op'] no contiene nada entonces sera NULL--*/
	$op = NULL;

else		/*--Si no, entonces $op contendra lo que contenga la variable $_GET--*/
	$op = $_GET['op'];

include ("funciones_mysql.php");		//Obtiene recursos de archivo funciones_mysql.php

$conexion = conectar();		//Conecta con la base de datos

$sql = "SELECT * FROM `Version` WHERE `version_no` = 1";
$resultado = query($sql, $conexion);
$campo = mysql_fetch_array($resultado); 
$version = $campo['version'];

if (isset($_SESSION['usuario'])) 		//Si la variable $_SESSION['usuario'] contiene algo
{

$id_usuario = $_SESSION['usuario'];		//$id_usuario= lo que contenga $_SESSION

$query = "SELECT * FROM `Usuarios` WHERE `id_usuario`='$id_usuario' ";		//Query que obtiene el permiso y si está activo el usuario
$result = mysql_query($query);
while ($campo = mysql_fetch_array($result)) 
{
$permiso = $campo['permiso'];
$activo = $campo['activo'];
}

if ($permiso == '1' && $activo == '1') 		//Si su permiso es 1 y esta activo, direcciona a administracion.php
header("Location: administracion.php");


if ($permiso == '2' && $activo == '1')		//Si su permiso es 1 y esta activo, direcciona a ventas.php
header("Location: ventas.php");		
} 

else 		//Si no, permite el inicio de sesion
{

?>
<!DOCTYPE html>

<html>		<!--Inicia html-->

	
	<head>		<!--Inicia head-->
	
		<link rel="shortcut icon" type="image/png" href="images/icono.png" />		<!--Etiqueta link para obtener el favicon-->
		<link href="log_in.css" rel="stylesheet" type="text/css" />			<!--Etiqueta link para importar hoja de estilos-->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />		<!--Formato del código en utf-8-->
		
		<title>
			Iniciar sesión
		</title>
		
	</head>		<!--Termina head-->

	<body>		<!--Inicia body-->
	
		<div id="pagina">		<!--Inicia id pagina-->
		
			<div id="header">		<!--Inicia header-->
			
				<h1>
				Artefactos Lumínicos SA de CV
				</h1>

			</div>		<!--Termina header-->
			
			<div id="contenedor_izquierdo">			<!--Inicia contenedor_izquierdo-->
			
				<div id="intro_izquierda">			<!--Inicia intro_izquierda (este div muestra el logo de artefactos lumínicos)-->
				
					<div id="logo">
					
						<img src="images/index_alsa.png" alt="Logo Artefactos Lumínicos"/>
						
					</div>
				
				</div>			<!--Termina intro_izquierda-->
				
			</div>			<!--Termina contenedor_izquierdo-->
			
			<div id="contenedor_derecho">			<!--Inicia id contenedor_derecho-->
			
				<div id="intro_derecha" >		<!--Inicia id intro_derecha-->
				
					<br>
					<br>
					<h2>
						Consecutivo de cotizaciones
					</h2>
					<br>
					
						<form action="validar_usuario.php" method="POST">		<!--Inicia form para validar_usuario.php-->
						
							<br /><br />
							<input type="text" title="Ingrese un nombre de usuario existente." class="caja_ingreso" placeholder="Usuario" name="id_usuario" required/>		<!--Caja de texto para ingresar usuario-->							
							<br />
							<br />
							<input type="password"  title="Ingrese la contraseña." class="caja_ingreso" placeholder="Contraseña" name="password" required/>		<!--Caja de texto para ingresar contraseña-->
							<br />
							<br />
							<br />
							<input type="submit" value="Entrar" class="button">		<!--Boton para accesar al sistema-->
							
						</form>		<!--Termina form para validar_usuario.php-->


					<?php		/*--Inicia php--*/
					
					}		// Termina else
					
					?>		<!--Termina php-->

				</div>		<!--Termina id intro_derecha-->		
			
			</div>		<!--Termina id contenedor_derecho-->
		
			<div id="pie_izquierdo">			<!--Inicia pie_izquierdo-->
			
				<div id="logos">

				<a href="http://validator.w3.org/check?uri=http%3A%2F%2Fwww.artefactosluminicos.com.mx%2FCotizaciones_prueba%2FInicio%2Flog_in.php%3Fversion%3D1.6.0">				<!--Hipervínculo para logo de certificación de HTML5-->
					<img 
						src="http://www.w3.org/html/logo/badge/html5-badge-h-css3-performance.png"
						width="90" height="35" alt="HTML5 Powered with CSS3 / Styling, and Performance &amp; Integration" 
						title="HTML5 Powered with CSS3 / Styling, and Performance &amp; Integration"
					/>
				</a>
				
				<a href="http://jigsaw.w3.org/css-validator/check/referer">			<!--Hipervínculo para logo de certificación de CSS3-->
					<img 
						style="border:0;width:88px;height:31px"
						src="http://jigsaw.w3.org/css-validator/images/vcss"
						alt="¡CSS Válido!" 
					/>
				</a>	
				
				</div>
				
			</div>			<!--Termina pie_izquierdo-->

			<?php 		/*--Inicia php--*/
			
				if ($op == 'desactivado') 		//Si usuario no esta activo
				{ 
				
			?>		<!--Termina php-->
					<script>		/*--Inicia script--*/
						alert('Este usuario ha sido desactivado \nPongase en contacto el Administrador');
					</script>		<!--Termina script-->
				
			<?php		/*--Inicia php--*/
			
				}		//Termina if
				
			?>		<!--Termina php-->

			<?php 		/*--Inicia php--*/
			
				if ($op == 'mal') 		//Si $op devuelve 'mal'
				{ 
			
			?>		<!--Termina php-->
			
					<script>		/*--Inicia script--*/
						alert('ERROR\nUsuario y/o contraseña erroneos, intente de nuevo');
					</script>		<!--Termina script-->
				
			<?php		/*--Inicia php--*/
			
				}		//Termina if
			
			?>		<!--Termina php-->
			
			<div id="pie_derecho">		<!--Inicia id pie_derecho-->
			
				<div id="letraversion">		<!--Inicia id letraversion-->
				
					Versi&oacute;n <?php echo $version; ?>
					
				</div>		<!--Termina id letraversion-->
			
			</div>		<!--Termina id pie_derecho-->
			
		</div>		<!--Termina id pagina-->
			
	</body>		<!--Termina body-->
	
</html>		<!--Termina html-->
