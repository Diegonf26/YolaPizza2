<?php
  $page_title = 'Cierre de caja';
  require_once('includes/load.php');
  include "escpos-php/ImpresionCerrarCaja.php";
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>
<?php
 $year  = date('Y');
 $month = date('m');
 $day = date('d');


$user = current_user();
//Encontrar fecha
$open=find_last_open_box();
$ingresos_cajas = find_sum_ingresos_caja($year,$month,$day);
$descripcionIngresos=find_descr_ingresos_caja($year,$month,$day);
$retiros_cajas=find_sum_retiros_caja($year,$month,$day);
$descripcionRetiros=find_descr_retiros_caja($year,$month,$day);


$ventasRealizadas_e=0;
$ventasRealizadas_t=0;
$ventasAutoconsumo=0;
$ventasEscuelas=0;
//$efectivo=VentasRealizadas('2018-12-12 21:24:58','venta_general','efectivo');
$efectivo=VentasRealizadas($open['date'],'venta_general','efectivo');
foreach ($efectivo as $vB){
  $ventasRealizadas_e=$ventasRealizadas_e+(float)remove_junk($vB['price']);
}

$tarjeta=VentasRealizadas($open['date'],'venta_general','tarjeta');
foreach ($tarjeta as $vB){
  $ventasRealizadas_t=$ventasRealizadas_t+(float)remove_junk($vB['price']);
}

$autoconsumo=VentasRealizadas($open['date'],'venta_general','autoconsumo');
foreach ($autoconsumo as $vB){
  $ventasAutoconsumo=$ventasAutoconsumo+(float)remove_junk($vB['price']);
}

$escuelasV=VentasRealizadas_Es($open['date'],'venta_escuelas');
foreach ($escuelasV as $esc){
  $ventasEscuelas=$ventasEscuelas+(float)remove_junk($esc['price']);
}

foreach($ingresos_cajas as $tempo){
  $ingresos_caja=remove_junk(ucwords($tempo['SUM(c.importe)']));
}
if($ingresos_caja<=0){
  $ingresos_caja=0;
}

$retiros_caja=0;
foreach($retiros_cajas as $tempo2){
  $retiros_caja=remove_junk(ucwords($tempo2['SUM(c.importe)']));
}
if($retiros_caja>=0)$retiros_caja=0;
else $retiros_caja=-$retiros_caja;
 
//Descripcion de ingresos y egresos
$ingresoUnificado="";
$retiroUnificado="";
foreach($descripcionIngresos as $a){
  $ingresoUnificado=$ingresoUnificado."* ".remove_junk(ucwords($a['descripcion']))."\n";
}
foreach($descripcionRetiros as $b){
  $retiroUnificado=$retiroUnificado."* ".remove_junk(ucwords($b['descripcion']))."\n";
}

