<?php
  $page_title = 'Ventas';
  $selec="Selecciona el sabor del ingrediente";
  require_once('includes/load.php');

  // Checkin What level user has permission to view this page
   page_require_level(3);
   //Catergoria pizzas
   $categorias = join_categories_table();
   $tam_pizzas= join_tampizza_table();
   $sabor_pizzas=join_tipopizza_table();
   $extra_pizzas=join_extrapizza_table();
   $pizzas_espec=join_pizzaespecilal_table();
   $products = join_product_table();
  
   $bebidas=join_bebidas_table(); //categoria Bebidas
   $ingredientes=join_ingredientes_table(); //categoria ingredientes
   $otros = join_otros_table("catalogo_otros"); //categoria otros
   $lasagna = join_otros_table("catalogo_lasagna"); //categoria lasagna
   $pastas=join_otros_table("catalogo_pastas"); //categoria pastas
   $ensaladas=join_otros_table("catalogo_ensaladas"); //categoria ensaladas
   $ensaladasFrutas=join_otros_table("catalogo_ensaladaFrutas"); //categoria ensaladas Frutas

   $sabores = find_all('tipo_pizzas');
   $cc = find_conta('contador');
   $contador=$cc[0]['conta'];
?>
<?php
 $c_categorie     = count_by_id('categories');
 $c_product       = count_by_id('products');
 $c_sale          = count_by_id('sales');
 $c_user          = count_by_id('users');
 $recent_products = find_recent_product_added('5');
 $recent_sales    = find_recent_sale_added('5');

 $tmp=0;

?>


<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-6">
    <?php echo display_msg($msg); ?>
   </div>
