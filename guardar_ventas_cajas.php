<?php
  require_once('includes/load.php');
 
  $cantidad=$_GET['p_canti'];
  $tama = $_GET['p_tama'];
  $precio = $_GET['p_precio'];
  $usuario = $_GET['p_usuario'];

  $date    = make_date();
  $query  = "INSERT INTO venta_cajas (";        //Insertar la BD en donde se va a ingresar los datos
  $query .=" qty,tama,price,date,user";
  $query .=") VALUES (";
  $query .=" '{$cantidad}', '{$tama}', '{$precio}', '{$date}', '{$usuario}'";
  $query .=")";
  $db->query($query)
?>
  