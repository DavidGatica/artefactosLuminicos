<link href="tabla2.css" rel="stylesheet" type="text/css" />
<style type="text/css" media="screen">
    A:link {color: black; font-size: 8pt; font-family: arial; text-decoration: none }
    A:hover {color: black; font-size: 8pt; font-family: arial; text-decoration: none }
    A:visited {color: black; font-size: 8pt; font-family: arial; text-decoration: none }
</style>

<?php
//Capturamos el usuario autenticado



if (!isset($_SESSION['usuario'])) {
    header('Location: log_in.php');
}
$id_usuario = $_SESSION['usuario'];

if (!isset($_GET['opcion']))
    $opcion = "nada";
else
    $opcion = $_GET['opcion'];

if (!isset($_POST['rfc']))
    $rfc = "nada";
else
    $rfc = $_POST['rfc'];

if (!isset($_POST['empresa']))
    $empresa = "nada";
else
    $empresa = $_POST['empresa'];


$cont = 0;
//Funcion que conecta la base de datos
$conexion = conectar();

//Obtener Datos de la empresa a cambiar "tabla clientes"
$sql = "SELECT * FROM `Cotizaciones`";
$resultado = query($sql, $conexion);
while ($campo = mysql_fetch_array($resultado)) {
    $cont = 1;
}

if ($cont == 1) {
    ?>
    
    <div id="barra">

        <div class="CSSTableGenerator" >
            <table border=2 align="center">

                <tr>

                    <td width="10%">No</td>                        
                    <td width="10%">Fecha de orden</td>
                    <td width="10%">Fecha de entrega</td>
                    <td width="35%">Cliente</td>
                    <td width="25%">Vendedor</td>
                    <td width="10%" height="25px" colspan="3">Gesti&oacute;n</td>


                </tr>



                <?php
//Obtener Datos de la empresa a cambiar "tabla clientes"
                $sql = "SELECT * FROM `Orden_Venta` ORDER BY id_orden_venta DESC";
                $resultado = query($sql, $conexion);
                while ($campo = mysql_fetch_array($resultado)) {
                    $id_cotizacion = $campo['id_cotizacion'];
                    
					$fecha_o = $campo['fecha_o'];
					$fecha_eo = $campo['fecha_eo'];

                    echo "<tr>";


                    echo "<td align='center'>" . $campo['id_orden_venta'] . "</td>";

                    $sql3 = "SELECT id_usuario FROM `Cotizaciones` WHERE id_cotizacion = '$id_cotizacion'";
                    $resultado3 = query($sql3, $conexion);
                    $campo3 = mysql_fetch_array($resultado3);
                    $id_usuario = $campo3['id_usuario'];


                    $sql0 = "SELECT * FROM `Usuarios` WHERE id_usuario = '$id_usuario'";
                    $resultado0 = query($sql0, $conexion);
                    $campo0 = mysql_fetch_array($resultado0);
                    $vendedor = $campo0['nombre'] . ' ' . $campo0['apellido_p'];


                    echo "<td align='center'>" . $fecha_o . "</td>";
					
                    echo "<td align='center'>" . $fecha_eo . "</td>";



                    $id_num_cliente = $campo['id_num_cliente'];

					$sql5 = "SELECT * FROM Cotizaciones WHERE id_cotizacion = $id_cotizacion";
					$resultado5 = query($sql5, $conexion);
					$campo5 = mysql_fetch_array($resultado5);
                    $id_cliente = $campo5['id_cliente'];
					
                    $sql2 = "SELECT * FROM `Clientes` WHERE id_num_cliente = '$id_cliente'";
                    $resultado2 = query($sql2, $conexion);
                    $campo2 = mysql_fetch_array($resultado2);
                    $empresa = $campo2['empresa'];


                    echo "<td>" . $campo2['empresa'] . "</td>";

                    echo "<td>" . $vendedor . "</td>";

                    echo "<td height='35px'> "
                    . "<a href='ver_cotizacion.php?id_cotizacion=" . $id_cotizacion . "' ><div class='ver' align='center'>Ver</div></a></td><td height='35px'>"
                    . "<a href='editar_cotizacion.php?id_cotizacion=" . $id_cotizacion . "&empresa=". $empresa . "&cotiz_usuario=". $id_usuario ."' ><div class='editar' align='center'> Editar</div></a></td>";

                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </div>

<?php } ?>

<?php if ($cont == 0) { ?>
    <div id="errorimg">
        <img  src="images/error.png"></div>
<?php } ?>




