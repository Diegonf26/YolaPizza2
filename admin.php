<?php
  $page_title = 'Admin página de inicio';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
?>
<?php
  $d = make_date2();
  $cc = find_conta('contador');
  $products = join_product_table();

  if($d!=$cc[0]['date']){   //solo actualiza si se ha cambiado el valor
    $query = "UPDATE contador SET ";        //Insertar la BD en la memoria de usuario
    $query .=" conta = 1, date = '{$d}' WHERE id = 1;";
    if($db->query($query)){
      foreach ($products as $product) {     
        $qty   = $product['quantity'];
        $qty_apro   = $product['qtyAproximada'];
        $tempo=(float)$qty-(float)$qty_apro;
        $query = "UPDATE products SET ";        //Insertar la BD en la memoria de usuario
        $query .=" quantity = '{$tempo}', date = '{$d}', qtyAproximada = '0' WHERE id='{$product['id']}';";
        $db->query($query);
      }
    }
  }
  $contador=$cc[0]['conta'];
  
  $year  = date('Y');
  $month = date('m');
  $day = date('d');

  $total1=0;
  $total2=0;
  $total3=0;
  $total4=0;

  $ventasPizzas = dailySales($year,$month,$day,'venta_pizzas');
  $ventasBebidas = dailySales($year,$month,$day,'venta_bebidas');
  $ventasIngredientes = dailySales($year,$month,$day,'venta_ingredientes');
  $ventasEscuelas = dailySales($year,$month,$day,'venta_escuelas');
  
  $listaExtras=buscar_catalogo("extra_pizzas");

  foreach ($ventasPizzas as $vP){
    if($vP['forma_pago']!='autoconsumo'){
      $p_llevar=0;
      if($vP['llevar_pizza']!='servirse' && $vP['tam_pizza']!='porcion'){
        if($vP['tam_pizza']=='familiar'||$vP['tam_pizza']=='extragrande') $p_llevar=1.00;
        else $p_llevar=1.00;
      }
      $val_e=0;
      if($vP['extras']!=null){
        $arrayExtras = explode(",", $vP['extras']);  // se obtiene un vector de extras
        $cos=costoExtra($vP['tam_pizza']);        //costo de extras en base al tamaño de la pizza
        if($vP['sabor_pizza']!="personalizada")   $val_e=$cos[0]['price']*(count($arrayExtras)); // si no es personalizada solo cuenta y multiplica
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
      $p_llevar = (float)$p_llevar*(float)$vP['qty'];        
      $val_e = (float)$val_e*(float)$vP['qty'];
      $total1=$total1+(float)remove_junk($vP['price'])+$p_llevar+$val_e;
    }
  }

  foreach ($ventasBebidas as $vB){
    if($vB['forma_pago']!='autoconsumo'){
      $total2=$total2+(float)remove_junk($vB['price']);
    }
    
  }

  foreach ($ventasIngredientes as $vI){
    if($vI['forma_pago']!='autoconsumo'&& $vI['nombre_ingre']!='familiar'&&$vI['nombre_ingre']!='mediana'&&$vI['nombre_ingre']!='extragrande'){
      $total3=$total3+(float)remove_junk($vI['price']);
    }
  }

  foreach ($ventasEscuelas as $vE){
    $total4=$total4+(float)remove_junk($vE['price']);
  }

  $ventasDiarias= $total1+$total2+$total3+$total4;

  $c_categorie     = count_by_id('categories');
  $c_product       = count_by_id('products');
  $c_sale          = count_by_id('sales');
  $c_user          = count_by_id('users');
  $products_sold_mediana   = find_higest_saleing_pizzas('3','mediana');
  $products_sold_familiar   = find_higest_saleing_pizzas('3','familiar');
  $products_sold_extragrande   = find_higest_saleing_pizzas('3','extragrande');
  $extra_pizzas=join_extrapizza_table();

  $tam_pizzas=buscar_catalogo("tam_pizzas");

  $recent_products = find_bajostock_product();

  $recent_sales    = find_recent_sale_added('5');
  $array_tama=  array('mediana', 'familiar', 'extragrande'); 
  $prueba="a";
  $prueba="c";
  $item_compr= array();
  $lista_items=array();

  //Contador de productos
  //Contador de Masas ---
  $masa_porcion=contador_masas('porcion','venta_pizzas');
  foreach ($masa_porcion as $porcion){ $v_masa_porcion=remove_junk($porcion['sum(qty)']); if($v_masa_porcion==NULL)$v_masa_porcion=0;}
  $masa_mediana=contador_masas('mediana','venta_pizzas');
  foreach ($masa_mediana as $mediana){ $v_masa_mediana=remove_junk($mediana['sum(qty)']); if($v_masa_mediana==NULL)$v_masa_familiar=0;}
  $masa_familiar=contador_masas('familiar','venta_pizzas');
  foreach ($masa_familiar as $familiar){ $v_masa_familiar=remove_junk($familiar['sum(qty)']);if($v_masa_familiar==NULL)$v_masa_familiar=0;}
  $masa_extragrande=contador_masas('extragrande','venta_pizzas');
  foreach ($masa_extragrande as $extragrande){ $v_masa_extragrande=remove_junk($extragrande['sum(qty)']); if($v_masa_extragrande==NULL)$v_masa_extragrande=0;}
  $masa_escuelas=contador_masas_escuela('venta_escuelas');
  foreach ($masa_escuelas as $escuela){ $v_masa_escuela=remove_junk($escuela['sum(qty_masas)']); if($v_masa_escuela==NULL)$v_masa_escuela=0;}
    
  
  $masa_totales=(0.5*(float)$v_masa_mediana)+($v_masa_porcion/(float)9)+(float)$v_masa_familiar+(float)$v_masa_extragrande+(float)$v_masa_escuela;
  
  //Variable de aproximado de productos
  $aprox_prod=1;

  if(isset($_GET['p_aproximado'])) 
  {
    $aprox_prod=$_GET['p_aproximado'];

    $products = join_product_table();
    $sabor_pizzas=join_tipopizza_table();
    $pizzas_espec=join_pizzaespecilal_table();
    $tam_pizzas= join_tampizza_table();
  
    $div_personalizada=0;
    $year  = date('Y');
    $month = date('m');
    $day = date('d');
    $ventasPizzas = dailySales($year,$month,$day,'venta_pizzas');
  
    //Contador de Cajas
    $caja_mediana1= contador_cajas1 ('mediana','venta_cajas');
    foreach ($caja_mediana1 as $cmediana1){ $v_caja_mediana1=remove_junk($cmediana1['sum(qty)']); if($v_caja_mediana1==NULL)$v_caja_mediana1=0;}
    $caja_grande= contador_cajas1 ('familiar','venta_cajas');
    foreach ($caja_grande as $cgrande){ $v_caja_grande=remove_junk($cgrande['sum(qty)']); if($v_caja_grande==NULL)$v_caja_grande=0;}      
    $caja_extragrande= contador_cajas1 ('extragrande','venta_cajas');
    foreach ($caja_extragrande as $cextragrande){ $v_caja_extragrande=remove_junk($cextragrande['sum(qty)']); if($v_caja_extragrande==NULL)$v_caja_extragrande=0;}      

    //SABORES
    //Tipo Especial
    //Conteo de Pizzas Especiales
    foreach ($pizzas_espec as $sab) {
      $nombre_sab=remove_junk($sab['name']); 
      if($nombre_sab=="personalizada"){
        foreach ($ventasPizzas as $vP){
          if($vP['sabor_pizza']=="personalizada"){               //Escogo solo pizzas personalizada
            $arrayExtras = explode(",", $vP['extras']);  // se obtiene un vector de extras
            foreach($sabor_pizzas as $sp){
              $nombre_sp=remove_junk($sp['name']); 
              ${'v_masa_perso_'.$nombre_sp}=0;
              foreach($arrayExtras as $aE){
                if($aE==ucwords($nombre_sp)){
                  $div_personalizada++;
                  if($vP['tam_pizza']!="porcion"){
                    if($vP['tam_pizza']=="mediana")
                      ${'v_masa_perso_'.$nombre_sp}=0.5;
                    else
                      ${'v_masa_perso_'.$nombre_sp}=1;
                  }
                }  
              }
            }
          }
        }
      }
    }

    //Diferentes a personalizadas
    foreach ($pizzas_espec as $sab) {
      $nombre_sab=remove_junk($sab['name']); 
      if($nombre_sab!="personalizada"){
        foreach ($tam_pizzas as $tam) {
          $nombre_tam=remove_junk($tam['name']);
          ${'masa_'.$nombre_tam.'_sabor'}=contador_masas_sabor(remove_junk($tam['name']),'venta_pizzas',remove_junk($sab['name']));
          foreach (${'masa_'.$nombre_tam.'_sabor'} as $tms){ ${'v_masa_'.$nombre_tam.'_sabor'}=remove_junk($tms['sum(qty)']); if( ${'v_masa_'.$nombre_tam.'_sabor'}==NULL) ${'v_masa_'.$nombre_tam.'_sabor'}=0;}
        }
        ${'v_masa_'.$nombre_sab}=(0.5*(float)$v_masa_mediana_sabor)+($v_masa_porcion_sabor/(float)9)+(float)$v_masa_familiar_sabor+(float)$v_masa_extragrande_sabor;
      }
    }
  
    //Tipo Normal
    foreach ($sabor_pizzas as $sab) {
      $nombre_sab=remove_junk($sab['name']); 
      foreach ($tam_pizzas as $tam) {
        $nombre_tam=remove_junk($tam['name']);
        ${'masa_'.$nombre_tam.'_sabor'}=contador_masas_sabor(remove_junk($tam['name']),'venta_pizzas',remove_junk($sab['name']));
        foreach (${'masa_'.$nombre_tam.'_sabor'} as $tms){ ${'v_masa_'.$nombre_tam.'_sabor'}=remove_junk($tms['sum(qty)']); if( ${'v_masa_'.$nombre_tam.'_sabor'}==NULL) ${'v_masa_'.$nombre_tam.'_sabor'}=0;}
      }
      ${'v_masa_'.$nombre_sab}=(0.5*(float)$v_masa_mediana_sabor)+($v_masa_porcion_sabor/(float)9)+(float)$v_masa_familiar_sabor+(float)$v_masa_extragrande_sabor+(float)${'v_masa_personalizada_'.$nombre_sab};
  
      if($div_personalizada!=0){        //Evitar division para cero si el numero de div de personalizada es 0
        ${'v_masa_'.$nombre_sab}+=(1/(float)$div_personalizada)*(${'v_masa_perso_'.$nombre_sab});   //Sumar la parte de pizza personalizada
      }
    }
    
  
    //Tipo Normal
    foreach ($sabor_pizzas as $sab) {
      $nombre_sab=remove_junk($sab['name']); 
      foreach ($tam_pizzas as $tam) {
        $nombre_tam=remove_junk($tam['name']);
        ${'masa_'.$nombre_tam.'_sabor'}=contador_masas_sabor(remove_junk($tam['name']),'venta_pizzas',remove_junk($sab['name']));
        foreach (${'masa_'.$nombre_tam.'_sabor'} as $tms){ ${'v_masa_'.$nombre_tam.'_sabor'}=remove_junk($tms['sum(qty)']); if( ${'v_masa_'.$nombre_tam.'_sabor'}==NULL) ${'v_masa_'.$nombre_tam.'_sabor'}=0;}
      }
      ${'v_masa_'.$nombre_sab}=(0.5*(float)$v_masa_mediana_sabor)+($v_masa_porcion_sabor/(float)9)+(float)$v_masa_familiar_sabor+(float)$v_masa_extragrande_sabor+(float)${'v_masa_personalizada_'.$nombre_sab};
  
      if($div_personalizada!=0){        //Evitar division para cero si el numero de div de personalizada es 0
        ${'v_masa_'.$nombre_sab}+=(1/(float)$div_personalizada)*(${'v_masa_perso_'.$nombre_sab});   //Sumar la parte de pizza personalizada
      }
    }
  }
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<!-- Info boxes -->
<div class="row">
  <!--Usuarios-->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-green"><i class="glyphicon glyphicon-user"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Usuarios</span>
        <span class="info-box-number"><?php  echo $c_user['total']; ?></span>
      </div>
    </div>
  </div>
  <!--Ventas-->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-yellow"><i class="glyphicon glyphicon-usd"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Ventas diarias</span>
        <span class="info-box-number"><small >$</small><?php  echo number_format((float)$ventasDiarias, 2, '.', ''); ?></span>
      </div>
    </div>
  </div>
  <!--Procductos-->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-blue"><i class="glyphicon glyphicon-shopping-cart"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Productos</span>
        <span class="info-box-number"><?php  echo $c_product['total']; ?></span>
      </div>
    </div>
  </div>
  <!--Contador-->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-red"><i class="glyphicon glyphicon-list"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Contador de ordenes</span>
        <span class="info-box-number"><?php  echo $contador; ?></span>
      </div>
    </div>
  </div>
  <!--Masas utilizadas-->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-gray"><i class="glyphicon glyphicon-adjust"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Masas utilizadas</span>
        <span class="info-box-number"><?php echo $masa_totales; ?></span>
      </div>
    </div>
  </div>

</div>
<!-- /.row -->

<!--.......Detalle de productos......-->
<div class="row">
  <!--Productos mas vedidos-->
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Pizzas extragrande más vendidas</span>
        </strong>
      </div>
      <div class="panel-body">
        <table class="table table-striped table-bordered table-condensed">
          <thead>
            <tr>
              <th class="text-center">Cantidad</th>
              <th class="text-center">Sabor</th>
              <th class="text-center">Valor vendido</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($products_sold_extragrande as  $product_sold): ?>
            <tr>
              <td class="text-center"><?php echo (int)$product_sold['totalQty']; ?></td>
              <td class="text-center"><?php echo remove_junk(first_character($product_sold['nam'])); ?></td>
              <td class="text-center">$ <?php echo number_format((float)$product_sold['totalprice'], 2, '.', ''); ?></td>   
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Pizzas familiar más vendidas</span>
        </strong>
      </div>
      <div class="panel-body">
        <table class="table table-striped table-bordered table-condensed">
          <thead>
            <tr>
              <th class="text-center">Cantidad</th>
              <th class="text-center">Sabor</th>
              <th class="text-center">Valor vendido</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($products_sold_familiar as  $product_sold): ?>
            <tr>
              <td class="text-center"><?php echo (int)$product_sold['totalQty']; ?></td>
              <td class="text-center"><?php echo remove_junk(first_character($product_sold['nam'])); ?></td>
              <td class="text-center">$ <?php echo number_format((float)$product_sold['totalprice'], 2, '.', ''); ?></td>   
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Pizzas medianas más vendidas</span>
        </strong>
      </div>
      <div class="panel-body">
        <table class="table table-striped table-bordered table-condensed">
          <thead>
            <tr>
              <th class="text-center">Cantidad</th>
              <th class="text-center">Sabor</th>
              <th class="text-center">Valor vendido</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($products_sold_mediana as  $product_sold): ?>
            <tr>
              <td class="text-center"><?php echo (int)$product_sold['totalQty']; ?></td>
              <td class="text-center"><?php echo remove_junk(first_character($product_sold['nam'])); ?></td>
              <td class="text-center">$ <?php echo number_format((float)$product_sold['totalprice'], 2, '.', ''); ?></td>   
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Ventas diarias-->
  <div class="col-md-5">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Últimas ventas del día</span>
        </strong>
      </div>
      <div class="panel-body">
        <table class="table table-striped table-bordered table-condensed">
          <thead>
            <tr>
              <th class="text-center">Orden N°</th>
              <th class="text-center">Fecha</th>
              <th class="text-center">Usuario</th>
              <th class="text-center">Valor de venta</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recent_sales as  $recent_sale): ?>
              <?php if($recent_sale['forma_pago']!='autoconsumo'): ?>
                <tr>
                  <td class="text-center"><?php echo remove_junk(first_character($recent_sale['orden'])); ?></td>
                  <td class="text-center"><?php echo remove_junk(ucfirst($recent_sale['date'])); ?></td>
                  <td class="text-center"><?php echo remove_junk(first_character($recent_sale['user'])); ?></td>
                  <td class="text-center">$<?php echo remove_junk(first_character($recent_sale['price'])); ?></td>
                </tr>
              <?php endif; ?>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Ventas diarias</span>
        </strong>
      </div>
      <div class="panel-body">
        <table class="table table-striped table-bordered table-condensed">
          <thead>
            <tr>
              <th class="text-center">Total</th>
              <th class="text-center">Pizzas</th>
              <th class="text-center">Bebidas</th>
              <th class="text-center">Ingredientes</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-center">$<?php echo number_format((float)$ventasDiarias, 2, '.', '');?></td>
              <td class="text-center">$<?php echo number_format((float)$total1, 2, '.', ''); ?></td>
              <td class="text-center">$<?php echo number_format((float)$total2, 2, '.', ''); ?></td>
              <td class="text-center">$<?php echo number_format((float)$total3, 2, '.', ''); ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!--Productos recientes-->
  <div class="col-md-3">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Productos con stock bajo</span>
        </strong>
      </div>
      <div class="panel-body">
        <div class="list-group">
          <?php foreach ($recent_products as  $recent_product): ?>
            <a class="list-group-item clearfix" href="product.php">
              <h4 class="list-group-item-heading">
                <?php if($recent_product['media_id'] === '0'): ?>
                  <img class="img-avatar img-circle" src="uploads/products/no_image.jpg" alt="User Image"  width="100" height="100">
                <?php else: ?>
                  <img class="img-avatar img-circle" src="uploads/products/<?php echo $recent_product['image'];?>" alt="User Image"  width="100" height="100" />
                <?php endif;?>
                <?php echo remove_junk(first_character($recent_product['name']));?>
                  <span class="label label-warning pull-right"><?php echo (int)$recent_product['quantity']; ?></span>
              </h4>
              <span class="list-group-item-text pull-right"><?php echo remove_junk(first_character($recent_product['categorie'])); ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script >
  //alert("Prueba");
  //alert("<?php echo $v_masa_escuela?>");
  if(Number(0)==Number("<?php echo $aprox_prod?>")){
    var DOMAIN = "http://localhost/Pizzeria/";

    //............CONTADOR DE MASAS .................................
    var masas=Number("<?php echo $masa_totales?>");
    
    //Contador de ingredientes extras
    <?php foreach ($extra_pizzas as $extra):?>
      var nombre_extra="<?php echo remove_junk($extra['name'])?>"; 
      <?php foreach ($tam_pizzas as $tam):?>
        var tamano="<?php echo remove_junk($tam['name'])?>";
        if("<?php echo ${"canti_".$extra['name']."_".$tam['name']}?>"!="")
          eval("ingre_"+nombre_extra+"_"+tamano+"="+"<?php echo ${"canti_".$extra['name']."_".$tam['name']}?>");
        else
          eval("ingre_"+nombre_extra+"_"+tamano+"="+0);
      <?php endforeach; ?>
    <?php endforeach; ?>
      
    //SABORES NORMALES
    var masas_mixta=Number("<?php echo $v_masa_mixta?>");
    var masas_hawayana=Number("<?php echo $v_masa_hawayana?>");
    var masas_pollo=Number("<?php echo $v_masa_pollo?>");
    var masas_vegetariana=Number("<?php echo $v_masa_vegetariana?>");
    var masas_carne=Number("<?php echo $v_masa_carne?>");
    var masas_tocino=Number("<?php echo $v_masa_tocino?>");
    var masas_napolitana=Number("<?php echo $v_masa_napolitana?>");
    var masas_criolla=Number("<?php echo $v_masa_criolla?>");
    var masas_tropical=Number("<?php echo $v_masa_tropical?>");
    var masas_mexicana=Number("<?php echo $v_masa_mexicana?>");

    //SABORES ESPECIALES
    var masas_amangiare=Number("<?php echo $v_masa_amangiare?>");
    var masas_tradicionalHawayana=Number("<?php echo $v_masa_tradicionalHawayana?>");
    var masas_tradicionalPollo=Number("<?php echo $v_masa_tradicionalPollo?>");

    //-----------------INVENTARIO DE APROXIMADOS-----------------
    var val_aprox_Masas=masas;
    var val_aprox_CajasMedianas=0;
    var val_aprox_CajasGrandes=0;
    var val_aprox_Harina = (0.5*masas).toFixed(2);
    var val_aprox_Levadura = (0.0043*masas).toFixed(2);
    var val_aprox_Aceite = (0.042*masas).toFixed(2);

    //Con ingredientes extras
    //Ingrediente que pueden ser agregados como extras
    var val_aprox_Queso = ((0.357*masas)+(0.047*ingre_queso_porcion)+(0.179*ingre_queso_mediana)+(0.357*ingre_queso_familiar)+(0.45*ingre_queso_extragrande)).toFixed(2);
    var val_aprox_Champiñones = (0.2*masas_pollo+0.1*masas_tradicionalPollo+0.1*masas_amangiare+0.1*masas_carne+0.2*masas_tocino+0.2*masas_vegetariana+(0.025*ingre_champinones_porcion)+(0.1*ingre_champinones_mediana)+(0.2*ingre_champinones_familiar)+(0.25*ingre_champinones_extragrande)).toFixed(2); //+0.2*masas_vegana
    var val_aprox_Salami = (0.05*masas_mixta+0.0125*masas_amangiare+0.025*masas_tradicionalPollo+0.025*masas_tradicionalHawayana+0.1*masas_napolitana+(0.0125*ingre_salami_porcion)+(0.05*ingre_salami_mediana)+(0.1*ingre_salami_familiar)+(0.13*ingre_salami_extragrande)).toFixed(2);
    var val_aprox_Durazno = (0.5*masas_tropical+(0.0625*ingre_durazno_porcion)+(0.25*ingre_durazno_mediana)+(0.5*ingre_durazno_familiar)+(0.7*ingre_durazno_extragrande)).toFixed(2);   
    var val_aprox_Piña = (0.3333*masas_hawayana+0.3333*masas_tropical+0.0833*masas_amangiare+0.1667*masas_tradicionalHawayana+(0.0416*ingre_pina_porcion)+(0.166*ingre_pina_mediana)+(0.333*ingre_pina_familiar)+(0.42*ingre_pina_extragrande)).toFixed(2);
    var val_aprox_Jamón = (0.05*masas_mixta+0.1*masas_hawayana+0.0125*masas_amangiare+0.025*masas_tradicionalPollo+0.075*masas_tradicionalHawayana+(0.0125*ingre_jamon_porcion)+(0.05*ingre_jamon_mediana)+(0.1*ingre_queso_familiar)+(0.13*ingre_jamon_extragrande)).toFixed(2);
    var val_aprox_Peperoni = (0.05*masas_mixta+0.0125*masas_amangiare+0.025*masas_tradicionalPollo+0.025*masas_tradicionalHawayana+0.1*masas_mexicana+(0.0125*ingre_peperoni_porcion)+(0.05*ingre_peperoni_mediana)+(0.1*ingre_peperoni_familiar)+(0.13*ingre_peperoni_extragrande)).toFixed(2);

    var val_aprox_Mortadela = (0.1*masas_mixta+0.025*masas_amangiare+0.05*masas_tradicionalPollo+0.05*masas_tradicionalHawayana).toFixed(2);
    var val_aprox_Salsa = (0.0625*masas).toFixed(2);
    var val_aprox_Pollo = (0.25*masas_pollo+0.125*masas_tradicionalPollo+0.0625*masas_amangiare).toFixed(2); 
    var val_aprox_Carne = (0.22*masas_carne+0.22*masas_criolla+0.22*masas_mexicana).toFixed(2);
    var val_aprox_Tocino = (0.2*masas_tocino+0.05*masas_criolla+0.05*masas_amangiare).toFixed(2);
    
    
    var val_aprox_CajasGrandes =Number("<?php echo $v_caja_grande;?>")+Number("<?php echo $v_caja_extragrande?>");
    var val_aprox_CajasMedianas=Number("<?php echo $v_caja_mediana1?>");
    

    //Cargar productos a inventario aproxiado
    <?php foreach ($products as $prod) :?>
      $.ajax({url: DOMAIN+"guardar_invent_aprox.php?p_producto="+'<?php echo remove_junk($prod['name']); ?>'+"&p_cantidad="+val_aprox_<?php echo remove_junk($prod['name']); ?>   //Guardar en BD aproximados
      });
    <?php endforeach; ?>
  } 
</script>

<?php include_once('layouts/footer.php'); ?>