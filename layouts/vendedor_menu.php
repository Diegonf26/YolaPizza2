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
<?php endif;?>


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

  <?php if($user['bloqueocaja']==true):?>
    <li  class="nav-item start">
      <a href="caja_ingreso_retiro.php" class="nav-link nav-toggle">
        <i class="glyphicon glyphicon-usd"></i>
        <span class="title">Ingresos-retiro de caja</span>
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

   

</ul>
<script type="text/javascript" src="assets/jquery-1.10.2.min.js"></script>
