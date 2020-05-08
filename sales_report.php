<?php
$page_title = 'Reporte de ventas';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
  $categorias = join_categories_table();
  $listaExtras=buscar_catalogo("extra_pizzas");
?>

<?php
  if(isset($_POST['submit'])){
    $req_dates = array('start-date','end-date');
    $option   = remove_junk($db->escape($_POST['selector']));
    validate_fields($req_dates);

    if(empty($errors)):
      $start_date   = remove_junk($db->escape($_POST['start-date']));
      $end_date     = remove_junk($db->escape($_POST['end-date']));
      switch ($option) {
        case 'Pizzas':
          $ventas =  datesSales($start_date,$end_date,'venta_pizzas');
          break;
        case 'Bebidas':
          $ventas =  datesSales($start_date,$end_date,'venta_bebidas');
          break;
        case 'Ingredientes':
          $ventas =  datesSales($start_date,$end_date,'venta_ingredientes');
          break;
        case 'Ventas a escuelas':
          $ventas = datesSales($start_date,$end_date,'venta_escuelas');
          break;  
      }
      
      $pri=0;
      foreach ($ventas as $c){
        $pri=$pri+(float)remove_junk($c['price']);
      }
 
    else:
      $session->msg("d", $errors);
      redirect('ventas_report.php', false);
    endif;

  } 
  else {
    $ventas =null;

  }
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <form class="clearfix" method="post" action="sales_report.php">
    <div class="col-md-6">
      <div class="panel">
        <div class="panel-body">     
          <label for="exampleFormControlSelect1">Seleccione la categoría</label>
          <select class="form-control" name="selector" required>
            <option></option>
            <?php foreach ($categorias as $cat):?>
              <option><?php echo remove_junk($cat['name']); ?></option>
            <?php endforeach; ?>
            <?php if("Ventas a escuelas"!=remove_junk($selector)):?>
                  <option>Ventas a escuelas</option>
            <?php endif?>
          </select>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="panel">
        <div class="panel-body">        
          <div class="form-group">
            <label class="form-label">Rango de fechas</label>
            <div class="input-group">
              <?php if ($ventas != null):?>
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
        </div>
      </div>
    </div>
  </form>

  <?php if ($ventas != null):?>
    <div class="col-md-12">
      <?php echo display_msg($msg); ?>
    </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Reportes de <?php echo $option.' ('.$start_date.' a '.$end_date; ?>)</span>
          </strong>
        </div>
        <?php switch($option): 
          case 'Pizzas': ?>
            <div class="panel-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="text-center" style="width: 12%;"> Fecha </th>
                    <th class="text-center" style="width: 5%;"> Cantidad </th>
                    <th class="text-center" style="width: 20%;"> Descripción </th>
                    <th class="text-center" style="width: 10%;"> Extras</th>
                    <th class="text-center" style="width: 5%;"> Para llevar</th>
                    <th class="text-center" style="width: 10%;"> Valor</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($ventas as $sale):?>
                    <?php if($sale['forma_pago']!='autoconsumo'):?>
                      <tr>
                        <td class="text-center"> <?php echo read_date($sale['date']); ?></td>
                        <td class="text-center"> <?php echo remove_junk($sale['qty']); ?></td>
                        <td > Pizza <?php echo remove_junk($sale['tam_pizza'])?> <?php echo remove_junk($sale['sabor_pizza']); ?></td>
                        <td class="text-center">
                          <?php if (remove_junk($sale['extras'])!==''): ?>
                          <div class="checkbox">
                            <label><input onclick="return false;" type="checkbox" value="" checked></label>
                          </div>
                          <?php endif; ?>
                        </td>
                        <td class="text-center">
                          <?php if(remove_junk($sale['llevar_pizza'])!='servirse'): ?>
                            <div class="checkbox">
                              <label><input onclick="return false;" type="checkbox" value="" checked></label>
                            </div>
                          <?php endif; ?>
                        </td>
                        <td class="text-center" id="pri<?php echo remove_junk($sale['id']); ?>"> 
                          <?php
                            $p_llevar=0; 
                            if((remove_junk($sale['llevar_pizza'])!='servirse')&&($sale['tam_pizza']!='porcion')){
                              if(remove_junk($sale['tam_pizza'])=='familiar'||remove_junk($sale['tam_pizza'])=='extragrande') $p_llevar=1.00;
                              else $p_llevar=1.00;
                            }
                            $val_e=0;
                            if($sale['extras']!=null){
                              $arrayExtras = explode(",", $sale['extras']);  // se obtiene un vector de extras
                              $cos=costoExtra($sale['tam_pizza']);        //costo de extras en base al tamaño de la pizza
                              if($sale['sabor_pizza']!="personalizada")   $val_e=$cos[0]['price']*(count($arrayExtras)); // si no es personalizada solo cuenta y multiplica
                              else{
                                $auxConta=0;
                                foreach($listaExtras as $lE){
                                  foreach($arrayExtras as $aE){
                                    if($lE['name']==$aE)  $auxConta++;
                                  }
                                }
                                $val_e=$cos[0]['price']*$auxConta;
                              }
                            }
                            $p_llevar = (float)$p_llevar*(float)$sale['qty'];        
                            $val_e = (float)$val_e*(float)$sale['qty']; 
                            $total1=(float)remove_junk($sale['price'])+$p_llevar+$val_e;
                            echo number_format((float)$total1, 2, '.', '');
                          ?>
                        </td>
                      </tr>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </tbody>
                <tr>
                  <th class="text-center" style="width: 10%;" colspan="5"> Total </th>
                  <th class="text-center" style="width: 10%;" id="total"> </th>
                </tr>
              </table>
            </div>
          <?php break; ?>
          <?php case 'Bebidas': ?>
            <div class="panel-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="text-center" style="width: 12%;"> Fecha </th>
                    <th class="text-center" style="width: 5%;"> Cantidad </th>
                    <th class="text-center" style="width: 20%;"> Tamaño </th>
                    <th class="text-center" style="width: 10%;"> Sabor</th>
                    <th class="text-center" style="width: 10%;"> Valor</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($ventas as $sale):?>
                    <?php if($sale['forma_pago']!='autoconsumo'):?>
                      <tr>
                        <td class="text-center"> <?php echo read_date($sale['date']); ?></td>
                        <td class="text-center"> <?php echo remove_junk($sale['qty']); ?></td>
                        <td class="text-center"> <?php echo remove_junk($sale['tam_bebida'])?> </td>
                        <td class="text-center"> <?php echo remove_junk($sale['sabor_bebida']); ?></td>
                        <td class="text-center" id="pri<?php echo remove_junk($sale['id']); ?>"> <?php echo remove_junk($sale['price']); ?></td>
                      </tr>
                    <?php endif;?>
                  <?php endforeach; ?>
                </tbody>
                  <tr>
                    <th class="text-center" style="width: 10%;" colspan="4"> Total </th>
                    <th class="text-center" style="width: 10%;" id="total"> </th>
                  </tr>
              </table>
            </div>
          <?php break; ?>
          <?php case 'Ingredientes': ?>
            <div class="panel-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="text-center" style="width: 12%;"> Fecha </th>
                    <th class="text-center" style="width: 5%;"> Cantidad </th>
                    <th class="text-center" style="width: 20%;"> Ingrediente </th>
                    <th class="text-center" style="width: 10%;"> Valor</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($ventas as $sale):?>
                    <?php if($sale['forma_pago']!='autoconsumo'&& $sale['nombre_ingre']!='familiar'&&$sale['nombre_ingre']!='mediana'&&$sale['nombre_ingre']!='extragrande'):?>
                      <tr>
                        <td class="text-center"> <?php echo read_date($sale['date']); ?></td>
                        <td class="text-center"> <?php echo remove_junk($sale['qty']); ?></td>
                        <td class="text-center"> <?php echo remove_junk($sale['nombre_ingre']); ?></td>
                        <td class="text-center" id="pri<?php echo remove_junk($sale['id']); ?>"> <?php echo remove_junk($sale['price']); ?></td>
                      </tr>
                    <?php endif;?>
                  <?php endforeach; ?>
                </tbody>
                  <tr>
                    <th class="text-center" style="width: 10%;" colspan="3"> Total </th>
                    <th class="text-center" style="width: 10%;" id="total"> </th>
                  </tr>
              </table>
            </div>
          <?php break; ?>
          <?php case 'Ventas a escuelas': ?>
            <div class="panel-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="text-center" style="width: 12%;"> Fecha </th>
                    <th class="text-center" style="width: 10%;"> Cantidad de masas </th>
                    <th class="text-center" style="width: 10%;"> Cajas Grandes </th>
                    <th class="text-center" style="width: 10%;"> Cajas Pequeñas </th>
                    <th class="text-center" style="width: 10%;"> Valor</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($ventas as $sale):?>
                      <tr>
                        <td class="text-center"> <?php echo read_date($sale['date']); ?></td>
                        <td class="text-center"> <?php echo remove_junk($sale['qty_masas']); ?></td>
                        <td class="text-center"> <?php echo remove_junk($sale['cajaGrande']); ?></td>
                        <td class="text-center"> <?php echo remove_junk($sale['cajaPequena']); ?></td>    
                        <td class="text-center" id="pri<?php echo remove_junk($sale['id']); ?>"> <?php echo remove_junk($sale['price']); ?></td>
                      </tr>
                  <?php endforeach; ?>
                </tbody>
                <tr>
                  <th class="text-center" style="width: 10%;" colspan="4"> Total </th>
                  <th class="text-center" style="width: 10%;" id="total"> </th>
                </tr>
              </table>
            </div>
          <?php break; ?>
        <?php endswitch; ?>
      </div>
    </div>
  <?php endif; ?>

</div>

<script>
myFunction();

  function myFunction() {
    "<?php if ($ventas != null):?>";
      var s = document.getElementById('total');
      var pri=0;
      "<?php foreach ($ventas as $sale):?>";
        "<?php if($sale['forma_pago']!='autoconsumo'):?>";
        var id = "<?php echo $sale['id']; ?>";
        
        pri = pri + Number(document.getElementById("pri"+id).innerHTML);

        "<?php endif; ?>";
      "<?php endforeach ?>";
      s.innerHTML =pri.toFixed(2);
    "<?php endif; ?>"; 
  }

</script>
<?php include_once('layouts/footer.php'); ?>