if(isset($_POST['cerrar_caja'])){
   if(empty($errors)){   
     $p_apertura_caja = remove_junk($db->escape($_POST['apertura_caja']));
     $p_cobros_efectivo = remove_junk($db->escape($_POST['cobros_efectivo']));
     $p_cobros_tarjeta = remove_junk($db->escape($_POST['cobros_tarjeta']));
     $p_total_ventas = remove_junk($db->escape($_POST['total_ventas']));
     $p_autoconsumo = remove_junk($db->escape($_POST['autoconsumo']));
     $p_escuelas = remove_junk($db->escape($_POST['escuelas']));
     $p_ingreso_ef_caja = remove_junk($db->escape($_POST['ingreso_ef_caja']));
     $p_retiro_ef_caja = remove_junk($db->escape($_POST['retiro_ef_caja']));
     $p_dinero_entregar = remove_junk($db->escape($_POST['dinero_entregar']));
     $p_dinero_entregado = remove_junk($db->escape($_POST['dinero_entregado']));
     $p_dinero_sobra = remove_junk($db->escape($_POST['dinero_sobra']));
     $p_caja_manana=remove_junk($db->escape($_POST['dinero_caja_total']));
     $p_date    = make_date();
     $p_user = remove_junk(ucwords($user['username']));

     //Funcion impresion de REPORTE CIERRE CAJA
     imprimirReportediario($p_apertura_caja,$p_cobros_efectivo,$p_cobros_tarjeta,$p_total_ventas,$p_autoconsumo,$p_escuelas,$p_ingreso_ef_caja,$p_retiro_ef_caja,$p_dinero_entregar,$p_dinero_entregado,$p_dinero_sobra,$p_caja_manana);

     $query  = "INSERT INTO tabla_cierres_cajas (";        //Insertar la BD en donde se va a ingresar los datos
     $query .=" dinero_apertura, cobros_en_caja, cobros_con_tarjeta, total_ventas, 	autoconsumo, escuelas,ingreso_efectivo_en_caja, retiro_efectivo_en_caja, dinero_a_entregar, dinero_entregado, saldo, caja_manana,	date, username, Descripcion_ingreso,Descripcion_retiro";
     $query .=") VALUES (";
     $query .=" '{$p_apertura_caja}','{$p_cobros_efectivo}','{$p_cobros_tarjeta}','{$p_total_ventas}','{$p_autoconsumo}','{$p_escuelas}','{$p_ingreso_ef_caja}','{$p_retiro_ef_caja}','{$p_dinero_entregar}','{$p_dinero_entregado}','{$p_dinero_sobra}','{$p_caja_manana}','{$p_date}','{$p_user}','{$ingresoUnificado}','{$retiroUnificado}'";
     $query .="); ";
     
    if($db->query($query)){
      //Guardas caja mananan
      $session->msg('s',"Caja cerrada correctamente");
      $p_id = remove_junk(ucwords($user['id']));
      $query2 = "UPDATE users SET ";        //Insertar la BD en la memoria de usuario
      $query2 .=" bloqueocaja = 0 ,";
      $query2 .=" caja_manana = '{$p_caja_manana}' WHERE id ="; //Insertar la caja manana en BD user
      $query2 .=" '{$p_id}';";
      if($db->query($query2)){
        delete_sum_retiros_caja($year,$month,$day);
        delete_sum_ingresos_caja($year,$month,$day);
        redirect('admin.php', false);
      } 
      else $session->msg('d',' Lo siento, registro memoria fallido.');        
    } 
    else {
      $session->msg('d',' Lo siento, registro falló.');
      redirect('caja_cierre.php', false);  
    }

   } else{
     $session->msg("d", $errors);
     redirect('caja_cierre.php',false);
   }
}
if(isset($_POST['no_cerrar'])){
  redirect('admin.php', false);
} 

else{
  if($user['bloqueocaja']==false){
    $session->msg("d", 'La caja se encuentra cerrada, abrala primero!');
    redirect('admin.php', false); //ojo depende de q menu este user, admin o special no todos van a admin
  }
} 
?>

