<?php
  header('Access-Control-Allow-Origin: *');
  require_once('includes/load.php');
 
  $nombre = $_GET['p_nombre'];

  $precio=buscar_preciosotros_table($nombre, "catalogo_ensaladas");
  foreach ($precio as $ggg){ echo remove_junk($ggg['price']); }
?>
  