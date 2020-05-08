<?php
  $page_title = 'PIZZAS PARA ESCUELAS';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  //page_require_level(1);
  $c_user= count_by_id('users');
?>
<?php
 
 if(isset($_POST['add_escuelas'])){
   $req_fields = array('masas-usadas','precio-total','cajas_grandes','cajas_peque' );
   validate_fields($req_fields);
   if(empty($errors)){
    $p_masasEscuelas  = remove_junk($db->escape($_POST['masas-usadas']));
    $p_totalEscuelas  = remove_junk($db->escape($_POST['precio-total']));
    $p_cajaGEscuelas  = remove_junk($db->escape($_POST['cajas_grandes'])); 
    $p_cajaPEscuelas   = remove_junk($db->escape($_POST['cajas_peque']));

    $date    = make_date();
    //Agregar usuarui
    $user = current_user();
    $aux = remove_junk(ucwords($user['username']));
    //Ingresar en base de Datos
    $query  = "INSERT INTO venta_escuelas (";
    $query .=" qty_masas,price,cajaGrande,cajaPequena,date,user";
    $query .=") VALUES (";
    $query .=" '{$p_masasEscuelas}', '{$p_totalEscuelas}','{$p_cajaGEscuelas}', '{$p_cajaPEscuelas}','{$date}', '{$aux}'";
    $query .=")";
    if($db->query($query)){
      //Actualizar valores APROXIMADOS
      //Leer actual y sumer nuevo (masas y cajas)
      $query0 = "UPDATE products SET ";        //Insertar la BD en la memoria de usuario
      $query0 .=" qtyAproximada = qtyAproximada+'{$p_masasEscuelas}' WHERE name =";
      $query0 .=" 'Masas'";

      if($db->query($query0)){
        //Cajas Medianas
        $query1 = "UPDATE products SET ";        //Insertar la BD en la memoria de usuario
        $query1 .=" qtyAproximada = qtyAproximada+'{$p_cajaGEscuelas}' WHERE name =";
        $query1 .=" 'CajasGrandes';";
        if($db->query($query1)){
          //Cajas Grandes
          $query2 = "UPDATE products SET ";        //Insertar la BD en la memoria de usuario
          $query2.=" qtyAproximada = qtyAproximada+'{$p_cajaPEscuelas}' WHERE name =";
          $query2.=" 'CajasMedianas';";
          
          if($db->query($query2)){
            $session->msg('s',"Pizzas escuela agregadas exitosamente. "); 
          } 
          else {
            $session->msg('d',' Lo siento, registro falló.');
            redirect('add_escuelas.php', false);
          }
        }
      } 
    }
   } 
   else{
    $session->msg("d", $errors);
    redirect('add_escuelas.php',false);
   }

 }
 else if(isset($_POST['regresar'])) redirect('add_escuelas.php',false);

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
  <div class="col-md-9">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Agregar Pizzas para escuelas</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_escuelas.php" class="clearfix">
              <!-- Masas utiliadas-->
              <div class="form-group">
                <div class="row">
                  <div class="col-md-4">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-record"></i>
                      </span>
                      <input type="number" step="1"  min="0" pattern="[0-9]" autocomplete="off" class="form-control"  id="masas-usadas" name="masas-usadas" placeholder="# Masas Usadas" onchange="actu_valor()">
                    </div>
                  </div>
                </div>
              </div>

              <!-- Cantidad y precio -->
              <div class="form-group">
               <div class="row">
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                    </span>
                    <input type="number" class="form-control" id="num-porciones" name="num-porciones" autocomplete="off" min="0" step="1" pattern="^\d+(?:\.\d{1,2})?$" placeholder="# Porciones obtenidas" onchange="actu_valor()">
                  </div>
                 </div>
                 <div class="col-md-4">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-paperclip"></i>
                      </span>
                      <input type="number" step="0.01"  min="0" pattern="^\d+(?:\.\d{1,2})?$" autocomplete="off" class="form-control" id="precio-porcion" name="precio-porcion" placeholder="Precio por porcion" onchange="actu_valor()">
                    </div>
                  </div>
                </div>
              </div>
              <!-- TOTAL-->
              <div class="form-group">
                <div class="row">
                  <div class="col-md-4">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-usd"></i>
                      </span>
                      <input type="number" step="0.01"  min="0" pattern="^\d+(?:\.\d{1,2})?$" autocomplete="off" class="form-control" id="precio-total" name="precio-total" placeholder="Total venta escuelas" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Verrificacion de uso de cajas -->
              <div class="custom-control custom-checkbox my-1 mr-sm-2">
                <input type="checkbox" class="custom-control-input" id="customControlInline" onchange="f_cajas()">
                <label class="custom-control-label" for="customControlInline">  SE USO CAJAS PARA ESTA ENTREGA</label>
              </div>


              <!-- Opcines de cajas -->
              <div id="cajas-usadas" class="form-group" style="margin-top: 3%; display: none;">
                <div class="row">
                  <!-- cajas grandes -->
                  <div class="col-md-4">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-th-large"></i>
                      </span>
                      <input type="number" class="form-control" id="cajas_grandes" name="cajas_grandes" autocomplete="off" min="0" step="1" pattern="^\d+(?:\.\d{1,2})?$" placeholder="# Cajas Grandes" value="0">
                    </div>
                  </div>
                  <!-- cajas medianas -->
                  <div class="col-md-4">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-th"></i>
                      </span>
                      <input type="number" class="form-control" id="cajas_peque" name="cajas_peque" autocomplete="off" min="0" step="1" pattern="^\d+(?:\.\d{1,2})?$" placeholder="# Cajas Medianas" value="0">
                    </div>
                  </div>
                </div>
              </div>

              <button type="submit" name="add_escuelas" class="btn btn-success" style="margin-top: 3%;">Cargar valores a inventario</button>
              <button type="submit" name="regresar" class="btn btn-danger" style="margin-top: 3%;">Cancelar</button>

          </form>
         </div>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>

<script>
  //Globaizacion de variables
  var grupo_cajas = document.getElementById("cajas-usadas"); 
  var check_cajas= document.getElementById("customControlInline");
  var masa_usadas=document.getElementById("masas-usadas");
  var porc_obt=document.getElementById("num-porciones");
  var porc_precio=document.getElementById("precio-porcion");
  var total_escuelas=document.getElementById("precio-total");
  //Cajas
  var cajas_grandes=document.getElementById("cajas_grandes");
  var cajas_peque=document.getElementById("cajas_peque");

  function f_cajas() {
    
    if (check_cajas.checked == true) {
      grupo_cajas.style.display = 'flex';
    }
    else{
      grupo_cajas.style.display = 'none';
      cajas_grandes.value=0;
      cajas_peque.value=0;
    }
  }

  function actu_valor(){
    //Delimitar los valores a solo enteros
    masa_usadas.value=parseInt(masa_usadas.value);
    porc_obt.value=parseInt(porc_obt.value);
  
    if(masa_usadas.value!=0 && porc_obt.value!=0 && porc_precio.value!=0)
      total_escuelas.value= (masa_usadas.value*porc_obt.value*porc_precio.value).toFixed(2);
  }

  function deli_cajas(){
    //Delimitar los valores a solo enteros
    cajas_grandes.valuee=parseInt(cajas_grandes.value);
    cajas_peque.value=parseInt(cajas_peque.value);
  }

</script>
