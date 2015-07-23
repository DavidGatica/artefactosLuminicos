<!doctype html>
<html>

	<script>
		function imprimir() {

			var objeto = document.getElementById('imprimeme');  //obtenemos el objeto a imprimir
			var ventana = window.open('', '_blank');  //abrimos una ventana vacía nueva
			ventana.document.write(objeto.innerHTML);  //imprimimos el HTML del objeto en la nueva ventana
			ventana.document.close();  //cerramos el documento
			ventana.print();  //imprimimos la ventana
			ventana.close();  //cerramos la ventana
		}
	</script>
	
	<script src="jquery-1.6.2.min.js"></script>
	<script src="jquery-ui-1.8.15.custom.min.js"></script>
    <script type="text/javascript">
       $(function() {
               $("#caja").datepicker({ dateFormat: "yy-mm-dd" }).val()
			   $("#caja1").datepicker({ dateFormat: "yy-mm-dd" }).val()
       });
	   
   </script>
	
	<?php
		
		//------    CONVERTIR NUMEROS A LETRAS         ---------------
		//------    Máxima cifra soportada: 18 dígitos con 2 decimales
		//------    999,999,999,999,999,999.99
		// NOVECIENTOS NOVENTA Y NUEVE MIL NOVECIENTOS NOVENTA Y NUEVE BILLONES
		// NOVECIENTOS NOVENTA Y NUEVE MIL NOVECIENTOS NOVENTA Y NUEVE MILLONES
		// NOVECIENTOS NOVENTA Y NUEVE MIL NOVECIENTOS NOVENTA Y NUEVE PESOS 99/100 M.N.
		//------    Creada por:                        ---------------
		//------             ULTIMINIO RAMOS GALÁN     ---------------
		//------            uramos@gmail.com           ---------------
		//------    10 de junio de 2009. México, D.F.  ---------------
		//------    PHP Version 4.3.1 o mayores (aunque podría funcionar en versiones anteriores, tendrías que probar)
		function numtoletras($xcifra)
		{
			$xarray = array(0 => "Cero",
				1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
				"DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
				"VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
				100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
			);
		//
			$xcifra = trim($xcifra);
			$xlength = strlen($xcifra);
			$xpos_punto = strpos($xcifra, ".");
			$xaux_int = $xcifra;
			$xdecimales = "00";
			if (!($xpos_punto === false)) {
				if ($xpos_punto == 0) {
					$xcifra = "0" . $xcifra;
					$xpos_punto = strpos($xcifra, ".");
				}
				$xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
				$xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
			}

			$XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
			$xcadena = "";
			for ($xz = 0; $xz < 3; $xz++) {
				$xaux = substr($XAUX, $xz * 6, 6);
				$xi = 0;
				$xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
				$xexit = true; // bandera para controlar el ciclo del While
				while ($xexit) {
					if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
						break; // termina el ciclo
					}

					$x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
					$xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
					for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
						switch ($xy) {
							case 1: // checa las centenas
								if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
									
								} else {
									$key = (int) substr($xaux, 0, 3);
									if (TRUE === array_key_exists($key, $xarray)){  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
										$xseek = $xarray[$key];
										$xsub = subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
										if (substr($xaux, 0, 3) == 100)
											$xcadena = " " . $xcadena . " CIEN " . $xsub;
										else
											$xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
										$xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
									}
									else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
										$key = (int) substr($xaux, 0, 1) * 100;
										$xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
										$xcadena = " " . $xcadena . " " . $xseek;
									} // ENDIF ($xseek)
								} // ENDIF (substr($xaux, 0, 3) < 100)
								break;
							case 2: // checa las decenas (con la misma lógica que las centenas)
								if (substr($xaux, 1, 2) < 10) {
									
								} else {
									$key = (int) substr($xaux, 1, 2);
									if (TRUE === array_key_exists($key, $xarray)) {
										$xseek = $xarray[$key];
										$xsub = subfijo($xaux);
										if (substr($xaux, 1, 2) == 20)
											$xcadena = " " . $xcadena . " VEINTE " . $xsub;
										else
											$xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
										$xy = 3;
									}
									else {
										$key = (int) substr($xaux, 1, 1) * 10;
										$xseek = $xarray[$key];
										if (20 == substr($xaux, 1, 1) * 10)
											$xcadena = " " . $xcadena . " " . $xseek;
										else
											$xcadena = " " . $xcadena . " " . $xseek . " Y ";
									} // ENDIF ($xseek)
								} // ENDIF (substr($xaux, 1, 2) < 10)
								break;
							case 3: // checa las unidades
								if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada
									
								} else {
									$key = (int) substr($xaux, 2, 1);
									$xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
									$xsub = subfijo($xaux);
									$xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
								} // ENDIF (substr($xaux, 2, 1) < 1)
								break;
						} // END SWITCH
					} // END FOR
					$xi = $xi + 3;
				} // ENDDO

				if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
					$xcadena.= " DE";

				if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
					$xcadena.= " DE";

				// ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
				if (trim($xaux) != "") {
					switch ($xz) {
						case 0:
							if (trim(substr($XAUX, $xz * 6, 6)) == "1")
								$xcadena.= "UN BILLON ";
							else
								$xcadena.= " BILLONES ";
							break;
						case 1:
							if (trim(substr($XAUX, $xz * 6, 6)) == "1")
								$xcadena.= "UN MILLON ";
							else
								$xcadena.= " MILLONES ";
							break;
						case 2:
							if ($xcifra < 1) {
								$xcadena = "CERO PESOS $xdecimales/100 M.N.";
							}
							if ($xcifra >= 1 && $xcifra < 2) {
								$xcadena = "UN PESO $xdecimales/100 M.N. ";
							}
							if ($xcifra >= 2) {
								$xcadena.= " PESOS $xdecimales/100 M.N. "; //
							}
							break;
					} // endswitch ($xz)
				} // ENDIF (trim($xaux) != "")
				// ------------------      en este caso, para México se usa esta leyenda     ----------------
				$xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
				$xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
				$xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
				$xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
				$xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
				$xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
				$xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
			} // ENDFOR ($xz)
			return trim($xcadena);
		}

		// END FUNCTION

		function subfijo($xx)
		{ // esta función regresa un subfijo para la cifra
			$xx = trim($xx);
			$xstrlen = strlen($xx);
			if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
				$xsub = "";
			//
			if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
				$xsub = "MIL";
			//
			return $xsub;
		}

		// END FUNCTION

		
		
        session_start();

        if (!isset($_SESSION['usuario'])) {
            header('Location: log_in.php');
        }
        $id_usuario = $_SESSION['usuario'];

