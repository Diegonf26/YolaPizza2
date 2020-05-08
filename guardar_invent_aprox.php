<?php
  require_once('includes/load.php');
 
  $produto=$_GET['p_producto'];
  $cantidad = $_GET['p_cantidad'];

  $date    = make_date();
  $query = "UPDATE products SET ";        //Insertar la BD en la memoria de usuario
  $query .=" qtyAproximada = '{$cantidad}' WHERE name =";
  $query .=" '{$produto}' ;";
  
  $db->query($query)
?>
  