<?php
 header('Access-Control-Allow-Origin: *');
 require_once('includes/load.php');
 
  $tama = $_GET['p_tama'];
  $extra = $_GET['p_extra'];
  if ($extra=='embutidos') {
    $extra='salami';
  }

  $precio=buscar_preciosextra_table($tama,$extra);
  foreach ($precio as $ggg){ echo remove_junk($ggg['price']); }
?>
  