//incluimos el archivo con las funciones
        include ("funciones_mysql.php");

//Funcion que conecta la base de datos
        $conexion = conectar();
		
		$sql = "SELECT id_orden_venta FROM Orden_Venta ORDER BY id_orden_venta DESC LIMIT 1";
		$resultado = query($sql, $conexion);
		$campo = mysql_fetch_row($resultado);
		
		$id_orden_venta = $campo[0] + 1;

		if ($id_orden_venta == "") 
		{
			$id_orden_venta = 1;
		}

        $id_cotizacion = $_GET['id_cotizacion'];

        $sql = "SELECT* FROM Cotizaciones WHERE id_cotizacion = '$id_cotizacion'";
        $resultado = query($sql, $conexion);
        $campo = mysql_fetch_array($resultado);

        $fecha = $campo['fecha'];		        
        $id_usuario = $campo['id_usuario'];
        $vigencia = $campo['vigencia'];
        $no_partidas = $campo['no_partidas'];
        $divisa = $campo['divisa'];
        $subtotal = $campo['subtotal'];
        $iva = $campo['iva'];
        $total = $campo['total'];
        $t_entrega = $campo['t_entrega'];
        $c_pago = $campo['c_pago'];
        $descuento = $campo['descuento'];
        $descuento2 = $descuento * 100;
        $sub = $subtotal;
		$id_cliente = $campo['id_cliente'];
        $subtotal1 = $subtotal * $descuento;
        $subtotal2 = $subtotal - $subtotal1;

        $sql = "SELECT * FROM `Clientes` WHERE `id_num_cliente`='$id_cliente'";
        $resultado = query($sql, $conexion);
        $campo = mysql_fetch_array($resultado);
		$rfc=$campo['id_cliente'];
        $id_direccion = $campo['id_direccion'];
		$id_num_cliente = $campo['id_num_cliente'];

        $sql = "SELECT * FROM Datos_Cotizacion WHERE `id_cotizacion`='$id_cotizacion'";
        $resultado = query($sql, $conexion);
        $campo = mysql_fetch_array($resultado);
        $cliente = $campo['datos_cliente'];
        $contacto = $campo['datos_contacto'];
        $vendedor = $campo['datos_vendedor'];



        $sql = "SELECT * FROM `Clientes` WHERE `id_num_cliente`='$id_num_cliente'";
        $resultado = query($sql, $conexion);
        $campo = mysql_fetch_array($resultado);
        $id_contacto = $campo['id_contacto'];
        $empresa = $campo['empresa'];
		


        $sql = "SELECT * FROM Contacto WHERE id_contacto='$id_contacto'";
        $resultado = query($sql, $conexion);
        $campo = mysql_fetch_array($resultado);

        $nombre_c = $campo['nombre_c'];
        $departamento = $campo['departamento'];
        $telefono1 = $campo['telefono1'];
        $telefono2 = $campo['telefono2'];
        $e_mail_c = $campo['e_mail_c'];


        $sql = "SELECT * FROM Usuarios WHERE id_usuario='$id_usuario'";
        $resultado = query($sql, $conexion);
        $campo = mysql_fetch_array($resultado);

        $nombre = $campo['nombre'];
        $apellido_p = $campo['apellido_p'];
        $apellido_m = $campo['apellido_m'];
		$vendedor = "$nombre"." "."$apellido_p"." "."$apellido_m";
        $e_mail = $campo['e_mail'];


        $nombre = "$nombre " . "$apellido_p " . "$apellido_m";


        $sql = "SELECT * FROM Direcciones WHERE id_direccion=$id_direccion";
        $resultado = query($sql, $conexion);
        $campo = mysql_fetch_array($resultado);

        $calle = $campo['calle'];
        $num_int = $campo['num_int'];
        $num_ext = $campo['num_ext'];
		$direccion = "$calle"." "."$num_int"." "."$num_ext";
        $municipio = $campo['municipio'];
        $estado = $campo['estado'];
        $cp = $campo['cp'];
		$colonia = $campo['colonia'];

