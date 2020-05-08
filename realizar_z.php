<?php
  require_once('includes/load.php');

  if(isset($_GET['p_efect'])) 
  {
    $efectivo=$_GET['p_efect'];
    $vuelto=$_GET['p_vuelto'];
    $forma=$_GET['p_pago'];

    $cc = find_conta('contador');
    $contador;
    foreach($cc as $c){
      $contador=$c['conta'];
      $contador=$cc[0]['conta'];
    }

    // $contador++;
    // $query = "UPDATE contador SET ";        //Insertar la BD en la memoria de usuario
    // $query .=" conta = '{$contador}' WHERE id = 1;";
    // if($db->query($query)){}

    GuardarVentasGenerales($_GET["numorden"], $_GET["subtotal"], $_GET["p_efect"],$_GET["p_vuelto"],$_GET["date"], $_GET["user"], $_GET["p_pago"]);     
  }
  //es autoconsumo
  else{
    GuardarVentasGenerales('0', $_GET["subtotal"], '0','0',$_GET["date"], $_GET["user"],'autoconsumo');     
  }
  
  function  GuardarVentasGenerales($num, $price, $pagado,$vuelto,$date, $user, $fP){

     $query  = "INSERT INTO venta_general (";        //Insertar la BD en donde se va a ingresar los datos
     $query .=" orden,price,pagado,vuelto,date,user,forma_pago";
     $query .=") VALUES (";
     $query .=" '{$num}', '{$price}', '{$pagado}','{$vuelto}', '{$date}', '{$user}', '{$fP}'";
     $query .="); ";

    global $db;
    if($db->query($query)){
        //redirect('realizar_venta.php', false);         //Regresar a administrar productos a vender   
    } else {
      $session->msg('d',' Lo siento, registro falló.');
      //redirect('realizar_venta.php', false);         //Regresar a administrar productos a vender
    }

  }

?>