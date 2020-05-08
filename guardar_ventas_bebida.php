<?php
  require_once('includes/load.php');
 
  $cantidad=$_GET['p_canti'];
  $tama = $_GET['p_tama'];
  $sabor = $_GET['p_sabor'];
  $precio = $_GET['p_precio'];
  $usuario = $_GET['p_usuario'];
  $forma = $_GET['p_forma'];

  $date    = make_date();
  $query  = "INSERT INTO venta_bebidas (";        //Insertar la BD en donde se va a ingresar los datos
  $query .=" qty,tam_bebida,sabor_bebida,price,date,user,forma_pago";
  $query .=") VALUES (";
  $query .=" '{$cantidad}', '{$tama}', '{$sabor}', '{$precio}', '{$date}', '{$usuario}', '{$forma}'";
  $query .=")";
  $db->query($query)
?>
  