//INICIA LA PAGINA WEB
	?>

<head>
    <title>Orden de venta</title>

    <meta charset="utf-8" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
	<link href="st_orden.css" rel="stylesheet" type="text/css" />
	<link href="jqueryCalendar.css" rel="stylesheet" type="text/css" />
    
<div id="imprimeme">
	
</head>

<body onLoad="document.forma1.cantidad.focus();">

	<div id="contenido">

		<div id="encabezado">
		
			<div id="logo">
			
				<img src="images/alsa_principal.png" alt="Logo de la empresa">
			
			</div>
			
			<div id="nombre_empresa">
			
				ARTEFACTOS LUMÍNICOS S.A. DE C.V.
			
			</div>
			
		</div>	
		
		<div id="datos_grales">
		
			<div id="datos_cliente">
			
				<div id="datos_clientei">
					
					<div class="datos_clienteii">
					
						<h6>Cliente: </h6>
						<h6>Dirección: </h6>
						<h6>Colonia: </h6>
						<h6>Del/Mun: </h6>
						<h6>Teléfono: </h6>
						<h6>At'n: </h6>
			

				
					</div>
					<form method="GET" action="orden_base_datos.php">
					<div class="datos_clientedd">
					
						<div class="datos">
							<input class="textinput1" type="text" value="<?php echo $empresa; ?>"><br />
							<input class="textinput1" type="text" value="<?php echo $direccion; ?>"><br />
							<input class="textinput1" type="text" value="<?php echo $colonia; ?>"><br />
							<input class="textinput1" type="text" value="<?php echo $municipio; ?>"><br />
							<input class="textinput1" type="text" value="<?php echo "$telefono1"." "."$telefono2"; ?>"><br />
							<input class="textinput1" type="text" value="<?php echo $nombre_c; ?>"><br />
						</div>
					
					</div>
				
				</div>
				
				<div id="datos_cliented">
				
					<div class="datos_clienteii">
					
						<h6>C.P.: </h6>				
						<h6>Pedido cliente: </h6>
						<h6>R.F.C.: </h6>
						
					</div>
					
					<div class="datos_clientedd">
					
						<div class="datos">
							<input class="textinput2" type="text" value="<?php echo $cp; ?>"><br />
							<input class="textinput2" type="text" name="pedido_cliente"><br />							
							<input class="textinput2" type="text" value="<?php echo $rfc; ?>">
						</div>
					
					</div>
				
				</div>			
				
			</div>
			
			<div id="orden_venta">
					
					<h6>Orden de venta No.</h6>
					<input class="textinput3" type="text" value="<?php echo $id_orden_venta;?>" name="id_orden_venta">
					
			</div>
			
			<div id="fecha">
			
				<div id="fechai">
				
					<h6>Fecha: </h6>
					<h6>Entrega: </h6>
				
				</div>
				
				<div id="fechad" class="datos">
				
					
					<input class="textinput4" id="caja" type="text" name="fecha_o"  name="fecha_o"><br />
					<input class="textinput4" id="caja1" type="text" name="fecha_oe" name="fecha_eo">
				
				</div>
			
			</div>
			
			<div id="c_pago">
			
				<h6>Condiciones de pago</h6>
				<textarea class="areatext"><?php echo $c_pago; ?></textarea>
				
			</div>
			

			
			
			
			<div id="lugar_entrega">
			
				<h6>Lugar de entrega:</h6>
				<textarea class="bigareatext" name="lugar_entrega"></textarea>
			
			</div>
			
			<div id="rep_ventas" class="center">
			
				<h6>Representante de ventas:</h6>
					<input type="text" class="textinput center" value="<?php echo $vendedor; ?>">
			</div>
		
		</div>

		
		
		
								<div class="break"></div>
								
								
		<br />
		<div class="partidas">
		
			<table>
			
				<tr>
				
					<td class="uno">PDA.</th>
					<td class="dos">CANT.</th>
					<td class="tres">UNID.</th>
					<td class="cuatro">CATALOGO</th>
					<td class="cinco">DESCRIPCION</th>
					<td class="seis">P.U.</th>
					<td class="siete">P.T.</th>
				
				</tr>
				
				<?php
