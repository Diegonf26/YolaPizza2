<?php
  $page_title = 'Ingreso o Retiro de Efectivo';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>

<?php
$user = current_user();
if(isset($_POST['aceptar'])){
   $req_fields = array('dinero','clave_caja','descrip');
   validate_fields($req_fields);
  if(empty($errors)){
    $aux = remove_junk(ucwords($user['username']));
    $p_id = remove_junk(ucwords($user['id']));
    $p_date    = make_date();
    $p_dinero = remove_junk($db->escape($_POST['dinero']));
    $p_descrip = remove_junk($db->escape($_POST['descrip']))." =  $ ".$p_dinero;
    $option   = remove_junk($db->escape($_POST['selector']));
    $clave_caja   = remove_junk($db->escape($_POST['clave_caja']));
    if(authenticate_clave_caja($clave_caja)){
      if($option == 'Retiro de Efectivo en Caja') $p_dinero=-$p_dinero;
      $query  = "INSERT INTO tabla_ingresos_retiros_cajas (";        //Insertar la BD en donde se va a ingresar los datos
      $query .=" importe,date,username,descripcion";
      $query .=") VALUES (";
      $query .=" '{$p_dinero}', '{$p_date}', '{$aux}', '{$p_descrip}'";
      $query .="); ";
      if($db->query($query)){
        if($p_dinero<0)$session->msg('s',"Retiro de dinero exitoso");  
        else $session->msg('s',"Ingreso de dinero en caja exitoso");    
        redirect('escpos-php/hello_abrir_caja.php',false); 
      } 
      else {
        $session->msg('d',' Lo siento, registro falló.');
        redirect('caja_ingreso_retiro.php', false);         //Regresar a administrar productos a vender
      }
    }
    else{
      $session->msg('d',' Lo siento, contraseña incorrecta.');
      redirect('caja_ingreso_retiro.php',false);
    }

  } else{
    $session->msg('d','La contraseña no puede estar en blanco');
    redirect('caja_ingreso_retiro.php',false);
  }
}
if(isset($_POST['cancelar'])) redirect('admin.php', false);
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
          <span>Ingreso o Retiro de efectivo en caja</span>
        </strong>
      </div>

      <div class="panel-body">
        <form method="post" action="caja_ingreso_retiro.php" class="clearfix">
          <div class="form-group">
            <div class="col-md-4">

              <!-- Definicion de ingreso o retiro -->
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-sort"></i></span>
                <select class="form-control" name="selector" required>
                  <option>Ingreso de Efectivo en Caja</option>
                  <option>Retiro de Efectivo en Caja</option>
                </select>            
              </div>

              <!-- Valor -->
              <div class="input-group" style="margin-top: 4%;">
                <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                <input id= "ingres_retiro" type="number" class="form-control" name="dinero" placeholder="Valor"  step="0.01"  min="0" pattern="^\d+(?:\.\d{1,2})?$" autocomplete="off">
              </div>
                <!-- Descipcion -->
              <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                <input id= "in-ret_descripcion" type="text" class="form-control" name="descrip" placeholder="Descripción"  autocomplete="off">              
              </div>
              <!-- Contraseña -->
              <div class="input-group" style="margin-top: 4%;">
                <span class="input-group-addon"><i class="glyphicon glyphicon-th-large"></i></span>
                <input type="password" class="form-control" name ="clave_caja" placeholder="Contraseña">
              </div>
            </div>
          </div>

          <button type="submit" name="aceptar" class="btn btn-success">Aceptar</button>
          <button type="submit" name="cancelar" class="btn btn-danger">Cancelar</button>            
        </form>
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
  document.getElementById("ingres_retiro").value = t;

}

</script>

<?php 
include_once('layouts/footer.php'); 

?>
