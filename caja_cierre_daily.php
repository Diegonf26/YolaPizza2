<?php
  $page_title = 'Reportes de cierres de caja';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  //$cierres = join_cierres_cajas();
?>
<?php
 $year  = date('Y');
 $month = date('m');
 $day = date('d');

 $cierres = daily_cierres_cajas($year,$month,$day);
?>

<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Reportes de cierres de caja del día (<?php echo  $year.'/'.$month.'/'.$day; ?>)</span>
          </strong>
        </div>
        <div class="panel-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th class="text-center" style="width: 15%;"> Fecha </th>
                <th class="text-center" style="width: 10%;"> Usuario </th>
                <th class="text-center" style="width: 10%;"> Monto de apertura </th>
                <th class="text-center" style="width: 10%;"> Cobros en efectivo</th>
                <th class="text-center" style="width: 10%;"> Cobros con tarjeta</th>
                <th class="text-center" style="width: 10%;"> Ingreso en caja</th>
                <th class="text-center" style="width: 10%;"> Retiro en caja</th>
                <th class="text-center" style="width: 10%;"> Monto entregado</th>
                <th class="text-center" style="width: 10%;"> Saldo </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($cierres as $cierre):?>
              <?php $desIngreso=remove_junk($cierre['Descripcion_ingreso']); ?>
              <?php $desRetiro=remove_junk($cierre['Descripcion_retiro']); ?>
              <tr>#06500C
                <td class="text-center"> <?php echo read_date($cierre['date']); ?></td>
                <td class="text-center"> <?php echo remove_junk($cierre['username']); ?></td>
                <td class="text-center" id="da<?php echo remove_junk($cierre['id']); ?>"> <?php echo remove_junk($cierre['dinero_apertura']); ?></td>
                <td class="text-center" id="cc<?php echo remove_junk($cierre['id']); ?>"> <?php echo remove_junk($cierre['cobros_en_caja']); ?></td>
                <td class="text-center" id="ct<?php echo remove_junk($cierre['id']); ?>"> <?php echo remove_junk($cierre['cobros_con_tarjeta']); ?></td>
                <td style="color: #2D8EFF; text-decoration: underline;" title="<?php echo $desIngreso;?>" class="text-center" id="ic<?php echo remove_junk($cierre['id']); ?>"><?php echo remove_junk($cierre['ingreso_efectivo_en_caja']); ?></td>
                <td style="color: #06500C; text-decoration: underline;" title="<?php echo $desRetiro;?>" class="text-center" id="rc<?php echo remove_junk($cierre['id']); ?>"> <?php echo remove_junk($cierre['retiro_efectivo_en_caja']); ?></td>
                <td class="text-center" id="de<?php echo remove_junk($cierre['id']); ?>"> <?php echo remove_junk($cierre['dinero_entregado']); ?></td>
                <td class="text-center" id="s<?php echo remove_junk($cierre['id']); ?>"> <?php echo remove_junk($cierre['saldo']); ?></td>
              </tr>
             <?php endforeach; ?>
            </tbody>
              <tr>
                <th class="text-center" style="width: 10%;" colspan="2"> Total </th>
                <th class="text-center" style="width: 10%;" id="dinero_apertura"> </th>
                <th class="text-center" style="width: 10%;" id="cobros_en_caja"> </th>
                <th class="text-center" style="width: 10%;" id="cobros_con_tarjeta"> </th>
                <th class="text-center" style="width: 10%;" id="ingreso_efectivo_en_caja"> </th>
                <th class="text-center" style="width: 10%;" id="retiro_efectivo_en_caja"> </th>
                <th class="text-center" style="width: 10%;" id="dinero_entregado"> </th>
                <th class="text-center" style="width: 10%;" id="saldo"> </th>
              </tr>
          </table>
        </div>
      </div>
    </div>
  </div>

<script>
myFunction();

  function myFunction() {

    var da = document.getElementById('dinero_apertura');
    var cc = document.getElementById('cobros_en_caja');
    var ct = document.getElementById('cobros_con_tarjeta');
    var ic = document.getElementById('ingreso_efectivo_en_caja');
    var rc = document.getElementById('retiro_efectivo_en_caja');
    var de = document.getElementById('dinero_entregado');
    var s = document.getElementById('saldo');


    var da1=0;
    var cc1=0;
    var ct1=0;
    var ic1=0;
    var rc1=0; 
    var de1=0;
    var s1=0;
    "<?php foreach ($cierres as $cierre):?>";
      var id = "<?php echo $cierre['id']; ?>";
      
      da1 = da1 + Number(document.getElementById("da"+id).innerHTML);
      cc1 = cc1 + Number(document.getElementById("cc"+id).innerHTML);
      ct1 = ct1 + Number(document.getElementById("ct"+id).innerHTML);
      ic1 = ic1 + Number(document.getElementById("ic"+id).innerHTML);
      rc1 = rc1 + Number(document.getElementById("rc"+id).innerHTML);
      de1 = de1 + Number(document.getElementById("de"+id).innerHTML);
      s1 = s1 + Number(document.getElementById("s"+id).innerHTML);
    "<?php endforeach ?>";
    da.innerHTML =da1.toFixed(2);
    cc.innerHTML =cc1.toFixed(2);
    ct.innerHTML =ct1.toFixed(2);
    ic.innerHTML =ic1.toFixed(2);
    rc.innerHTML =rc1.toFixed(2);
    de.innerHTML =de1.toFixed(2);
    s.innerHTML =s1.toFixed(2);

    //var utilizado = document.getElementById("utilizado"+id);
    //utilizado.innerHTML = inicial.innerHTML-final;
  }

</script>

<?php include_once('layouts/footer.php'); ?>
