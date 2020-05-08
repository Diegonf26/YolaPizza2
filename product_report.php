<?php
$page_title = 'Reporte de Inventario';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
  $products = join_product_table();
?>

<?php
  if(isset($_POST['submit'])){
    $req_dates = array('start-date','end-date');
    $option   = remove_junk($db->escape($_POST['selector']));
    validate_fields($req_dates);

    if(empty($errors)):
      $start_date   = remove_junk($db->escape($_POST['start-date']));
      $end_date     = remove_junk($db->escape($_POST['end-date']));
      $inv      = by_dates_Inventario($start_date,$end_date,$option);

      $total=0;
      foreach ($inv as $i){
        $total=$total+(float)remove_junk($i['gasto']);
      }

    else:
      $session->msg("d", $errors);
      redirect('product_report.php', false);
    endif;

  } else {
    $inv =null;
  }
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>

<div class="row">
  <form class="clearfix" method="post" action="product_report.php">
  <div class="col-md-6">
    <div class="panel">
      <div class="panel-body">
            <div class="form-group">
              <label for="exampleFormControlSelect1">Seleccione el producto</label>
              <select class="form-control" name="selector" required>
                <option></option>
                <?php foreach ($products as $product):?>
                  <option><?php echo remove_junk($product['name']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
                 <button style="visibility: hidden" type="submit" name="submit" class="btn btn-primary">Generar reporte de inventario</button>
            </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="panel">
      <div class="panel-body">
            <div class="form-group">
              <label class="form-label">Rango de fechas</label>
              <div class="input-group">
                <?php if ($inv != null):?>
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
                 <button type="submit" name="submit" class="btn btn-primary">Generar reporte de inventario</button>
            </div>
      </div>
    </div>
  </div>
  </form>
    <?php if ($inv != null):?>
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading clearfix">
            <strong>
              <span class="glyphicon glyphicon-th"></span>
              <span>Reportes de Inventario de <?php echo $option.' (desde '.$start_date.' a '.$end_date; ?>)</span>
            </strong>
          </div>
          <div class="panel-body">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th class="text-center" style="width: 15%;"> Fecha </th>
                  <th class="text-center" style="width: 10%;"> Usuario </th>
                  <th class="text-center" style="width: 10%;"> Proveedor</th>
                  <th class="text-center" style="width: 10%;"> Cantidad anterior </th>
                  <th class="text-center" style="width: 10%;"> Nueva Cantidad</th>
                  <th class="text-center" style="width: 10%;"> Unidades</th>
                  <th class="text-center" style="width: 10%;"> Precio de compra</th>
                  <th class="text-center" style="width: 10%;"> Gasto </th>
                </tr>
              </thead>
              <tbody>
                  <?php foreach ($inv as $i):?>
                    <tr>
                      <td class="text-center"> <?php echo read_date($i['date']); ?></td>
                      <td class="text-center"> <?php echo remove_junk($i['username']); ?></td>
                      <td class="text-center"> <?php echo remove_junk($i['pro']); ?></td>
                      <td class="text-center" > <?php echo remove_junk($i['last_quantity']); ?></td>
                      <td class="text-center" > <?php echo remove_junk($i['new_quantity']); ?></td>
                      <td class="text-center"> <?php echo remove_junk($i['unidades']); ?></td>
                      <td class="text-center"> <?php echo remove_junk($i['buy_price']); ?></td>
                      <td class="text-center"> <?php echo remove_junk($i['gasto']); ?></td>
                    </tr>
                  <?php endforeach; ?>
              </tbody>
              <tr>
                <th class="text-center" style="width: 10%;" colspan="7"> Total $</th>
                <th class="text-center" style="width: 10%;" id="gas"><?php echo number_format((float)$total, 2, '.', '')?></th>
              </tr>

            </table>
          </div>
        </div>
      </div>
    <?php endif; ?>
</div>

<?php include_once('layouts/footer.php'); ?>
