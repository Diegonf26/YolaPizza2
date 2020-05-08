<?php
  header('Access-Control-Allow-Origin: *');
  require_once('includes/load.php');
 
  $size = $_GET['p_size'];
  $flavor = $_GET['p_flavor'];

  $precio=buscar_preciosbebida_table($size,$flavor);
  foreach ($precio as $ggg){ echo remove_junk($ggg['price']); }
?>
  