//Obtener "tabla Partidas"
                                $sql = "SELECT `partida`,`cantidad`,`unidad`,`catalogo`,`descripcion`,`precio_uni`,`precio_total` FROM `Partidas` WHERE `id_cotizacion` = '$id_cotizacion'";
                                $resultado = query($sql, $conexion);
                                while ($campo = mysql_fetch_array($resultado)) {
                                    $precio_uni = $campo['precio_uni'];
                                    $precio_total = $campo['precio_total'];
                                    $precio_uni = number_format($precio_uni, 2);
                                    $precio_total = number_format($precio_total, 2);

                                    echo
                                    "<tr>" .
                                    "<td>" . $campo['partida'] . "</td>";

                                    if ($campo['cantidad'] == 0) {
                                        echo
                                        "<td> </td>";
                                    } else {
                                        echo
                                        "<td>" . $campo['cantidad'] . "</td>";
                                    }

                                    echo
                                    "<td>" . $campo['unidad'] . "</td>" .
                                    "<td>" . $campo['catalogo'] . "</td>" .
                                    "<td>" . $campo['descripcion'] . "</td>";

                                    if ($campo['precio_uni'] == 0) {
                                        echo
                                        "<td> </td>" .
                                        "<td> </td>";
                                    } else {
                                        echo
                                        "<td>" . $precio_uni . "</td>" .
                                        "<td>" . $precio_total . "</td>";
                                    }
                                    "</tr>";
                                }
                                ?>



                                <tr>
			
			</table>
			
		</div>
		
		<br />
		<br />
		
		<hr />
			
								<div class="break"></div>
		
		<br />
		<br />
		
		<div id="datos_bottom">		
		
			<div id="notas">
				<h6>Notas:</h6> 
				<textarea class="bigareatext3" name="nota_o">Se esntregara enhuacalado y embalzamado para su mayor protección del producto</textarea>
			</div>
			
			<div id="totales">
			
				<div id="totalesi">
				
					<h5>SUBTOTAL:</h5>
					
					<br />
					
					<h5>16% I.V.A.:</h5>
					
					<br />
					
					<br />
					
					<h5>TOTAL:</h5>
				 
				</div>			
				
				<div id="totalesd">
				
					<?php echo '<h5>' . $subtotal .'</h5>';?>
					
					<br />
					
					<?php echo '<h5>' . $iva .'</h5>';?>
					
					<br /> <hr style="float: right;"/>
					
					<br />					
					
					
					<form name="forma1" action="<?php echo $_SERVER['PHP_SELF']."?id_cotizacion=".$id_cotizacion; ?>" method="post">
				<input  type="text" name="cantidad" value="<?php echo isset($_POST['cantidad']) ? $_POST['cantidad'] : $total; ?>" maxlength="21" class="caja_total" />  
				
				<br /><input id="flechita" type="submit" name="boton1" value="">
					
				</div>
		
			</div>
			
					<div id="imp_letra">
		
			<div id="importe"><h6>Importe con letra:</h6></div>
			<textarea class="bigareatext2" name="importe_letra"><?php echo isset($_POST['cantidad']) ? numtoletras($_POST['cantidad']) : ''; ?></textarea>
			</form>
			
			
			
		
		</div>
		
		</div>


		


						<div class="break"></div>

		<div id="ger_ven">
			<h5>Gerencia de ventas</h5>
			<hr/>
			<br />
				<select>
					<option value=""></option>
					<option value="Marco Villar">Marco Villar</option>
				</select>
		</div>						

		<div id="ger_op">
			<h5>Gerencia Operativa</h5>
			<hr/>
			<br />
				<select>
					<option value=""></option>
					<option value="Adriana Villar">Adriana Villar</option>
				</select>
		</div>
		
		<div id="asis_ven">
			<h5>Asistente de ventas</h5>
			<hr/>
			<br />
				<select>
					<option value=""></option>
					<option value="Mariela Aguilar">Mariela Aguilar</option>
				</select>
		</div>
		
		<div id="cre_cob">
			<h5>Crédito y Cobranza</h5>
			<hr/>
			<br />
				<select>
					<option value=""></option>
					<option value="Ana María Villar">Ana María Villar</option>
				</select>
		</div>
		
						<div class="break"></div>
		

	</div>
	
</div>
	<div class="centrar">
	<a href="administracion.php?sec=cotizaciones" id="regresar">Regresar</a>
	<input type="submit" value="Crear" id="crear" >
	</form>
	</div>
	

</body>



</html>