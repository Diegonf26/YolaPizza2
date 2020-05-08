<?php
/*
user admin clave admin

|--------------------------------------------------------------------------
| OWSA-INV V2
|--------------------------------------------------------------------------
| Author: Siamon Hasan
| Project Name: OSWA-INV
| Version: v2
| Offcial page: http://oswapp.com/
| facebook Page: https://www.facebook.com/oswapp
|
https://obedalvarado.pw/blog/sistema-inventario-open-source-php-mysql/
plantillas admin https://adminlte.io/

vende a $400 https://www.youtube.com/watch?v=QfI0kCpV0ZY
vende a $100 https://www.youtube.com/watch?v=LuL6ULvyGJI  se ve mejor

proyectos en ingles
https://www.youtube.com/watch?v=jk8L4_Wx40U   https://codersfolder.com/
https://www.webscript.info/user-posts/post?pid=118&post=inventory-management-system-using-php-mysqli-jquery-ajax-bootstrap

sidebar https://bootsnipp.com/snippets/featured/responsive-sidebar-menu


tutorial diseño de sistema completo
https://www.youtube.com/watch?v=zwm-1OAhLbQ&list=PLB_Wd4-5SGAYCmzk21-bvdVTTF6AkH3-T&index=1



comandos mysql https://gist.github.com/hofmannsven/9164408
create database oswa_inv;   crea base de datos
mysql -u root -p oswa_inv < oswa_inv.sql
*/
  ob_start();
  require_once('includes/load.php');                                    //importa archivos de include
  
  $d = make_date2();
  $cc = find_conta('contador');
  $products = join_product_table();

  if($d!=$cc[0]['date']){   //solo actualiza si se ha cambiado el valor
    $query = "UPDATE contador SET ";        //Insertar la BD en la memoria de usuario
    $query .=" conta = 0, date = '{$d}' WHERE id = 1;";
    if($db->query($query)){
      foreach ($products as $product) {     
        $qty   = remove_junk($product['quantity']);
        $qty_apro   = remove_junk($product['qtyAproximada']);
        $tempo=(float)$qty-(float)$qty_apro;
        $query = "UPDATE products SET ";        //Insertar la BD en la memoria de usuario
        $query .=" quantity = '{$tempo}', date = '{$d}', qtyAproximada = '0' WHERE id='{$product['id']}';";
        $db->query($query);
      }
    }
  }


  if($session->isUserLoggedIn(true))  redirect('home.php', false);
?>
<?php //include_once('layouts/header.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="180x180" href="assets/img/apple-touch-icon.png">
  <link rel="shortcut  icon" type="image/png" sizes="32x32" href="assets/img/favicon-32x32.png">
  <link rel="shortcut  icon" type="image/png" sizes="16x16" href="assets/img/favicon-16x16.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Inicio de sesión
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link href="assets/css/monserrat.css" rel="stylesheet" />
  <link href="assets/css/fontawesome.css" rel="stylesheet">
  <!-- CSS Files -->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/css/now-ui-kit.css?v=1.2.0" rel="stylesheet" />  
</head>

<body class="login-page sidebar-collapse">
  <div class="page-header clear-filter" filter-color="orange">
    <div class="page-header-image" style="background-image:url(assets/img/login1.jpg)"></div>
    <div class="content">
      <h1 class="nav-link"  style="color: #ffffff; font-weight: bold;" >Pizzería Amangiare</h1>
      <div class="container">
        <div class="col-md-5 ml-auto mr-auto">
          <div class="card card-login card-plain">
            <form method="post" action="auth.php" class="clearfix">
              <!--div class="card-header text-center">
                <div class="logo-container">
                  <img src="assets/img/now-logo.png" alt="">
                </div>
              </div-->
              <div class="card-body">
                <div class="input-group no-border input-lg">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="now-ui-icons users_circle-08"></i>
                    </span>
                  </div>
                  <input type="name" class="form-control" name="username" placeholder="Nombre se usuario" required autocomplete="off">
                </div>
                <div class="input-group no-border input-lg">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="now-ui-icons clothes_tie-bow"></i>
                    </span>
                  </div>
                  <input type="password" name= "password" placeholder="Contraseña" class="form-control" required/>
                </div>
              </div>
              <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary btn-round btn-lg btn-block">Iniciar sesión</button>  
                <div class="pull-right">
                  <h6>
                    <!--a href="#pablo" class="link">Necesita Ayuda?</a-->
                  </h6>
                </div>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6 ml-auto mr-auto text-center">
        <?php echo display_msg($msg);?>
      </div>
    </div>
    <footer class="footer">
      <div class="container">
        <div class="copyright" id="copyright">
          &copy;
          <script>
            document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))
          </script>, Diseñado por 
          <!--a href="#" target="_blank">XXXXX</a-->
          <a href="#diegonf26@gmail.com">Diego Navas</a> y <a href="#jonathan.vargas1104@gmail.com">Jonathan Vargas</a>
        </div>
      </div>
    </footer>
  </div>

<?php //include_once('layouts/footer.php'); ?>
  <!--   Core JS Files   -->
  <script src="assets/js/core/jquery.min.js" type="text/javascript"></script>
  <script src="assets/js/core/popper.min.js" type="text/javascript"></script>
  <script src="assets/js/core/bootstrap.min.js" type="text/javascript"></script>
  <!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
  <script src="assets/js/plugins/bootstrap-switch.js"></script>
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
  <!--  Plugin for the DatePicker, full documentation here: https://github.com/uxsolutions/bootstrap-datepicker -->
  <script src="assets/js/plugins/bootstrap-datepicker.js" type="text/javascript"></script>
  <!-- Control Center for Now Ui Kit: parallax effects, scripts for the example pages etc -->
  <script src="assets/js/now-ui-kit.js?v=1.2.0" type="text/javascript"></script>
</body>

</html>
