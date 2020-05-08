<?php
  $page_title = 'Agregar producto a vender';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_categories = find_all('categories');
  $all_photo = find_all('media');
?>
<?php
 if(isset($_POST['add_productovender'])){
   $req_fields = array('product-title','product-categorie', 'saleing-price');
   validate_fields($req_fields);
   if(empty($errors)){
     $p_name  = remove_junk($db->escape($_POST['product-title']));
     $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
    //  $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
    //  $p_buy   = remove_junk($db->escape($_POST['buying-price']));
     $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
     if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
       $media_id = '0';
     } else {
       $media_id = remove_junk($db->escape($_POST['product-photo']));
     }
     $date    = make_date();
     $query  = "INSERT INTO productovender (";        //Insertar la BD en donde se va a ingresar los datos
     $query .=" name,sale_price,categorie_id,media_id";
     $query .=") VALUES (";
     $query .=" '{$p_name}', '{$p_sale}', '{$p_cat}', '{$media_id}'";
     $query .=")";
     $query .=" ON DUPLICATE KEY UPDATE name='{$p_name}'";
     if($db->query($query)){
       $session->msg('s',"Producto agregado exitosamente. ");
       redirect('add_productovender.php', false);
     } else {
       $session->msg('d',' Lo siento, registro falló.');
       redirect('productovender.php', false);         //Regresar a administrar productos a vender
     }

   } else{
     $session->msg("d", $errors);
     redirect('add_productovender.php',false);
   }

 }
 else if(isset($_POST['regresar'])) redirect('productovender.php',false);

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
            <span>Agregar producto a vender</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_productovender.php" class="clearfix">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" placeholder="Descripción">
               </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="product-categorie">
                      <option value="">Selecciona una categoría</option>
                    <?php  foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['id'] ?>">
                        <?php echo $cat['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <select class="form-control" name="product-photo">
                      <option value="">Selecciona una imagen</option>
                    <?php  foreach ($all_photo as $photo): ?>
                      <option value="<?php echo (int)$photo['id'] ?>">
                        <?php echo $photo['file_name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group">
               <div class="row">
               </div>
              </div>
              <button type="submit" name="add_productovender" class="btn btn-danger">Agregar producto</button>
              <button type="submit" name="regresar" class="btn btn-danger">Cancelar</button>

          </form>
         </div>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
