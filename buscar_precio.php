<?php
  header('Access-Control-Allow-Origin: *');
  require_once('includes/load.php');
  // $dbhost = "localhost";
  // $dbuser = "visor";
  // $dbpass = "12345";
  // $dbname = "oswa_inv";

  // //Connect to MySQL Server
  // mysql_connect($dbhost, $dbuser, $dbpass);
  //   //Select Database
  // mysql_select_db($dbname) or die(mysql_error());
    // Retrieve data from Query String
  $tama = $_GET['p_tama'];
  $tipo = $_GET['p_tipo'];
  $sabor = $_GET['p_sabor'];

  // echo $tama,$tipo,$sabor;

  // $tama= mysql_real_escape_string($tama);
  // $tipo= mysql_real_escape_string($tipo);
  // $sabor= mysql_real_escape_string($sabor);

  // global $db;
  // $sql  ="SELECT p.price FROM catalogo_pizzas p WHERE p.size = '{$tama}' AND p.type = '{$tipo}' AND p.flavor = '{$sabor}' LIMIT 1";
  // $qry_result = mysql_query($query) 
  // $db->query($sql);

  // $sql  ="SELECT p.price FROM catalogo_pizzas p WHERE p.size = '{$tama}' AND p.type = '{$tipo}' AND p.flavor = '{$sabor}' LIMIT 1";
  // $qry_result = mysql_query($sql);

  $precio=buscar_precios_table($tama,$tipo,$sabor);
  foreach ($precio as $ggg){ echo ltrim(remove_junk($ggg['price'])); }

  // echo $precio;
  
  // function buscar_precios_table($tama,$tipo,$sabor){
  //   global $db;
  //   $sql  ="SELECT p.price FROM catalogo_pizzas p WHERE p.size = '{$tama}' AND p.type = '{$tipo}' AND p.flavor = '{$sabor}' LIMIT 1";
  //   $qry_result = mysql_query($query) 
  //  return $db->query($sql);
  // }
?>
  