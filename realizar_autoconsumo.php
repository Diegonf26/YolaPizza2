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
  
   //categoria Bebidas
   $bebidas=join_bebidas_table();
   $ingredientes=join_ingredientes_table();

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

?>


<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-6">
    <?php echo display_msg($msg); ?>
   </div>
</div>
  <!--.......Cuadrados de visualizacion......-->
  <div class="row">
  <div class="container">
    <span class="badge badge-success" style="background-color:#AE1D01; color:white; font-weight:bold; margin-bottom: 1%;"><h4> AUTOCONSUMO </h4></span>
   </div>
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
            </div>
          </div>

          <div class="col-sm-3">
            <div class="card" style="width: 16rem; ">
              <a href="#" onclick="f_caja('familiar');" title="Seleccionar Caja Grande"> 
                <img class="card-img-top img-responsive" src="uploads/products/cajaPizza_grande.png" alt="">
              </a>
              <h4 class="card-title center"> Caja Grande </h4>
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
        
        <!-- Presentacion de opciones -->
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
          <?php foreach ($sabor_pizzas as $tip) if ($tmp++ < 3){?>
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
          <form class="form-horizontal" onsubmit="ingre_especial();">
            <?php for ($x = 1; $x <= 4; $x++) { ?>
              <div class="form-group row">
                <label class="col-sm-2 col-form-label" style="width: 150px;">Ingrediente <?php echo $x ?></label>
                <div class="col-md-6">
                  <select class="form-control" id="ingred_<?php echo $x ?>" style="width: 400px;">
                    <option value=""><? echo $selec?> </option>
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
        <button id="cont_compra" type="button" class="btn btn-success btn-block" onclick="f_final_compra()"style="display:none;">Finalizar Autoconsumo</button>
      </div>
    </div>
  </div>
</div>

<script >
  var venta_aux=[];
  var venta_aux_extra=[];
  var item_eliminados=[];
  var categ, p_tama, p_tipo, p_extras, p_forma, p_pago, pizza_vent='0';
  var fila_id = 0;
  var num_extras = 0;
  var str_extra="";
  var pagoTotal=1; 
  var p_id_pizza='';      //Informacion de id de la pizza a la cual corresponden los extras
  var caja_mediana=0;
  var caja_grande=0;
  var DOMAIN = "http://localhost/Pizzeria/";

  var titu_regre=document.getElementById("titulo_regresar"); //Titulo regresar 
  var btn_regre=document.getElementById("btn_regresar");    //Boton regresar
  var btn_cont=document.getElementById("cont_compra");    //Boton continuar con la compra

//------------------------------------------------------------------------------------------------
    
  function selec_categ(nombre_cat) {
    var g=document.getElementById("cont_categ"); //Cotenedor catego boqueo selecion
    var regr=document.getElementById("funcion_regresar"); //Funcion regresar
    centrar(regr);
    //Ajustar boton regresar
    regr.style.justifyContent= 'left';
    regr.style.paddingLeft= '3%';

    btn_regre.style.display = 'flex';

    if(nombre_cat=="Pizzas"){
      var e = document.getElementById("selc_pizzas_nor_esp"); //Sig Pizzas tipo
      var f = document.getElementById("selc_pizzas_tam"); //Actual Pizzas Tamano
      //Cetrar layout a visualizar
      centrar(f);
      //Regresar a pantalla anterior
      e.style.display = 'none';
      //titulo de categoria
      titu_regre.innerText = "Seleccione el tamaño de pizza";
      pizza_vent=0;   //Ventana de tamano
    }
    else if (nombre_cat=="Bebidas") {
      var f = document.getElementById("selc_bebidas");   //Actual
      centrar(f);
      //titulo de categoria
      titu_regre.innerText = "Seleccione bebida";
      pizza_vent=5; 
    }
    
    else if (nombre_cat=="Ingredientes") {
      var f = document.getElementById("selc_ingredientes");   //Actual
      centrar(f);
      //titulo de categoria
      titu_regre.innerText = "Seleccione ingrediente";  
      pizza_vent=6;  
    }
    
    else if (nombre_cat=="Cajas Pizza") {
      var f = document.getElementById("selc_cajas");   //Actual
      centrar(f);
      //titulo de categoria
      titu_regre.innerText = "Seleccione Caja Pizza";  
      pizza_vent=7;  
    }
    categ=nombre_cat;
    g.style.pointerEvents="none";   //Bloqueo de categoria
    
    btn_cont.disabled=true;   //Bloqueo boton contiuar compra
  }
  //---------- Categoria PIZZAS --------------
  // 0=> Ventana de tamano
  // 1=> Ventana de tipo
  // 2=> Ventana de sabor
  // 3=> Ventana de extra 

  //-1)---Tamano PIZZA
  function tam_pizzas(tama){
    p_tama=tama;
    var f = document.getElementById("selc_pizzas_tam"); //SE CIERRA:ventana actual

    if(tama!="porcion"){
      var e = document.getElementById("selc_pizzas_nor_esp"); //SE ABRE:ventana se abre
      var g = document.getElementById("selc_pizzas_sabor");  //SE CIERRA:ventana siguiente en regreso
      var g2 = document.getElementById("selc_pizzas_especiales");  //SE CIERRA:ventana siguiente en regreso
      
      centrar(e);
      f.style.display = 'none';
      g.style.display = 'none';
      g2.style.display = 'none';
      // g3.style.display = 'none';
      pizza_vent=1;   //Ventana de especial o normal
    }
    else{
      f.style.display = 'none';
      pizzas_normal(tama);
    }
    //titulo de categoria
    titu_regre.innerText = "Seleccione el tipo de pizza";
    //Inicializacion parametros extra
    num_extras=0;
    str_extra="";
  }

  //-2)---Tipo PIZZA
  function pizzas_normal(tipo){
    var f = document.getElementById("selc_pizzas_nor_esp"); //SE CIERRA:ventana actual
    var g = document.getElementById("selc_extra"); //SE CIERRA:ventana siguiente en regreso
    if (tipo=="porcion") 
      var e = document.getElementById("selc_pizzas_sabor_PORCION");    //SE ABRE:ventana se abre 
    else if (tipo=="normal")
      var e = document.getElementById("selc_pizzas_sabor");    //SE ABRE:ventana se abre
    else
      var e = document.getElementById("selc_pizzas_especiales");    //SE ABRE:ventana se abre
    centrar(e);
    var g3 = document.getElementById("selc_personalizada"); 
    f.style.display = 'none';
    g.style.display = 'none';
    g3.style.display = 'none';
    p_tipo=tipo;
    //titulo de categoria
    titu_regre.innerText = "Seleccione el sabor de pizza";
    pizza_vent=2;   //Ventana de sabor
  }

  //-3)---Sabor PIZZA
  function sabor_pizza(tipo,on_regres,ingre_especial){
    var precio=0;
    if (on_regres==0 && tipo!="personalizada") {
      if(tipo.search("personalizada")!=(-1)){
        p_sabor="personalizada";              //Determinar piza especial sin ingredien
        str_extra+=(ingre_especial.toString())+",";
      }
      else
        p_sabor=tipo;
      //Requrimiento de precio a BD se demora mas que la siguiente linea secuencial
      $.ajax({url: DOMAIN+"buscar_precio.php?p_tama="+p_tama+"&p_tipo="+p_tipo+"&p_sabor="+p_sabor, success: function(result){
        precio=Number(result);
        var descrip= categ+" "+p_tama+" "+p_tipo+" "+tipo;
        agregar_fila(descrip,precio);

        //Guardar venta ---------------------------------------------------------------------------
        var venta_pizza={id:fila_id,categ:categ,canti:1,tama:p_tama,tipo:p_tipo,sabor:p_sabor,extra:str_extra,forma:"123",precioP:precio,fpago:"pago"};
        venta_aux.push(venta_pizza); 
      }}); 
    }
    if(tipo=="personalizada"){
      var e = document.getElementById("selc_personalizada");
      //Titulo de ventana
      titu_regre.innerText = "Seleccione ingredientes:";
    }
    else{
      var e = document.getElementById("selc_extra");
      var selc_personalizada = document.getElementById("selc_personalizada");
      selc_personalizada.style.display = 'none'; 
      //Titulo de ventana
      titu_regre.innerText = "¿Desea algún ingrediente extra?"; 
      btn_regre.style.display = 'none';     //Desaparecer boton regresar de ingredientes extras
    }

    var sabor_porcion = document.getElementById("selc_pizzas_sabor_PORCION");
    var extra = document.getElementById("selc_extra2");
    var continuar = document.getElementById("fun_cont_extra");
    
    var f = document.getElementById("selc_pizzas_sabor");
    var f2 = document.getElementById("selc_pizzas_especiales");
    var g = document.getElementById("selc_pizzas_forma"); //Ven sig cierra REGRESAR
    centrar(e);
    centrar(extra);
    centrar(continuar);
    f.style.display = 'none';
    f2.style.display = 'none';
    g.style.display = 'none';
    sabor_porcion.style.display = 'none';
    pizza_vent=3;   //Ventana de servir
    p_id_pizza=(fila_id+1);     //Corrspondencia de pizza con extras
  }

  function ingre_extra(extra){
    num_extras++;
    //Buscar precios de extras y cracion de fila en nota de venta
    $.ajax({url: DOMAIN+"buscar_precio_extra.php?p_tama="+p_tama+"&p_extra="+extra, success: function(result){
      // alert(result);
      precio=Number(result);
      var descrip= "Extra "+extra+" en pizza "+p_tama;
      agregar_fila(descrip,precio);
      //Objeto para almacenar extras en tabla de comprobante
      var venta_extra={id:fila_id,id_pizza:p_id_pizza,categ:"Extra",canti:1,tama:p_tama,p_extra:extra,precioP:precio};
      venta_aux.push(venta_extra);
      //Array paracargar a BD con ingredientes extras repetidos
      var v_extra={id:fila_id,id_pizza:p_id_pizza,p_extra:extra};
      venta_aux_extra.push(v_extra);
      //Bloquear
      document.getElementById("canti_"+fila_id).disabled = true;
    }});
    
  }

  function avanzar_extra() {
    var e = document.getElementById("selc_pizzas_forma");
    var f = document.getElementById("selc_extra");
    centrar(e);
    f.style.display = 'none';
    pizza_vent=4;   //Ventana de servir
  }

  function forma_servir(forma) {
    p_forma=forma; 
    venta_aux.forEach(element => {
      if (element.id==(Number(fila_id-num_extras))) {   //Es necesario contar el numero de xtras porq tambien generan filas
        element.forma=p_forma;
      }
    });

    if (p_forma=="llevar"  && !(p_tama=="porcion")) {
      var descrip="Caja Pizza "+p_tama;
      if(p_tama=="mediana")
        var precio_caja=1;
      else 
        var precio_caja=1.25;
      
      agregar_fila(descrip, precio_caja);
      var venta_forma={id:fila_id,id_pizza:p_id_pizza,categ:"Caja_pizza",canti:1,tama:p_tama,precioP:precio_caja};
      venta_aux.push(venta_forma);
      document.getElementById("canti_"+fila_id).disabled = true;
    }
    //Quitar  el contenedor al finalizar
    var e = document.getElementById("selc_pizzas_forma");
    var regr=document.getElementById("funcion_regresar"); 
    var g = document.getElementById("cont_categ");
    e.style.display = 'none';
    regr.style.display = 'none';
    g.style.pointerEvents="auto"; //Habilitar pulsacion
    centrar(btn_cont);
    btn_cont.disabled=false;   //Desbloqueo boton contiuar compra
    //Titulo de ventana
    titu_regre.innerText = "La pizza es para:";
    //Deshabilitar el contador de caja para actulizacion automatica
  }


  function centrar(id){
    id.style.display = 'flex';
    id.style.paddingTop='2%';
    id.style.alignItems='center';
    id.style.flexWrap= 'wrap';
    id.style.justifyContent= 'center';
  }

  function regresar_carac(){
    var r = document.getElementById("funcion_regresar");
    var z=document.getElementById("cont_categ");
    switch (pizza_vent) {
      case 1:     //Ventana de tipo
        selec_categ(categ);   
        break;
      case 2:     //Ventana de sabor
        if(p_tama=="porcion"){
          var g = document.getElementById("selc_pizzas_sabor_PORCION");
          selec_categ(categ);
          g.style.display = 'none';
        } 
        else
          tam_pizzas(p_tama);
        break;
      case 3:     //Ventana de extra
        pizzas_normal(p_tipo);
        break;
      case 4:     //Ventana de servirse
        sabor_pizza(p_sabor,1,0);   //1 determina que esta regresando a la venta anterior
        break;
      case 0:
        var g = document.getElementById("cont_categ");
        var f = document.getElementById("selc_pizzas_tam");
        
        g.style.pointerEvents="auto"; //Habilitar categorias
        btn_cont.disabled=false;      //Habilitar continuar compra
        f.style.display = 'none';     //Desaparecer caracteristicas pizzas
        r.style.display = 'none';
        break;
      case 5:       //Regresar bebidas
        var g = document.getElementById("selc_bebidas");
        r.style.display = 'none';
        g.style.display="none";
        z.style.pointerEvents="auto"; //Habilitar pulsacion
        btn_cont.disabled=false;
        break;

      case 6:       //Regresar ingredientes
        var f = document.getElementById("selc_ingredientes");
        r.style.display = 'none';
        f.style.display="none";
        z.style.pointerEvents="auto"; //Habilitar pulsacion
        btn_cont.disabled=false;
        break;

      case 7:       //Regresar Caja Pizza
        var f = document.getElementById("selc_cajas");
        r.style.display = 'none';
        f.style.display="none";
        z.style.pointerEvents="auto"; //Habilitar pulsacion
        btn_cont.disabled=false;
        break;
    }
  }

  function eliminar_fila(tr_id) {
    //Eliminar fila de comprobante de venta
    $('#tabla_factura tbody tr#'+tr_id).remove();     //Eliminar fila  de tabla
    item_eliminados.push(tr_id);
    //Eliminar adicionales de pizza eliminadasfor
    venta_aux.forEach(element => {
      if (element.id==tr_id) {
        if(element.categ=="Pizzas"){
          venta_aux.forEach(elem_relac => {
            if(elem_relac.id_pizza==tr_id){
              $('#tabla_factura tbody tr#'+elem_relac.id).remove();
              item_eliminados.push(elem_relac.id);
            }
          });
        }
        else if(element.categ=="Caja_pizza"){
          venta_aux.forEach(elem_relac2 => {
            //Eliminar pizzas vinculadas con caja
            if(elem_relac2.categ=="Pizzas"){
              if(elem_relac2.id==element.id_pizza){
                $('#tabla_factura tbody tr#'+elem_relac2.id).remove();
                item_eliminados.push(elem_relac2.id);
              }
            }
            //Eliminar adicionales de pizzas vinculadas con caja
            else if(elem_relac2.id_pizza==element.id_pizza){
              $('#tabla_factura tbody tr#'+elem_relac2.id).remove();
              item_eliminados.push(elem_relac2.id);
            }
          });
          element.id_pizza=0;
        }
      }
    });
    //alert(item_eliminados);
    sum_productos();
  }

  function actu_precio(id){
    var cantidad=document.getElementById('canti_'+id).value;
    var precio=document.getElementById('precio_'+id).value;
    var total=(cantidad*precio).toFixed(2);
    document.getElementById('total_'+id).value=total;

    //Busqueda en array de productos
    venta_aux.forEach(element => {
      if (element.id==(Number(id))) {
        element.canti=cantidad;
        element.precioP=total;
        // alert(total);

        //Cambio automatico # de caja para llevar
        if(element.categ=="Pizzas"){
          venta_aux.forEach(caja => {
            if(caja.id_pizza==(Number(id))){
              //alert(caja.id_pizza);
              caja.canti=cantidad;
              precio=document.getElementById('precio_'+caja.id).value;
              caja.precioP=(cantidad*precio).toFixed(2);
              //ACtualizacion en nota de venta
              document.getElementById('total_'+caja.id).value=caja.precioP;
              document.getElementById('canti_'+caja.id).value=cantidad;
            }
          });
        }
      }
    });

    //Busqueda en Extras para repetirlo
    var aux_string="";
    venta_aux_extra.forEach(elementEx => {
      if(elementEx.id==(Number(id))){
        var ingre= Array.from(new Set(elementEx.p_extra.split(','))).toString();    //eliminar elementos repetidos de string
        aux_string=ingre;
        for(var j=1;j<cantidad;j++){      //cargar el numero de veces que el extra a sido colocado
          aux_string=aux_string+","+ingre;
        }
        elementEx.p_extra=aux_string;
        //alert(elementEx.p_extra);
      }
    });
    sum_productos();
  }

  function sum_productos() {
    var porc_iva=Number(document.getElementById('valor_iva').value)/100;
    var sum=0;
    for (i=1; i<=fila_id; i++) {
      if (document.getElementById('total_'+i)!=null) {
        var total=document.getElementById('total_'+i).value;
        sum+=Number(total);
      }
    }
    document.getElementById('sub_producto').value=sum.toFixed(2); 
    document.getElementById('iva').value=(porc_iva*sum).toFixed(2);
    var valor_iva=Number(document.getElementById('iva').value);
    document.getElementById('total_compra').value=(valor_iva+sum).toFixed(2);
  }

  function agregar_fila(descrip, prec) {
    fila_id++;
    var newRow = $("<tr id="+fila_id+">");
    var cols = "";
    cols += '<td class="text-center" style=width: 100%;"><input id="canti_'+fila_id+'" name="cantidad" type="number" value="1" min="1" style="width: 60%;" onchange="actu_precio('+fila_id+')"></td>';
    cols += '<td class="text-justify" style=width: 100%;">'+descrip+'</td>';
    cols += '<td class="text-center" style=width: 100%;">$ <input class="text-center" id="precio_'+fila_id+'" name="precio" type="text" style="width: 70%;" disabled value='+prec.toFixed(2)+'></td>';
    cols += '<td class="text-center" style=width: 100%;">$ <input class="text-center" id="total_'+fila_id+'" name="total" type="text"  style="width: 70%;" disabled value='+prec.toFixed(2)+'></td>';
    cols += '<td class="text-center" style=width: 100%;"> <span onclick="eliminar_fila('+fila_id+')"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Eliminar"><span class="glyphicon glyphicon-trash"></span></span></td>';

    newRow.append(cols);
    $("#tabla_factura").append(newRow);
    
    sum_productos();
    
  }

  function f_final_compra(){
    var nFilas = $("#tabla_factura tbody tr").length;
    if (nFilas==0) {
      alert("No existen productos en autoconsumo");
      location.reload();
    }
    else{
      f_continuar();
    }
  }

  function ingre_especial(){
    var ingre=0;
    var ingre_esp=[];
    for(k=1;k<=4;k++){
      var e = document.getElementById("ingred_"+k);
      var strUser = e.options[e.selectedIndex].text;
      if(strUser!='<? echo $selec?>'){
        ingre++;
        ingre_esp.push(strUser);        //Ingresar ingredientes de personalizada en array ingre_esp
      }
    }
    if(ingre>=2){
      var str_esp= 'personalizada:';
      for(l=0;l<ingre_esp.length;l++)
        str_esp+=("1/"+ingre+" "+ingre_esp[l]+",");
      sabor_pizza(str_esp,0,ingre_esp);
    }
    else{
      ingre_esp=[];
      alert("Numero de ingredientes insuficientes");
    }
    event.preventDefault();     //Evitar refresh de pagina tras submit
  }

  //---------------------------------------------------------------------------

  function f_bebidas(size, flavor){
    var f = document.getElementById("selc_bebidas");   //Actual
    var r = document.getElementById("funcion_regresar");
    var g = document.getElementById("cont_categ");
    g.style.pointerEvents="auto"; //Habilitar pulsacion
    f.style.display="none";
    r.style.display = 'none';
    
    // Buscar precios de extras y cracion de fila en nota de venta

    $.ajax({url: DOMAIN+"buscar_precio_bebidas.php?p_size="+size+"&p_flavor="+flavor, success: function(result){
      precio=Number(result);
      var descrip= size+" de "+flavor;
      agregar_fila(descrip,precio);
      var venta_bebida={id:fila_id,categ:"Bebida",canti:1,tama:size,sabor:flavor,precioP:precio};
      venta_aux.push(venta_bebida);
      centrar(btn_cont);
      btn_cont.disabled=false;  //Desbloqueo boton contiuar compra
    }});
  }

  function f_ingred(nombre){
    var f = document.getElementById("selc_ingredientes");   //Actual
    var r = document.getElementById("funcion_regresar");
    var g = document.getElementById("cont_categ");
    g.style.pointerEvents="auto"; //Habilitar pulsacion
    f.style.display="none";
    r.style.display = 'none';

    // alert(nombre);
    $.ajax({url: DOMAIN+"buscar_precio_ingredi.php?p_nombre="+nombre, success: function(result){
      // alert(result);
      precio=Number(result);
      var descrip= nombre.replace(/(^|\s)\S/g, l => l.toUpperCase());         //Poner en mayuscula primera letra
      agregar_fila(descrip,precio);
      var venta_ingre={id:fila_id,categ:"Ingredientes",canti:1,v_nombre:nombre,precioP:precio};
      venta_aux.push(venta_ingre);
      centrar(btn_cont);
      btn_cont.disabled=false;   //Desbloqueo boton contiuar compra
    }});
  }

  function f_caja(tam){
    var descrip="Caja Pizza "+tam;
    if(tam=='mediana')
      var precio_caja=1;
    else 
      var precio_caja=1;
    
    agregar_fila(descrip, precio_caja);
    var venta_forma={id:fila_id,categ:"Caja_pizza",canti:1,tama:tam,precioP:precio_caja};
    venta_aux.push(venta_forma);

    //Quitar  el contenedor al finalizar
    var e = document.getElementById("selc_cajas");
    var regr=document.getElementById("funcion_regresar"); 
    var g = document.getElementById("cont_categ");
    e.style.display = 'none';
    regr.style.display = 'none';
    g.style.pointerEvents="auto"; //Habilitar pulsacion
    centrar(btn_cont);
    btn_cont.disabled=false;   //Desbloqueo boton contiuar compra
    //Titulo de ventana
    titu_regre.innerText = "Escoja la caja a vender:";
  }

  function f_continuar(){
    var aux=0;            //Auxiliar q permite determinar si se debe cargar los datos a la  BD o no
    var total=document.getElementById('total_compra').value;

    p_pago="autoconsumo";

    var user = "<?php echo $user['username']; ?>";        //Determinar el usuario que ejecuta la venta

    //CARGAR A BD DE VENTA PIZZAS
    var aux_extra=0;    //Aux que cuenta el numero de extras
    str_extra="";
    if(aux==0){
      venta_aux.forEach(element => {
        var aux_eli=item_eliminados.indexOf(element.id);
        if(aux_eli<0){
          //alert(element.id);
          if(element.categ=="Pizzas"){
            //Agregar los extras a la pizza actualemente seleccionada
            venta_aux_extra.forEach(ele_extra => {
              if(ele_extra.id_pizza==element.id){     //Verificar si pertenece a la pizza
                var aux_eli_extra=item_eliminados.indexOf(ele_extra.id);    //Verificar si no ha sido eliminada
                if(aux_eli_extra<0){                                        //Verificar si no ha sido eliminada
                  str_extra+=(ele_extra.p_extra+",");
                  //alert(str_extra);
                  aux_extra++;
                }
              }
            });

            var str_e=""
            if(aux_extra==0){
              if(element.extra!="")
                str_e=element.extra.slice(0, -1);     //Elimina la coma de sabores de personalozada
            }       
            else
              var str_e=element.extra+str_extra.slice(0, -1);         //Elimina la coma

            $.ajax({url: DOMAIN+"guardar_ventas.php?p_canti="+element.canti+"&p_tama="+element.tama+"&p_tipo="+element.tipo+"&p_sabor="+element.sabor+"&p_extras="+str_e+"&p_forma="+element.forma+"&p_precio="+element.precioP+"&p_pago="+p_pago+"&p_usuario="+user
            });
            str_extra="";
          }
          else if(element.categ=="Bebida"){
            $.ajax({url: DOMAIN+"guardar_ventas_bebida.php?p_canti="+element.canti+"&p_tama="+element.tama+"&p_sabor="+element.sabor+"&p_precio="+element.precioP+"&p_usuario="+user+"&p_forma="+p_pago
            });
          }
          else if(element.categ=="Ingredientes"){
            $.ajax({url: DOMAIN+"guardar_ventas_ingredientes.php?p_canti="+element.canti+"&p_nombre="+element.v_nombre+"&p_precio="+element.precioP+"&p_usuario="+user+"&p_forma="+p_pago
            });
          }
          else if(element.categ=="Caja_pizza"){
            //var venta_forma={id:fila_id,categ:"Caja_pizza",canti:1,tama:tam,precioP:precio_caja};
            $.ajax({url: DOMAIN+"guardar_ventas_cajas.php?p_canti="+element.canti+"&p_tama="+element.tama+"&p_precio="+element.precioP+"&p_usuario="+user
            });
          }
        }
      });

      //GUARDAR CONSUMOS APROXIMADOS
  //     <?php foreach ($products as $prod) :?>
  //     $.ajax({url: DOMAIN+"guardar_invent_aprox.php?p_nombre="+'<?php echo remove_junk($prod['name']); ?>'+"&p_cantidad="+4
  //     });
  // <?php endforeach; ?>
      // $.ajax({url: DOMAIN+"guardar_invent_aprox.php?p_producto="+'Cajas Medianas'+"&p_cantidad="+4   //Guardar en BD aproximados
      // });

      var totalCompra=document.getElementById('total_compra').value;

      var date = "<?php echo make_date(); ?>";
      var d = new Date();
      var date1=d.getFullYear().toString()+"_"+d.getMonth().toString()+"_"+d.getDate().toString()+"_"+d.getHours().toString()+"_"+d.getMinutes().toString();
      
      var servir=1; //0 llevar, 1 servirse
      
      var numorden='<?php echo $contador?>';

      //guarda venta general y el contador
      $.ajax({url: DOMAIN+"realizar_z.php?"+"user="+user+"&"+"date="+date+"&"+"subtotal="+totalCompra});
      
      
      window.open(DOMAIN+"admin.php?p_aproximado=0","_self");
    }   
  }

</script>

<?php include_once('layouts/footer.php'); ?>

