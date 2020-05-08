<?php
  $page_title = 'Agregar proveedor';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
 if(isset($_POST['proveedor_add'])){
    $req_fields = array('name');
    validate_fields($req_fields);
    if(empty($errors)){
      $p_name  = remove_junk($db->escape($_POST['name']));
      $p_address   = remove_junk($db->escape($_POST['address']));
      $p_phone   = remove_junk($db->escape($_POST['phone']));
      $p_cellphone   = remove_junk($db->escape($_POST['cellphone']));

      $query  = "INSERT INTO proveedores (";        //Insertar la BD en donde se va a ingresar los datos
      $query .=" name, address, phone, cellphone";
      $query .=") VALUES (";
      $query .=" '{$p_name}', '{$p_address}', '{$p_phone}', '{$p_cellphone}'";
      $query .=")";
      $query .=" ON DUPLICATE KEY UPDATE name='{$p_name}'";
      if($db->query($query)){
        $session->msg('s',"Proveedor agregado exitosamente. ");
        redirect('proveedores.php', false);
      } else {
        $session->msg('d',' Lo siento, registro falló.');
        redirect('proveedores.php', false);         //Regresar a administrar productos a vender
      }

    } else{
     $session->msg("d", $errors);
     redirect('proveedor_add.php',false);
    }

 }
 else if(isset($_POST['regresar'])) redirect('proveedores.php',false);

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
  <div class="col-md-9">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Datos del nuevo proveedor</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="proveedor_add.php" class="clearfix">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                   <span>Nombre..</span>
                  </span>
                  <input type="text" class="form-control" name="name"  autocomplete="off">
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                   <span>Dirección</span>
                  </span>
                  <input type="text" class="form-control" name="address" autocomplete="off">
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                   <span>Teléfono.</span>
                  </span>
                  <input type="tel" class="form-control" name="phone" autocomplete="off">
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                   <span>Celular....</span>
                  </span>
                  <input type="tel" class="form-control" name="cellphone" autocomplete="off">
               </div>
              </div>
              <button type="submit" name="proveedor_add" class="btn btn-success">Agregar</button>
              <button type="submit" name="regresar" class="btn btn-danger">Cancelar</button>

          </form>
         </div>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