<?php include_once('layouts/header.php');?>
<div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
</div>
<div class="row">
  <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Caja</span>
       </strong>
      </div>
      <div class="panel-body">
        <form method="post" class="clearfix" id="get_order_data">
          <table class="table table-bordered table-striped table-hover">
            <thead>                                                             <!--Cabecera dentro de la tabla-->
                <tr>
                    <th>Descripción</th><th class="text-center" style="width: 100px;">    Valor [$]    </th>
                </tr>
            </thead>
            <tbody>                                                              <!--Cuerpo dentro de la tabla-->
              <tr><td>Apertura de caja</td><td class="text-center" >
                <input readonly type="number" style="text-align:center" id="apertura_caja" name="apertura_caja" value='0' />
              </td></tr>
              <tr><td>Cobros en efectivo</td><td class="text-center ">
                <input readonly type="number" style="text-align:center" id="cobros_efectivo" name="cobros_efectivo" value='0' />
              </td></tr>
              <tr><td>Cobros con tarjeta</td><td class="text-center">
                <input readonly type="number" style="text-align:center" id="cobros_tarjeta" name="cobros_tarjeta" value='0'/>
              </td></tr>
              <tr><td>Escuelas</td><td class="text-center">
                <input type="number" readonly style="text-align:center"  id="escuelas"  name="escuelas" value="0"/>
              </td></tr>
              <tr><td style="background-color:#F48585; font-weight:bold;">Total vendido</td><td class="text-center">
                <input type="number" readonly style="text-align:center"   id="total_ventas" name="total_ventas" value=''  style="color:#0099ff"/>
              </td></tr>
              <td></td> <!--Espacio-->
              <tr><td>Autoconsumo</td><td class="text-center">
                <input type="number" readonly style="text-align:center"  id="autoconsumo"  name="autoconsumo" value="0"/>
              </td></tr>
              <tr><td>Ingreso de efectivo en caja</td><td class="text-center">
                <input readonly type="number" style="text-align:center; color: #2D8EFF; text-decoration: underline;" id="ingreso_ef_caja" name="ingreso_ef_caja" title="<?php echo $ingresoUnificado; ?>" value='0'>
              </td></tr>
              <tr><td>Retiro de efectivo en caja</td><td class="text-center">
                <input readonly type="number" style=" text-align:center; color: #06500C; text-decoration: underline;" id="retiro_ef_caja" name="retiro_ef_caja" value='0' title="<?php echo $retiroUnificado; ?>">
              </td></tr>
              <tr><td style="background-color:#A8CFDF; font-weight:bold;">Dinero a entregar</td><td class="text-center">
                <input type="number" readonly style="text-align:center;"  id="dinero_entregar" name="dinero_entregar"/>
              </td></tr>
              <tr><td  style="color:#0099ff; text-align:left; font-weight:bold;">Dinero entregado</td><td class="text-center" >
                <input type="number" style="text-align:center;"  id="dinero_entregado" name="dinero_entregado" step="0.01"  min="0" pattern="^\d+(?:\.\d{1,2})?$" autocomplete="off"  onchange="myFunction(1)"/>
              </td></tr>
              <tr id="color_saldo" style="text-align:center;"><td id="dinero_sobra_txt">a</td><td class="text-center">
                <input type="number" readonly style="text-align:center"  id="dinero_sobra" name="dinero_sobra"/>
              </td></tr>

              <td></td> <!--Espacio-->

              <!--DINERO CAJA MANANA-->
              <tr><td  style="background-color:#C56AF0">Caja manana - Monedas</td><td class="text-center" >
                <input readonly type="number" style="text-align:center; background-color: #D9B6EA;"  id="dinero_caja" name="dinero_caja" step="0.01"  min="0" pattern="^\d+(?:\.\d{1,2})?$" autocomplete="off"/>
              </td></tr>
              <tr><td  style="background-color:#D7A8AC">Caja manana - Billetes </td><td class="text-center" >
                <input type="number" style="text-align:center;"  id="dinero_caja_billetes" name="dinero_caja_billetes" step="0.01"  min="0" pattern="^\d+(?:\.\d{1,2})?$" autocomplete="off" value='0.00'onchange="actu_caja()"/>
              </td></tr>
              <tr><td  style="color:#C56AF0;font-weight:bold;">Total Caja Manana </td><td class="text-center" >
                <input readonly type="number" style="text-align:center;"  id="dinero_caja_total" name="dinero_caja_total" step="0.01"  min="0" pattern="^\d+(?:\.\d{1,2})?$" autocomplete="off" value='0.00' />
              </td></tr>
              
            </tbody>
          </table>
          <button type="submit"  name="cerrar_caja" class="btn btn-success" id="cerradura">Cerrar Caja</button>
          <button type="submit" name="no_cerrar" class="btn btn-danger">Cancelar</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-5">
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
            <tr><td>$1</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction(0)"  pattern="\d*" value="0" min="0" id="un_d"type="number"></td></tr>
            <tr><td>$5</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction(0)"  pattern="\d*" value="0" min="0" id="cinco_d"type="number"></td></tr>
            <tr><td>$10</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction(0)"  pattern="\d*" value="0"  min="0" id="diez_d" type="number"></td></tr>
            <tr><td>$20</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction(0)"  pattern="\d*" value="0" min="0" id="veinte_d" type="number"></td></tr>
            <tr><td>$50</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction(0)"  pattern="\d*" value="0" min="0" id="cincuenta_d" type="number"></td></tr>
            <tr><td>$100</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction(0)"  pattern="\d*" value="0" min="0" id="cien_d" type="number"></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

    <div class="col-md-5">
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
            <tr><td>1 ctv</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction(0)"  pattern="\d*" value="0" min="0" id="un_c" type="number"></td></tr>
            <tr><td>5 ctv</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction(0)"  pattern="\d*" value="0" min="0" id="cinco_c" type="number"></td></tr>
            <tr><td>10 ctv</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction(0)"  pattern="\d*" value="0" min="0" id="diez_c" type="number"></td></tr>
            <tr><td>25 ctv</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction(0)"  pattern="\d*" value="0"  min="0" id="veinte_c" type="number"></td></tr>
            <tr><td>50 ctv</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction(0)"  pattern="\d*" value="0" min="0" id="cincuenta_c"type="number"></td></tr>
            <tr><td>$1</td><td class="text-center"><input onkeypress="isInputNumber(event)" style="width: 100px;" onchange="myFunction(0)"  pattern="\d*" value="0" min="0" id="cien_c"type="number"></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<script>

  $(document).ready(function(){
    var d_apertura =Number("<?php echo remove_junk(ucwords($open['dinero_apertura']));?>");

    var d_ing_ef_caja=Number("<?php echo $ingresos_caja;?>");
    var d_ret_ef_caja = Number("<?php echo $retiros_caja;?>");
    var cobros_efe = Number("<?php echo $ventasRealizadas_e;?>");
    var cobros_tar = Number("<?php echo $ventasRealizadas_t;?>");
    var autocon = Number("<?php echo $ventasAutoconsumo;?>")
    var vent_escuelas=Number("<?php echo $ventasEscuelas;?>")
    //----------------------------
    document.getElementById("retiro_ef_caja").value=d_ret_ef_caja.toFixed(2);
   
    document.getElementById("ingreso_ef_caja").value=d_ing_ef_caja.toFixed(2);
    document.getElementById("autoconsumo").value=autocon.toFixed(2);
    document.getElementById("escuelas").value=vent_escuelas.toFixed(2);
    document.getElementById("apertura_caja").value=d_apertura.toFixed(2);
    document.getElementById("cobros_efectivo").value=cobros_efe.toFixed(2);
    document.getElementById("cobros_tarjeta").value=cobros_tar.toFixed(2);

    ///--------
    var caja_igual=Number("<?php echo remove_junk(ucwords($open['caja_igual']));?>");
    if (caja_igual==0){
      document.getElementById("apertura_caja").style.backgroundColor = "#E6A7A6"; 
      document.getElementById("apertura_caja").title="<?php echo remove_junk(ucwords($open['caja_diferencia']));?>";
    }
    myFunction();
  });

	$("#cerradura").click(function(){
    var user = "<?php echo $user['username']; ?>";
    var date = "<?php echo make_date(); ?>";
    var d = new Date();
    var date1=d.getFullYear().toString()+"_"+d.getMonth().toString()+"_"+d.getDate().toString()+"_"+d.getHours().toString()+"_"+d.getMinutes().toString();
    
    var data = $("#get_order_data").serialize(); 
    //var win = window.open("caja_c_reporte.php?"+data+"&"+"user="+user+"&"+"date1="+date1+"&"+"date="+date,"_blank"); // will open new tab on document ready
    //win.focus();
	});

  function isInputNumber(evt){
      
      var ch = String.fromCharCode(evt.which);
      
      if(!(/[0-9]/.test(ch))){
          evt.preventDefault();
      }
      
  }
  
  function myFunction(aux) {
    var color_saldo = document.getElementById("color_saldo");

    var d_apertura = Number(document.getElementById("apertura_caja").value);
    var d_cobro_ef = Number(document.getElementById("cobros_efectivo").value);
    var d_cobro_tar = Number(document.getElementById("cobros_tarjeta").value);
    var d_total_v = document.getElementById("total_ventas");
    var d_autoconsumo = Number(document.getElementById("autoconsumo").value);
    var d_ing_ef_caja = Number(document.getElementById("ingreso_ef_caja").value);
    var d_ret_ef_caja = Number(document.getElementById("retiro_ef_caja").value);
    var d_escuelas = Number(document.getElementById("escuelas").value);
    var d_entregado_valor = Number(document.getElementById("dinero_entregado").value); //Dinero ingresado directamente en cuadro DINERO ENTREADO
    var d_caja_valor = Number(document.getElementById("dinero_caja").value); //Dinero ingresado directamente en cuadro CAJA

    var d_entregado = document.getElementById("dinero_entregado");
    var d_entregar = document.getElementById("dinero_entregar");
    var d_sobra_txt = document.getElementById("dinero_sobra_txt");
    var d_sobra = document.getElementById("dinero_sobra");
    var d_caja = document.getElementById("dinero_caja");

    //VENTAS
    var s1=d_cobro_ef+d_cobro_tar;
    d_total_v.value=s1.toFixed(2);

    //DINERO SACADO
    var s2= d_ing_ef_caja-d_ret_ef_caja;

    //dolares
    var cien = Number(document.getElementById("cien_d").value);
    var cincuenta = Number(document.getElementById("cincuenta_d").value);
    var veinte = Number(document.getElementById("veinte_d").value);
    var diez = Number(document.getElementById("diez_d").value);
    var cinco = Number(document.getElementById("cinco_d").value);
    var un = document.getElementById("un_d").value;
    if(!isInt(cien)||!isInt(cincuenta)||!isInt(veinte)||!isInt(diez)||!isInt(cinco)||!isInt(un)){
      alert("Sólo se aceptan números enteros"); return;
    } 
    var suma1= (100*cien)+(50*cincuenta)+(20*veinte)+(10*diez)+(5*cinco)+1*un; //BILLETES

    //centavos
    var cien = document.getElementById("cien_c").value;
    var cincuenta = document.getElementById("cincuenta_c").value;
    var veinte = document.getElementById("veinte_c").value;
    var diez = document.getElementById("diez_c").value;
    var cinco = document.getElementById("cinco_c").value;
    var un = document.getElementById("un_c").value;
    if(!isInt(cien)||!isInt(cincuenta)||!isInt(veinte)||!isInt(diez)||!isInt(cinco)||!isInt(un)){
      alert("Sólo se aceptan números enteros"); return;
    } 
    var suma2= 1*cien+0.01*((50*cincuenta)+(25*veinte)+(10*diez)+(5*cinco)+1*un); //MONEDAS

    var k=suma2+suma1; //TOTAL CAJA
    var t=k.toFixed(2); //Ajuste a dos decimales
    var valor_final_d_entregado=0;

    if(aux==0){
      //Cunado se cambia valores de lista
      d_entregado.value= t;
      valor_final_d_entregado=t;
      d_caja.value=suma2.toFixed(2);
    }
    else {
      //Cuando se cambia valor directo en cuadro
      d_entregado.value = d_entregado_valor.toFixed(2);
      valor_final_d_entregado=d_entregado_valor;
      d_caja.value=d_caja_valor.toFixed(2);
    }
      

    var total=s1+s2-d_cobro_tar+d_apertura+d_escuelas;
    var tempo=parseFloat(total)
    d_entregar.value = tempo.toFixed(2);

    var sobra_dinero=(valor_final_d_entregado-tempo).toFixed(2);

    if(sobra_dinero<0){
      d_sobra_txt.style.backgroundColor = "#ff9933";
      d_sobra_txt.innerHTML = "Falta dinero en caja";
    } 
    else{
      d_sobra_txt.style.backgroundColor = "#66ff66";
      d_sobra_txt.innerHTML = "Sobra dinero en caja";
    } 

    d_sobra.value = sobra_dinero;

    actu_caja();  

  }

  function isInt(n) {
    return n % 1 == 0;
  }

  function actu_caja(){
    var caja_billetes=Number(document.getElementById("dinero_caja_billetes").value);
    var caja_monedas=Number(document.getElementById("dinero_caja").value);
    ///---Prsenacion de elemetos
    document.getElementById("dinero_caja_total").value=(caja_billetes+caja_monedas).toFixed(2)
    document.getElementById("dinero_caja_billetes").value=caja_billetes.toFixed(2)
  }

</script>

<?php include_once('layouts/footer.php'); ?>
