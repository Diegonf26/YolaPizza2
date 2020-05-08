<?php
$page_title = 'Reporte de cierres de caja';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>

<?php
  if(isset($_POST['submit'])){
    $req_dates = array('start-date','end-date');
    validate_fields($req_dates);

    if(empty($errors)):
      $start_date   = remove_junk($db->escape($_POST['start-date']));
      $end_date     = remove_junk($db->escape($_POST['end-date']));
      $cierres      = by_dates_cierres_cajas($start_date,$end_date);

      $da=0;
      $cc=0;
      $ct=0; 
      $ic=0; 
      $rc=0; 
      $de=0;
      $s=0;
      foreach ($cierres as $c){
        $da=$da+(float)remove_junk($c['dinero_apertura']);
        $cc=$cc+(float)remove_junk($c['cobros_en_caja']);
        $ct=$ct+(float)remove_junk($c['cobros_con_tarjeta']);
        $ic=$ic+(float)remove_junk($c['ingreso_efectivo_en_caja']);
        $rc=$rc+(float)remove_junk($c['retiro_efectivo_en_caja']);
        $de=$de+(float)remove_junk($c['dinero_entregado']);
        $s=$s+(float)remove_junk($c['saldo']);
      }
 
    else:
      $session->msg("d", $errors);
      redirect('caja_cierre_general.php', false);
    endif;

  } 
  else {
    $cierres =null;

  }
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="panel">
      <div class="panel-body">
          <form class="clearfix" method="post" action="caja_cierre_general.php">
            <div class="form-group">
            <label class="form-label">Rango de fechas</label>
              <div class="input-group">
                <?php if ($cierres != null):?>
                  <input type="text" class="datepicker form-control" name="start-date" value=<?php echo $start_date; ?> required autocomplete="off">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
                  <input type="text" class="datepicker form-control" name="end-date" value=<?php echo $end_date; ?> required autocomplete="off">                 
                <?php else:?>
                  <input type="text" class="datepicker form-control" name="start-date" Placeholder='Desde' required autocomplete="off">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-menu-right"></i></span>
                  <input type="text" class="datepicker form-control" name="end-date" Placeholder='Hasta' required autocomplete="off">
                  <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
                 <button type="submit" name="submit" class="btn btn-primary">Generar Reporte</button>
            </div>
          </form>
      </div>
    </div>
  </div>

  <?php if ($cierres != null):?>
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Reportes de cierres de caja  (<?php echo $start_date.' a '.$end_date; ?>)</span>
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
            <tr>
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
            <th class="text-center" style="width: 10%;" ><?php echo number_format((float)$da, 2, '.', '')?> </th>
            <th class="text-center" style="width: 10%;" ><?php echo number_format((float)$cc, 2, '.', '')?> </th>
            <th class="text-center" style="width: 10%;" ><?php echo number_format((float)$ct, 2, '.', '')?> </th>
            <th class="text-center" style="width: 10%;" ><?php echo number_format((float)$ic, 2, '.', '')?> </th>
            <th class="text-center" style="width: 10%;" ><?php echo number_format((float)$rc, 2, '.', '')?> </th>
            <th class="text-center" style="width: 10%;" ><?php echo number_format((float)$de, 2, '.', '')?> </th>
            <th class="text-center" style="width: 10%;" ><?php echo number_format((float)$s, 2, '.', '')?> </th>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <?php endif; ?>

</div>


<?php include_once('layouts/footer.php'); ?>