</div>
  <!--.......Cuadrados de visualizacion......-->
  <div class="row">
  <!--Seleccion de Productos-->
  <div class="col-md-7">
    <div id="cont_categ" class="row">
    <!--Categorias-->
      <?php foreach ($categorias as $cat):?>
        <div class="col-md-3">
          <div class="card" style="width: 18rem;">
            <?php if($cat['media_id'] === '0'): ?>
              <a href="#" onclick="selec_categ('<?php echo remove_junk(ucfirst($cat['name'])); ?>');" title="Seleccionar Categoria"> 
               <img class="card-img-top img-responsive" src="uploads/products/no_image.jpg" alt="">
              </a>
            <?php else: ?>
                <a class="text-center" href="#"  onclick="selec_categ('<?php echo remove_junk(ucfirst($cat['name'])); ?>');" title="Seleccionar Categoria"> 
                  <img class="card-img-top img-responsive" src="uploads/products/<?php echo $cat['image']; ?>" alt=""style="height: 130px; display: block; margin-left: auto;margin-right: auto;">
                </a>
            <?php endif; ?>
            <h4 class="card-title center"> <?php echo remove_junk(ucfirst($cat['name'])); ?> </h4>    <!--Lee nombres de categrias-->
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <!-- Contenedor de productos -->
    <div class="row">
      <div id="selec_productos" class="container-fluid text-center" style="background-color: #A3ABA7;">
        <!-- regresar -->
        <div id="funcion_regresar" class="row" style="display: none;">
          <button id="btn_regresar" type="button" class="btn btn-success" style="width: auto" onclick="regresar_carac()">
            <i class="glyphicon glyphicon-arrow-left"></i>
              Regresar
          </button>
          <div class='col-sm-8 text-center'>
            <h3 id='titulo_regresar' class='text-center text-white' style="color: #213041; font-weight: bold;">Example </h3>
          </div>
        </div>

        <!-- Categoria cajas-->
        <div id="selc_cajas" class="row" style="display: none;">
          <div class="col-sm-3">
            <div class="card" style="width: 16rem; ">
              <a href="#" onclick="f_caja('mediana');" title="Seleccionar Caja Mediana"> 
                <img class="card-img-top img-responsive" src="uploads/products/cajaPizza_mediana.png" alt="">
              </a>
              <h4 class="card-title center"> Caja Mediana </h4>
              <p class="card-body"> Precio: $1.00 </p>
            </div>
          </div>

          <div class="col-sm-3">
            <div class="card" style="width: 16rem; ">
              <a href="#" onclick="f_caja('familiar');" title="Seleccionar Caja Grande"> 
                <img class="card-img-top img-responsive" src="uploads/products/cajaPizza_grande.png" alt="">
              </a>
              <h4 class="card-title center"> Caja Grande </h4>
              <p class="card-body"> Precio: $1.00 </p>
            </div>
          </div>
          <!-- //Caja aparte -->
          <div class="col-sm-3">
            <div class="card" style="width: 16rem; ">
              <a href="#" onclick="f_caja('familiarEspecial');" title="Seleccionar Caja Mediana"> 
                <img class="card-img-top img-responsive" src="uploads/products/Pizza-Box.png" alt="">
              </a>
              <h4 class="card-title center"> Caja Familiar Especial</h4>
              <p class="card-body"> Precio: $1.50 </p>
            </div>
          </div>

        </div>

        <!-- Categoria Bebidas -->
        <div id="selc_bebidas" class="row" style="display: none;">
          <?php foreach ($bebidas as $beb):?>
            <div class="col-sm-3">
              <div class="card" style="width: 16rem; ">
                <?php if($beb['media_id'] === '0'): ?>
                  <a href="#" onclick="f_bebidas('<?php echo remove_junk($beb['size']); ?>','<?php echo remove_junk($beb['flavor']); ?>');" title="Seleccionar Producto"> 
                  <img class="card-img-top img-responsive" src="uploads/products/no_image.jpg" alt="">
                  </a>
                <?php else: ?>
                <a href="#" onclick="f_bebidas('<?php echo remove_junk($beb['size']); ?>','<?php echo remove_junk($beb['flavor']); ?>');" title="Seleccionar <?php echo remove_junk(ucfirst($beb['size'])); ?>"> 
                    <img class="card-img-top img-responsive" src="uploads/products/<?php echo $beb['image']; ?>" alt="">
                  </a>
                <?php endif; ?>
                <h4 class="card-title center"> <?php echo remove_junk(ucfirst($beb['size']));?>  <?php echo remove_junk(ucfirst($beb['flavor'])); ?> </h4>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Categoria Ingrediantes -->
        <div id="selc_ingredientes" class="row" style="display: none;">
          <?php foreach ($ingredientes as $ingre):?>
            <div class="col-sm-3">
              <div class="card" style="width: 16rem;">
                <?php if($ingre['media_id'] === '0'): ?>
                  <a href="#" onclick="f_ingred('<?php echo remove_junk($ingre['nombre']); ?>');" title="Seleccionar Producto"> 
                  <img class="card-img-top img-responsive" src="uploads/products/no_image.jpg" alt="">
                  </a>
                <?php else: ?>
                <a href="#" onclick="f_ingred('<?php echo remove_junk($ingre['nombre']); ?>');" title="Seleccionar <?php echo remove_junk(ucfirst($ingre['size'])); ?>"> 
                    <img class="card-img-top img-responsive" src="uploads/products/<?php echo $ingre['image']; ?>" alt="">
                  </a>
                <?php endif; ?>
                <h4 class="card-title center"> <?php echo remove_junk(ucfirst($ingre['nombre']));?></h4>
                <p class="card-body"> Precio: $<?php echo remove_junk(ucfirst($ingre['price'])); ?> </p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Categoria pastas -->
        <div id="selc_pastas" class="row" style="display: none;">
          <?php foreach ($pastas as $past):?>
            <div class="col-sm-3">
              <div class="card" style="width: 16rem;">
                <?php if($past['media_id'] === '0'): ?>
                  <a href="#" onclick="f_pastas('<?php echo remove_junk($past['nombre']); ?>');" title="Seleccionar Producto"> 
                  <img class="card-img-top img-responsive" src="uploads/products/no_image.jpg" alt="">
                  </a>
                <?php else: ?>
                <a href="#" onclick="f_pastas('<?php echo remove_junk($past['nombre']); ?>');" title="Seleccionar <?php echo remove_junk(ucfirst($otr['size'])); ?>"> 
                    <img class="card-img-top img-responsive" src="uploads/products/<?php echo $past['image']; ?>" alt="">
                  </a>
                <?php endif; ?>
                <h4 class="card-title center"> <?php echo remove_junk(ucfirst($past['nombre']));?></h4>
                <p class="card-body"> Precio: $<?php echo remove_junk(ucfirst($past['price'])); ?> </p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Categoria ensaladas -->
        <div id="selc_ensaladas" class="row" style="display: none;">
          <?php foreach ($ensaladas as $ensala):?>
            <div class="col-sm-3">
              <div class="card" style="width: 16rem;">
                <?php if($ensala['media_id'] === '0'): ?>
                  <a href="#" onclick="f_ensaladas('<?php echo remove_junk($ensala['nombre']); ?>');" title="Seleccionar Producto"> 
                  <img class="card-img-top img-responsive" src="uploads/products/no_image.jpg" alt="">
                  </a>
                <?php else: ?>
                <a href="#" onclick="f_ensaladas('<?php echo remove_junk($ensala['nombre']); ?>');" title="Seleccionar <?php echo remove_junk(ucfirst($ensala['size'])); ?>"> 
                    <img class="card-img-top img-responsive" src="uploads/products/<?php echo $ensala['image']; ?>" alt="">
                  </a>
                <?php endif; ?>
                <h4 class="card-title center"> <?php echo remove_junk(ucfirst($ensala['nombre']));?></h4>
                <p class="card-body"> Precio: $<?php echo remove_junk(ucfirst($ensala['price'])); ?> </p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        
        <!-- Categoria ensaladas de frutas-->
        <div id="selc_ensaladasFrutas" class="row" style="display: none;">
          <?php foreach ($ensaladasFrutas as $ensalaF):?>
            <div class="col-sm-3">
              <div class="card" style="width: 16rem;">
                <?php if($ensalaF['media_id'] === '0'): ?>
                  <a href="#" onclick="f_ensaladasFrutas('<?php echo remove_junk($ensalaF['nombre']); ?>');" title="Seleccionar Producto"> 
                  <img class="card-img-top img-responsive" src="uploads/products/no_image.jpg" alt="">
                  </a>
                <?php else: ?>
                <a href="#" onclick="f_ensaladasFrutas('<?php echo remove_junk($ensalaF['nombre']); ?>');" title="Seleccionar <?php echo remove_junk(ucfirst($ensalaF['size'])); ?>"> 
                    <img class="card-img-top img-responsive" src="uploads/products/<?php echo $ensalaF['image']; ?>" alt="">
                  </a>
                <?php endif; ?>
                <h4 class="card-title center"> <?php echo remove_junk(ucfirst($ensalaF['nombre']));?></h4>
                <p class="card-body"> Precio: $<?php echo remove_junk(ucfirst($ensalaF['price'])); ?> </p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Categoria Otros -->
        <div id="selc_otros" class="row" style="display: none;">
          <?php foreach ($otros as $otr):?>
            <div class="col-sm-3">
              <div class="card" style="width: 16rem;">
                <?php if($otr['media_id'] === '0'): ?>
                  <a href="#" onclick="f_otros('<?php echo remove_junk($otr['nombre']); ?>');" title="Seleccionar Producto"> 
                  <img class="card-img-top img-responsive" src="uploads/products/no_image.jpg" alt="">
                  </a>
                <?php else: ?>
                <a href="#" onclick="f_otros('<?php echo remove_junk($otr['nombre']); ?>');" title="Seleccionar <?php echo remove_junk(ucfirst($otr['size'])); ?>"> 
                    <img class="card-img-top img-responsive" src="uploads/products/<?php echo $otr['image']; ?>" alt="">
                  </a>
                <?php endif; ?>
                <h4 class="card-title center"> <?php echo remove_junk(ucfirst($otr['nombre']));?></h4>
                <p class="card-body"> Precio: $<?php echo remove_junk(ucfirst($otr['price'])); ?> </p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        
        <!-- Categoria LASAGNA -->
        <div id="selc_lasagna" class="row" style="display: none;">
          <?php foreach ($lasagna as $lasag):?>
            <div class="col-sm-3">
              <div class="card" style="width: 16rem;">
                <?php if($otr['media_id'] === '0'): ?>
                  <a href="#" onclick="f_lasagna('<?php echo remove_junk($lasag['nombre']); ?>');" title="Seleccionar Producto"> 
                  <img class="card-img-top img-responsive" src="uploads/products/no_image.jpg" alt="">
                  </a>
                <?php else: ?>
                <a href="#" onclick="f_lasagna('<?php echo remove_junk($lasag['nombre']); ?>');" title="Seleccionar <?php echo remove_junk(ucfirst($lasag['size'])); ?>"> 
                    <img class="card-img-top img-responsive" src="uploads/products/<?php echo $lasag['image']; ?>" alt="">
                  </a>
                <?php endif; ?>
                <h4 class="card-title center"> <?php echo remove_junk(ucfirst($lasag['nombre']));?></h4>
                <p class="card-body"> Precio: $<?php echo remove_junk(ucfirst($lasag['price'])); ?> </p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        
        <!-- CATEGORIA PIZZAS -->
        <div id="selc_pizzas_tam" class="row" style="display: none;">
          <?php foreach ($tam_pizzas as $tam):?>
            <div class="col-sm-3">
              <div class="card" style="width: 16rem;">
                <?php if($tam['media_id'] === '0'): ?>
                  <a href="#" onclick="tam_pizzas('<?php echo remove_junk($tam['name']); ?>');" title="Seleccionar Producto"> 
                  <img class="card-img-top img-responsive" src="uploads/products/no_image.jpg" alt="">
                  </a>
                <?php else: ?>
                <a href="#" onclick="tam_pizzas('<?php echo remove_junk($tam['name']); ?>');" title="Seleccionar <?php echo remove_junk(ucfirst($tam['name'])); ?>"> 
                    <img class="card-img-top img-responsive" src="uploads/products/<?php echo $tam['image']; ?>" alt="">
                  </a>
                <?php endif; ?>
                <h4 class="card-title center"> <?php echo remove_junk(ucfirst($tam['name'])); ?> </h4>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <!-- Pizza especial o normal -->
        <div id="selc_pizzas_nor_esp" class="row justify-content-around"  style="display: none; ">
          <div class="col-md-3">
            <div class="card" style="width: 18rem;">
              <a href="#" onclick="pizzas_normal('especial');" title="Seleccionar Pizza Especial"> 
              <img class="card-img-top img-responsive" src="uploads/products/pizza_especial.png" alt="">
              </a>
              <h4 class="card-title center"> Pizza Especial </h4>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card" style="width: 18rem;">
              <a href="#" onclick="pizzas_normal('normal');" title="Seleccionar Pizza Normal"> 
              <img class="card-img-top img-responsive" src="uploads/products/pizza_normal.png" alt="">
              </a>
              <h4 class="card-title center"> Pizza Normal </h4>
            </div>
          </div>  
        </div>
        <!-- Sabor de pizza -->
        <div id="selc_pizzas_sabor" class="row justify-content-around" style="display: none;">
          <?php foreach ($sabor_pizzas as $tip) :?>
            <div class="col-md-3">
              <div class="card" style="width: 16rem;">
                <?php if($tip['media_id'] === '0'): ?>
                  <a href="#" onclick="sabor_pizza('<?php echo remove_junk($tip['name']); ?>','0','0');" title="Seleccionar Tipo"> 
                  <img class="card-img-top img-responsive" src="uploads/products/no_image.jpg" alt="">
                  </a>
                <?php else: ?>
                <a href="#" onclick="sabor_pizza('<?php echo remove_junk($tip['name']); ?>','0','0');" title="Seleccionar <?php echo remove_junk(ucfirst($tip['name'])); ?>"> 
                    <img class="card-img-top img-responsive" src="uploads/products/<?php echo $tip['image']; ?>" alt=""  style="height: 100px; display: block; margin-left: auto;margin-right: auto;">
                  </a>
                <?php endif; ?>
                <h4 class="card-title center"> <?php echo remove_junk(ucfirst($tip['name'])); ?> </h4>
                <p class="card-body"> Ingedientes: <?php echo remove_junk(ucfirst($tip['tipo_descrip'])); ?> </p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <!-- Sabor de pizza PORCION-->
        <div id="selc_pizzas_sabor_PORCION" class="row justify-content-around" style="display: none;">
          <?php foreach ($sabor_pizzas as $tip) if ($tmp++ < 4){?>
            <div class="col-md-3">
              <div class="card" style="width: 16rem;">
                <?php if($tip['media_id'] === '0'): ?>
                  <a href="#" onclick="sabor_pizza('<?php echo remove_junk($tip['name']); ?>','0');" title="Seleccionar Tipo"> 
                  <img class="card-img-top img-responsive" src="uploads/products/no_image.jpg" alt="">
                  </a>
                <?php else: ?>
                <a href="#" onclick="sabor_pizza('<?php echo remove_junk($tip['name']); ?>','0');" title="Seleccionar <?php echo remove_junk(ucfirst($tip['name'])); ?>"> 
                    <img class="card-img-top img-responsive" src="uploads/products/<?php echo $tip['image']; ?>" alt=""  style="height: 100px; display: block; margin-left: auto;margin-right: auto;">
                  </a>
                <?php endif; ?>
                <h4 class="card-title center"> <?php echo remove_junk(ucfirst($tip['name'])); ?> </h4>
                <p class="card-body"> Ingedientes: <?php echo remove_junk(ucfirst($tip['tipo_descrip'])); ?> </p>
              </div>
            </div>
          <?php } ?>
        </div>
        <!-- Ingredientes Extras -->
        <div id="selc_extra" class="row" style="display: none;" >
          <div id="selc_extra2" class="row justify-content-around">
            <?php foreach ($extra_pizzas as $extra):?>
              <div class="col-md-3">
                <div class="card" style="width: 18rem;">
                  <?php if($tip['media_id'] === '0'): ?>
                    <a href="#" onclick="ingre_extra('<?php echo remove_junk($extra['name']); ?>');" title="Seleccionar Extra"> 
                    <img class="card-img-top img-responsive" src="uploads/products/no_image.jpg" alt="">
                    </a>
                  <?php else: ?>
                  <a href="#" onclick="ingre_extra('<?php echo remove_junk($extra['name']); ?>');" title="Seleccionar <?php echo remove_junk(ucfirst($extra['name'])); ?>"> 
                      <img class="card-img-top img-responsive" src="uploads/products/<?php echo $extra['image']; ?>" alt=""   style="height: 100px; display: block; margin-left: auto;margin-right: auto;">
                    </a>
                  <?php endif; ?>
                  <h4 class="card-title center"> <?php echo remove_junk(ucfirst($extra['name'])); ?> </h4>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <div id="fun_cont_extra" class="d-flex flex-row-reverse">
            <button type="button" class="btn btn-light" style="width: auto" onclick="avanzar_extra()">
              <i class="glyphicon glyphicon-arrow-right"></i>
              Continuar
            </button>
          </div>
        </div>

        <!-- Servirse o llevar-->
        <div id="selc_pizzas_forma" class="row justify-content-around"  style="display: none; ">
          <div class="col-md-3">
            <div class="card" style="width: 18rem;">
              <a href="#" onclick="forma_servir('servirse');" title="Seleccionar Pizza Especial"> 
              <img class="card-img-top img-responsive" src="uploads/products/forma_servirse.png" alt="">
              </a>
              <h4 class="card-title center"> Para Servirse </h4>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card" style="width: 18rem;">
              <a href="#" onclick="forma_servir('llevar')" title="Seleccionar Llevar"> 
              <img class="card-img-top img-responsive" src="uploads/products/forma_llevar.png" alt="">
              </a>
              <h4 class="card-title center"> Para Llevar </h4>
            </div>
          </div>  
        </div>
        <!-- Opciones Pizza Especial -->
        <div id="selc_pizzas_especiales" class="row justify-content-around" style="display: none;">
          <?php foreach ($pizzas_espec as $especial) :?>
            <div class="col-md-3">
              <div class="card" style="width: 16rem;">
                <?php if($especial['media_id'] === '0'): ?>
                  <a href="#" onclick="sabor_pizza('<?php echo remove_junk($especial['name']); ?>','0','0');" title="Seleccionar Tipo"> 
                  <img class="card-img-top img-responsive" src="uploads/products/no_image.jpg" alt="">
                  </a>
                <?php else: ?>
                <a href="#" onclick="sabor_pizza('<?php echo remove_junk($especial['name']); ?>','0','0');" title="Seleccionar <?php echo remove_junk(ucfirst($especial['name'])); ?>"> 
                    <img class="card-img-top img-responsive" src="uploads/products/<?php echo $especial['image']; ?>" alt=""  style="height: 100px; display: block; margin-left: auto;margin-right: auto;">
                  </a>
                <?php endif; ?>
                <h4 class="card-title center"> <?php echo remove_junk(ucfirst($especial['name'])); ?> </h4>
                <p class="card-body"> Ingedientes: <?php echo remove_junk(ucfirst($especial['tipo_descrip'])); ?> </p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <!-- Pizza Personalizada -->
        <div id="selc_personalizada" class="row justify-content-around" style="display: none;">
          <form class="form-horizontal" id="formulario-perso" onsubmit="ingre_especial1();">
            <?php for ($x = 1; $x <= 4; $x++) { ?>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label" style="width: 150px;">Ingrediente <?php echo $x ?></label>
                <div class="col-md-6">
                  <select class="form-control" id="ingred_<?php echo $x ?>" style="width: 400px;">
                    <option value="">Seleccione el sabor del ingrediente</option>
                      <?php  foreach ($sabores as $sab): ?>
                        <option value="<?php echo (int)$sab['id'] ?>">
                          <?php echo ucfirst($sab['name']) ?></option>
                      <?php endforeach; ?>
                  </select>
                </div>
              </div>
            <?php }?>
            <button type="submit" class="btn btn-primary" style="width: auto">Continuar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!--Factura-->
  <div class="col-md-5">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th-list"></span>
          <span id="txtHint">Comprobante venta</span>
        </strong>
      </div>
      <div class="panel-body">
        <table id="tabla_factura" class="table table-striped table-hover table-condensed">
          <thead>
            <tr>
              <th class="text-center" style="width:10%">Cantidad</th>
              <th class="text-justify" style="width:40%">Descripción</th>
              <th class="text-center" style="width:20%">Precio</th>
              <th class="text-center" style="width:20%">Total</th>
              <th class="text-center" style="width:10%"></th>
            </tr>
          </thead>
          <tbody id="tb_factura" >
            
          </tbody>
          <tfoot>
            <tr>
              <td></td>
              <td></td>
              <th class="text-right">Subtotal</td>
              <td class="text-center" style="width: 100%;">$ <input class="text-center" id="sub_producto" name="subtotal" type="text"  style="width: 70%;" disabled value='0.00'></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <th class="text-right">IVA <input class="text-center" id="valor_iva" name="iva" type="text"  style="width: 25%;" disabled value='0'> %</td>
              <td class="text-center" style="width: 100%;">$ <input class="text-center" id="iva" name="iva" type="text"  style="width: 70%;" disabled value='0.00'></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <th class="text-right">TOTAL</td>
              <td class="text-center" style="width: 100%;">$ <input class="text-center" id="total_compra" name="total_compra" type="text"  style="width: 70%;" disabled value='0.00'></td>
            </tr>
          </tfoot>
        </table>
        <button id="cont_compra" type="button" class="btn btn-primary btn-block" onclick="f_final_compra()"style="display:none;">Elegir forma de pago</button>
        <div class="row" id="cont_vuelto" style="display: none;">
        <div class="row"  style="padding-top: 5%;">
            <div class="form-check text-center">
              <label class="form-check-label" style="margin-right: 5%;">
                <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios1" value="option1" checked onclick="forma_pago('efectivo');">
                Efectivo
              </label>
              <label class="form-check-label" style="margin-right: 5%;">
                <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios2" value="option2" onclick="forma_pago('tarjeta');">
                Tarjeta
              </label>
              <!-- <label class="form-check-label" style="margin-right: 5%;">
                <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios3" value="option3" onclick="forma_pago('domicilio');">
               Domicilio
              </label>-->
              <label class="form-check-label" style="margin-right: 5%;">
                <input type="radio" class="form-check-input" name="optionsRadios" id="optionsRadios4" value="option4" onclick="forma_pago('uber');">
                Uber
              </label>
            </div>
          </div>
          <!-- Tabla seleccion DOMICILIO -->
          <div class="panel panel-info" id="panel_domi" style="display: ; margin-left: 3em;">
            <div class="panel-heading" id="panel_header_domi" style="display: ;">
              <strong>
                <input type="checkbox" id="chk_domicilio" name="chk_domicilio" onclick="func_domicilio()">
                <label> DOMICILIO</label>
                <span class="glyphicon glyphicon-hand-left"  style="float:right;"></span>
              </strong>
            </div>
            <div class="panel-body" id="panel_body_domi"  style="display: none;">  
              <table class="table table-striped table-hover table-condensed">
                <tbody> 
                  <tr><td class="text-right"> Valor Domicilio</td><td class="text-center">$ <input id="in_domicilio" class="text-center"  type="number" value="0.00" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" min="0" style="width: 30%;" onchange="actu_domicilio()"></td></tr>
                </tbody>
              </table>     
            </div>
          </div>
          <!-- Imagen de tarjeta -->
          <img class="card-img-top img-responsive pb-2" id="im_tarjeta" src="fotos/tarjeta.png" alt="" style="width: 30%; margin:auto; display: none;">
          <div class="panel-body" id="tabla_tarjeta" style="display: none;">
            <table class="table table-striped table-hover table-condensed">
              <tbody> 
                <tr><td class="text-right">Subtotal</td><td class="text-center">$ <input readonly id="sub_tarjeta" class="text-center" type="number" value="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" style="width: 25%;" disabled></td></tr>
                <tr><td class="text-right" style="color: #00AEEF;">Cargo Adicional </td><td class="text-center" style="color: #1C5DB3;">$ <input id="cargo_tarjeta" class="text-center"  type="number" step="0.01" value="0.00" pattern="^\d+(?:\.\d{1,2})?$" min="0"  onchange="actu_tarjeta()" style="width: 25%; background-color: #A1C0E8;"></td></tr>
                <tr><td class="text-right" >TOTAL</td><td class="text-center">$ <input readonly id="total_tarjeta" class="text-center"  type="number" value="0.00" pattern="^\d+(?:\.\d{1,2})?$" min="0" style="width: 30%;" disabled></td></tr>
              </tbody>
            </table>
          </div>
          <!-- Tabla de efectivo -->
          <div class="panel-body" id="tabla_vuelto">
            <table class="table table-striped table-hover table-condensed">
              <tbody> 
                <tr><td class="text-right" style="color:#0099ff; text-align:center; font-weight:bold; ">TOTAL</td><td class="text-center">$ <input id="total_efectivo" class="text-center"  type="number" value="0.00" pattern="^\d+(?:\.\d{1,2})?$" min="0" style="width: 25%; background-color: #81BEF7;" disabled></td></tr>
                <tr><td class="text-right">Efectivo</td><td class="text-center">$ <input id="in_efectivo" class="text-center" type="number" value="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" style="width: 25%;" onkeyup="actu_vuelto()"></td></tr>
                <tr><td class="text-right">Vuelto</td><td class="text-center">$ <input id="in_vuelto" class="text-center"  type="number" value="0.00" pattern="^\d+(?:\.\d{1,2})?$" min="0" style="width: 25%;" disabled></td></tr>
              
              </tbody>
            </table>
          </div>
          <!-- Tabla de Uber -->
          <img class="card-img-top img-responsive pb-2" id="im_uber" src="uploads/products/logoUber.png" alt="" style="width: 30%; margin:auto; display: none;">
          <div class="panel-body" id="tabla_uber" style="display: none;">
            <table class="table table-striped table-hover table-condensed">
              <tbody> 
                <tr><td class="text-right">Valor</td><td class="text-center">$ <input id="sub_uber" class="text-center" type="number" value="0.00" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" style="width: 25%;" disabled></td></tr>
                <tr><td class="text-right" style="color: #1AAA13;">Precio Uber</td><td class="text-center" style="color: #0D4809;">$ <input id="total_uber" class="text-center;"  type="number" value="0.00" pattern="^\d+(?:\.\d{1,2})?$" min="0" onchange="actu_uber()" style="width: 25%; background-color: #A6D2A4;"></td></tr>
              </tbody>
            </table>
          </div>
          <div class="text-center">
            <button id="f_continuar" type="button" class="btn btn-success" onclick="f_continuar(1)" >Finalizar Compra</button>
            <a style="visibility:hidden;">aaaa</a>
            <button id="f_cancelar" type="button" class="btn btn-danger " onclick="f_continuar(0)" >Cancelar Compra</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="realizar_venta.js"></script>


<?php include_once('layouts/footer.php'); ?>
