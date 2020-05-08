<?php
  require_once('includes/load.php');
 
  $cantidad=$_GET['p_canti'];
  $nombre = $_GET['p_nombre'];
  $precio = $_GET['p_precio'];
  $usuario = $_GET['p_usuario'];
  $forma = $_GET['p_forma'];

  $date    = make_date();
  $query  = "INSERT INTO venta_ingredientes (";        //Insertar la BD en donde se va a ingresar los datos
  $query .=" qty,nombre_ingre,price,date,user,forma_pago";
  $query .=") VALUES (";
  $query .=" '{$cantidad}', '{$nombre}', '{$precio}', '{$date}', '{$usuario}', '{$forma}'";
  $query .=")";
  $db->query($query)
?>
  