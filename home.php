<?php
  $page_title = 'AMANGIARE';
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}  //Verificar si esta un logeado para entrar a home
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
 <div class="col-md-12">
    <div class="panel">
      <div class="jumbotron text-center">
         <h1>Bienvenido a pizzería Amangiare</h1>
         <h2>Acceso exitoso</h2>
     
      </div>
    </div>
 </div>
</div>
<?php include_once('layouts/footer.php'); ?>
