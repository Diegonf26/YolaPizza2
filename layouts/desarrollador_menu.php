<ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
  <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
  <li class="sidebar-toggler-wrapper hide">
    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
    <div class="sidebar-toggler"> </div>
    <!-- END SIDEBAR TOGGLER BUTTON -->
  </li>

  <li class="nav-item start ">
    <a href="admin.php" class="nav-link">
      <i class="glyphicon glyphicon-home"></i>      <!--Iconos de boostrap ver: https://getbootstrap.com/docs/3.3/components/-->
      <span class="title">Panel de control</span>
      <!--span class="selected"></span-->
      <!--span class="arrow open"></span-->
    </a>
  </li>
  <li  class="nav-item start">
    <a href="users.php"  class="nav-link nav-toggle">
      <i class="glyphicon glyphicon-user"></i>
      <span class="title">Administrador de usuarios</span>
    </a>
    <!--ul class="sub-menu">
      <li class="nav-item start "><a class="nav-link " href="group.php">Administrar grupos</a> </li>
      <li class="nav-item start "><a class="nav-link "href="users.php">Administrar usuarios</a> </li>
   </ul-->
  </li>
  <!--li>
    <a href="categorie.php" >
      <i class="glyphicon glyphicon-indent-left"></i>
      <span class="title">Categorías</span>
    </a>
  </li-->
  <!--Lista de productos a vender-->
  <li  class="nav-item start">
    <a href="#"  class="nav-link nav-toggle">
      <i class="glyphicon glyphicon-shopping-cart"></i>
      <span class="title">Catalogo de productos</span>
    </a>
    <ul class="sub-menu">
      <li class="nav-item start "><a class="nav-link "href="catalogo_pizzas.php">Pizzas</a> </li>
      <li class="nav-item start "><a class="nav-link "href="catalogo_bebidas.php">Bebidas</a> </li>
      <li class="nav-item start "><a class="nav-link "href="catalogo_extras.php">Extras</a> </li>
   </ul>
  </li>
  <!--Iventario-->
  <li  class="nav-item start">
    <a href="#"  class="nav-link nav-toggle">
      <i class=" glyphicon glyphicon-check"></i>
      <span class="title">Inventario</span>
    </a>
    <ul class="sub-menu">
      <li class="nav-item start "><a class="nav-link "href="product.php?hello=0">Manejo de inventario</a> </li>
      <li class="nav-item start "><a class="nav-link "href="product_update.php">Actualización de inventario</a> </li>
      <li class="nav-item start "><a class="nav-link "href="product_report.php">Reportes de inventario</a> </li>

   </ul>
  </li>

  <li  class="nav-item start">
    <a href="proveedores.php"  class="nav-link">
      <i class="glyphicon glyphicon-tasks"></i>
      <span class="title">Proveedores</span>
    </a>
  </li>

  <li  class="nav-item start">
    <a href="media.php"  class="nav-link">
      <i class="glyphicon glyphicon-picture"></i>
      <span class="title">Media</span>
    </a>
  </li>

  <!--li  class="nav-item start">
    <a href="sales.php" class="nav-link nav-toggle"  class="nav-link">
      <i class="glyphicon glyphicon-th-list"></i>
       <span class="title">Registro de ventas</span>
      </a>
  </li-->
  <!-- Realizar una Nueva Venta -->
  <?php $user = current_user();?> 
  <?php if($user['bloqueocaja']==true):?>

  <li  class="nav-item start">
    <a href="realizar_venta.php" class="nav-link">
      <i class="glyphicon glyphicon-tags"></i>      <!--Iconos de boostrap ver: https://getbootstrap.com/docs/3.3/components/-->
      <span class="title">Nueva venta</span>
    </a>
  </li>
  <!-- Realizar Autoconsumo -->
  <li  class="nav-item start">
    <a href="realizar_autoconsumo.php" class="nav-link">
      <i class="glyphicon glyphicon-eye-close"></i>      <!--Iconos de boostrap ver: https://getbootstrap.com/docs/3.3/components/-->
      <span class="title">Autoconsumo  </span>
    </a>
  </li>

  <!-- Realizar Pizzas Escuelas-->
  <li  class="nav-item start">
    <a href="add_escuelas.php" class="nav-link">
      <i class="glyphicon glyphicon-blackboard"></i>      <!--Iconos de boostrap ver: https://getbootstrap.com/docs/3.3/components/-->
      <span class="title">Pizzas escuelas  </span>
    </a>
  </li>

<?php endif;?>


  <li class="nav-item start">
    <a href="#" class="nav-link nav-toggle">
      <i class="glyphicon glyphicon-signal"></i>
       <span class="title">Reporte de ventas</span>
      </a>
      <ul class="sub-menu">
        <li class="nav-item start "><a class="nav-link "href="sales_report.php">Ventas por fecha </a></li>
        <li class="nav-item start "><a class="nav-link "href="sales_montly.php">Ventas mensuales</a></li>
        <li class="nav-item start "><a class="nav-link "href="sales_daily.php">Ventas diarias</a> </li>
      </ul>
  </li>

  <!--Apertura y cierre de caja-->

  <?php if($user['bloqueocaja']==false):?>
    <li  class="nav-item start">
      <a href="caja_apertura.php" class="nav-link">
        <i class="glyphicon glyphicon-folder-open"></i>      <!--Iconos de boostrap ver: https://getbootstrap.com/docs/3.3/components/-->
        <span class="title">Apertura de caja</span>
      </a>
    </li>
  <?php else:?>
    <li  class="nav-item start">
      <a href="caja_cierre.php" class="nav-link">
        <i class="glyphicon glyphicon-folder-close"></i>      <!--Iconos de boostrap ver: https://getbootstrap.com/docs/3.3/components/-->
        <span class="title">Cierre de caja</span>
      </a>
    </li>
  <?php endif;?>



  <li  class="nav-item start">
    <a href="#" class="nav-link nav-toggle">
      <i class="glyphicon glyphicon-folder-close"></i>
       <span class="title">Reportes de cierres</span>
      </a>
      <ul class="sub-menu">
        <li class="nav-item start "><a class="nav-link "href="caja_cierre_general.php">Cierres por fecha </a></li>
        <li class="nav-item start "><a class="nav-link "href="caja_cierre_monthly.php">Cierres mensuales</a></li>
        <li class="nav-item start "><a class="nav-link "href="caja_cierre_daily.php">Cierres diarias</a> </li>
      </ul>
  </li>

  <?php if($user['bloqueocaja']==true):?>
    <li  class="nav-item start">
      <a href="caja_ingreso_retiro.php" class="nav-link nav-toggle">
        <i class="glyphicon glyphicon-usd"></i>
        <span class="title">Ingresos-retiro de caja</span>
        </a>
    </li>
  <?php endif;?>

  <!--li  class="nav-item start">
    <a href="prueba_impresora.php" class="nav-link nav-toggle">
      <i class="glyphicon glyphicon-usd"></i>
       <span class="title">Prueba impresora</span>
      </a>
  </li-->

</ul>
<script type="text/javascript" src="assets/jquery-1.10.2.min.js"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
<!-- <script >
  $(document).ready(function(){
      $('li').click(function(){
          $('li').css('color','black');
          $(this).css('color', 'green');
      });
  });
  // var selec=document.getElementsByTagName("IL");
  // selec.style.background= 'yellow';
</script> -->
