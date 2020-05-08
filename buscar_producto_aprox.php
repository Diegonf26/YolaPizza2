<?php
  header('Access-Control-Allow-Origin: *');
  require_once('includes/load.php');
 
  $nombre = $_GET['p_nombre'];
  $cantidad=buscar_productosaprox_table($nombre);
  foreach ($cantidad as $ggg){ echo remove_junk($ggg['qtyAproximada']); }
?>
  