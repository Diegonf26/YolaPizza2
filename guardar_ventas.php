<?php
  require_once('includes/load.php');
 
  $cantidad=$_GET['p_canti'];
  $tama = $_GET['p_tama'];
  $tipo = $_GET['p_tipo'];
  $sabor = $_GET['p_sabor'];
  $forma = $_GET['p_forma'];
  $extras = $_GET['p_extras'];
  $precio = $_GET['p_precio'];
  $pago = $_GET['p_pago'];
  $usuario = $_GET['p_usuario'];

  $date    = make_date();
  $query  = "INSERT INTO venta_pizzas (";        //Insertar la BD en donde se va a ingresar los datos
  $query .=" qty,tam_pizza,tipo_pizza,sabor_pizza,llevar_pizza,extras,price,forma_pago,date,user";
  $query .=") VALUES (";
  $query .=" '{$cantidad}', '{$tama}', '{$tipo}', '{$sabor}', '{$forma}', '{$extras}', '{$precio}', '{$pago}', '{$date}', '{$usuario}'";
  $query .=")";
  $db->query($query)
?>
  