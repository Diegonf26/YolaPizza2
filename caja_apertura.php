<?php
  $page_title = 'Apertura de caja';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>

<?php
$user = current_user();
if(isset($_POST['abrir_caja'])){
   $req_fields = array('dinero');
   validate_fields($req_fields);
   if(empty($errors)){
    $p_dinero = remove_junk($db->escape($_POST['dinero']));

     $aux = remove_junk(ucwords($user['username'])); 
     $caja_anterior = remove_junk(ucwords($user['caja_manana'])); 
     $p_id = remove_junk(ucwords($user['id']));
     $p_bloqueo = true;

     if ($caja_anterior!=$p_dinero) {
       $mensaje="La caja ha sido abierta con un valor diferente";
       $caja_igual=false;
     }
     else {
      $mensaje="Caja Abierta sin novedad";
      $caja_igual=true;
     }

     $diferencia_caja=$caja_anterior-$p_dinero;

     $p_date    = make_date();

     $query  = "INSERT INTO tabla_aperturas_cajas (";        //Insertar la BD en donde se va a ingresar los datos
     $query .=" dinero_apertura,caja_igual,caja_diferencia,username,date";
     $query .=") VALUES (";
     $query .=" '{$p_dinero}', '{$caja_igual}', '{$diferencia_caja}','{$aux}', '{$p_date}'";
     $query .="); ";


    if($db->query($query)){
      if ($caja_igual==true) {
        $session->msg('s',$mensaje);
      }
      else {
        $session->msg('w',$mensaje);
      }
      $query2 = "UPDATE users SET ";        //Insertar la BD en la memoria de usuario
      $query2 .=" bloqueocaja = true WHERE id =";
      $query2 .=" '{$p_id}' ;";
      if($db->query($query2)) redirect('admin.php', false);
      else $session->msg('d',' Lo siento, registro memoria.');        
    } else {
      $session->msg('d',' Lo siento, registro falló.');
      redirect('caja_apertura.php', false);         //Regresar a administrar productos a vender
    }

   } else{
     $session->msg("d", $errors);
     redirect('caja_apertura.php',false);
   }
}
if(isset($_POST['no_abrir'])) redirect('admin.php', false);
else{
  if($user['bloqueocaja']==true){
    $session->msg("d", 'La caja se encuentra abierta, cierrela primero!');
    redirect('admin.php', false); //ojo depende de q menu este user, admin o special no todos van a admin
    exit();
  }
} 
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
            <span>Apertura de caja</span>
         </strong>
        </div>

        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="caja_apertura.php" class="clearfix">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <output type="text" class="form-control" name="fecha" placeholder="Descripción">
                    <?php 
                        date_default_timezone_set('America/Bogota'); $fecha= date("d/m/Y"); echo $fecha; //$hora= date("d/m/Y  g:i a"); 
                    ?>
                  </output>
               </div>
              </div>

              <div class="form-group">
               <div class="row">
                  <div class="col-md-4">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-usd"></i>
                      </span>
                      <input id= "apertura" type="number" class="form-control" name="dinero" placeholder="Valor de apertura" step="0.01"  min="0" pattern="^\d+(?:\.\d{1,2})?$" autocomplete="off" style="width: 150px;">
                      <span class="input-group-addon">Caja anterior</span>
                      <span class="input-group-addon" type="number">
                        <?php
                          $user = current_user(); $caja_anterior = remove_junk(ucwords($user['caja_manana'])); echo $caja_anterior;
                        ?>
                      </span>
                   </div>
                  </div>
               </div>
              </div>
              <button type="submit" name="abrir_caja" class="btn btn-success">Aceptar</button>
              <button type="submit" name="no_abrir" class="btn btn-danger">Cancelar</button>            
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Billetes</span>
          </strong>
        </div>
          <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
              <thead>                                                             <!--Cabecera dentro de la tabla-->
                  <tr>
                      <th>Denominación</th><th class="text-center" style="width: 100px;">Cantidad</th>
                  </tr>
              </thead>
              <tbody> 
                <tr><td>$1</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction()"  pattern="\d*" value="0" min="0" id="un_d"type="number"></td></tr>
                <tr><td>$5</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction()"  pattern="\d*" value="0" min="0" id="cinco_d"type="number"></td></tr>
                <tr><td>$10</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction()"  pattern="\d*" value="0"  min="0" id="diez_d" type="number"></td></tr>
                <tr><td>$20</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction()"  pattern="\d*" value="0" min="0" id="veinte_d" type="number"></td></tr>
                <tr><td>$50</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction()"  pattern="\d*" value="0" min="0" id="cincuenta_d" type="number"></td></tr>
                <tr><td>$100</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction()"  pattern="\d*" value="0" min="0" id="cien_d" type="number"></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <strong>
              <span class="glyphicon glyphicon-th"></span>
              <span>Monedas</span>
            </strong>
          </div>
          <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
              <thead>                                                             <!--Cabecera dentro de la tabla-->
                  <tr>
                      <th>Denominación</th><th class="text-center" style="width: 100px;">Cantidad</th>
                  </tr>
              </thead>
              <tbody>
                <tr><td>1 ctv</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction()"  pattern="\d*" value="0" min="0" id="un_c" type="number"></td></tr>
                <tr><td>5 ctv</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction()"  pattern="\d*" value="0" min="0" id="cinco_c" type="number"></td></tr>
                <tr><td>10 ctv</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction()"  pattern="\d*" value="0" min="0" id="diez_c" type="number"></td></tr>
                <tr><td>25 ctv</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction()"  pattern="\d*" value="0"  min="0" id="veinte_c" type="number"></td></tr>
                <tr><td>50 ctv</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction()"  pattern="\d*" value="0" min="0" id="cincuenta_c"type="number"></td></tr>
                <tr><td>$1</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction()"  pattern="\d*" value="0" min="0" id="cien_c"type="number"></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>

<script>

function isInputNumber(evt){
    
    var ch = String.fromCharCode(evt.which);
    
    if(!(/[0-9]/.test(ch))){
        evt.preventDefault();
    }
    
}

function isInputNumber2(evt){
    
    var ch = String.fromCharCode(evt.which);
    
    if(!(/[0-9.]/.test(ch))){
        evt.preventDefault();
    }
    
}


function myFunction() {

  //dolares
  var cien = document.getElementById("cien_d").value;
  var cincuenta = document.getElementById("cincuenta_d").value;
  var veinte = document.getElementById("veinte_d").value;
  var diez = document.getElementById("diez_d").value;
  var cinco = document.getElementById("cinco_d").value;;
  var un = document.getElementById("un_d").value;

  var suma1= (100*cien)+(50*cincuenta)+(20*veinte)+(10*diez)+(5*cinco)+1*un;

  //centavos
  var cien = document.getElementById("cien_c").value;
  var cincuenta = document.getElementById("cincuenta_c").value;
  var veinte = document.getElementById("veinte_c").value;
  var diez = document.getElementById("diez_c").value;
  var cinco = document.getElementById("cinco_c").value;;
  var un = document.getElementById("un_c").value;

  var suma2= 1*cien+0.01*((50*cincuenta)+(25*veinte)+(10*diez)+(5*cinco)+1*un);
  var k=suma2+suma1;
  var t=k.toFixed(2);
  document.getElementById("apertura").value = t;

}

</script>

<?php 
include_once('layouts/footer.php'); 

?>
