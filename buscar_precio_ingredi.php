<?php
  header('Access-Control-Allow-Origin: *');
  require_once('includes/load.php');
 
  $nombre = $_GET['p_nombre'];

  $precio=buscar_preciosingredientes_table($nombre);
  foreach ($precio as $ggg){ echo remove_junk($ggg['price']); }
?>
  