<?php
  $page_title = 'Editar proveedor';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
$url=$_GET['url'];
$tabla=$_GET['tabla'];

$proveedor = find_by_id($tabla,(int)$_GET['id']);

if(!$proveedor){
  $session->msg("d","Missing proveedor id.");
  redirect('catalogo_pizzas.php');
}
?>
<?php
  if(isset($_POST['proveedor'])){

    if(1)
    {
      if($_POST['name']=='') $p_name=$proveedor['name'];
      else $p_name  = remove_junk($db->escape($_POST['name']));
      
      if($_POST['address']=='') $p_address=$proveedor['address'];
      else $p_address   = remove_junk($db->escape($_POST['address']));
      
      if($_POST['phone']=='') $p_phone=$proveedor['phone'];
      else $p_phone   = remove_junk($db->escape($_POST['phone']));
      
      if($_POST['cellphone']=='') $p_cellphone=$proveedor['cellphone'];
      else $p_cellphone   = remove_junk($db->escape($_POST['cellphone']));
      

      $query  = "UPDATE $tabla SET";        //Insertar la BD en donde se va a ingresar los datos
      $query  .=" name ='{$p_name}',";
      $query  .=" address ='{$p_address}',";
      $query  .=" phone ='{$p_phone}',";
      $query  .=" cellphone ='{$p_cellphone}'";
      $query  .=" WHERE id ='{$proveedor['id']}';";
      $result = $db->query($query);
      if($result && $db->affected_rows() === 1){
        $session->msg('s',"Proveedor agregado exitosamente. ");
        redirect($url, false);
      } else {
        $session->msg('d',' Lo siento, registro falló.');
        redirect('proveedores.php', false);         //Regresar a administrar productos a vender
      }

    } else{
        $session->msg("d", $errors);
        redirect('proveedores_edit.php?id='.$proveedor['id'].'&url='.$url.'&tabla='.$tabla, false);
    }

  }
  else if(isset($_POST['cancel'])){
    redirect($url);
  }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Editar proveedor</span>
        </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-7">
          <form method="post" action="proveedores_edit.php?id=<?php echo (int)$proveedor['id']; ?>&url=<?php echo $url;?>&tabla=<?php echo $tabla;?>">
          <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                   <span>Nombre..</span>
                  </span>
                  <input type="text" class="form-control" name="name"  autocomplete="off" value="<?php echo remove_junk($proveedor['name']);?>">
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                   <span>Dirección</span>
                  </span>
                  <input type="text" class="form-control" name="address" autocomplete="off" value="<?php echo remove_junk($proveedor['address']);?>">
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                   <span>Teléfono.</span>
                  </span>
                  <input type="tel" class="form-control" name="phone" autocomplete="off" value="<?php echo remove_junk($proveedor['phone']);?>">
               </div>
              </div>

              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                   <span>Celular....</span>
                  </span>
                  <input type="tel" class="form-control" name="cellphone" autocomplete="off" value="<?php echo remove_junk($proveedor['cellphone']);?>">
               </div>
              </div>
              <button type="submit" name="proveedor" class="btn btn-success">Actualizar</button>
              <button type="submit" name="cancel" class="btn btn-danger">Cancelar</button>
              
          </form>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
