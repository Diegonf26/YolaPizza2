<?php
  $page_title = 'Agregar producto';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_categories = find_all('categories');
  $all_photo = find_all('media');
  $all_proveedores = find_all('proveedores');
?>
<?php
 $uni=array("Unidad", "Kg", "Litro", "Latas", "gr");
 if(isset($_POST['add_product'])){
   $req_fields = array('product-title','product-categorie','product-quantity','desc-unidades','buying-price','nombre-proveedor' );
   validate_fields($req_fields);
   if(empty($errors)){
     $p_name  = remove_junk($db->escape($_POST['product-title']));
     $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
     $p_prov   = remove_junk($db->escape($_POST['nombre-proveedor']));
     $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
     $p_uni   = remove_junk($db->escape($_POST['desc-unidades']));
     $p_buy   = remove_junk($db->escape($_POST['buying-price']));

     if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
       $media_id = '0';
     } else {
       $media_id = remove_junk($db->escape($_POST['product-photo']));
     }
     $date    = make_date();
     $query  = "INSERT INTO products (";
     $query .=" name,quantity,unidades,buy_price,categorie_id,media_id,date,proveedor_id";
     $query .=") VALUES (";
     $query .=" '{$p_name}', '{$p_qty}','{$p_uni}', '{$p_buy}', '{$p_cat}', '{$media_id}', '{$date}', '{$p_prov}'";
     $query .=")";
     $query .=" ON DUPLICATE KEY UPDATE name='{$p_name}'";
     if($db->query($query)){
      $user = current_user();
      $aux = remove_junk(ucwords($user['username']));
      $gasto    = $p_qty*$p_buy;
      $query2  = "INSERT INTO products_add_records (";
      $query2 .=" `name`, `last_quantity`, `new_quantity`, `unidades`, `buy_price`, `gasto`,`date`, `username`, `proveedor_id`";
      $query2 .=") VALUES (";
      $query2 .=" '{$p_name}','0','{$p_qty}', '{$p_uni}', '{$p_buy}','{$gasto}', '{$p_date}', '{$aux}', '{$p_prov}'";
      $query2 .=")";
      if($db->query($query2)){
        $session->msg('s',"Producto agregado exitosamente. ");
       redirect('product.php', false);
      }
       
     } else {
       $session->msg('d',' Lo siento, registro falló.');
       redirect('product.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('add_product.php',false);
   }

 }
 else if(isset($_POST['regresar'])) redirect('product.php',false);

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
            <span>Agregar Materia prima</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_product.php" class="clearfix">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" autocomplete="off" placeholder="Descripción">
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
              <!-- Selcion de cantidad -->
              <div class="form-group">
               <div class="row">
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                    </span>
                    <input type="number" class="form-control" name="product-quantity" autocomplete="off" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" placeholder="Cantidad">
                  </div>
                 </div>
                 <div class="col-md-8">
                   <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-asterisk"></i>
                      </span>
                      <select class="form-control" name="desc-unidades">
                        <option value="">Seleccione una unidad de medida</option>
                        <?php  foreach ($uni as $u): ?>
                          <option value=<?php echo $u?>><?php echo $u?></option>
                        <?php endforeach; ?>
                      </select>
                      <!--input type="text" class="form-control" name="desc-unidades" placeholder="Unidades"-->
                    </div>
                  </div>
                </div>
              </div>
              <!-- Seleccion de precios -->
              <div class="form-group">
                <div class="row">
                  <div class="col-md-4">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-usd"></i>
                      </span>
                      <input type="number" step="0.01"  min="0" pattern="^\d+(?:\.\d{1,2})?$" autocomplete="off" class="form-control" name="buying-price" placeholder="Precio de compra">
                    </div>
                  </div>
                </div>
              </div>



              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-briefcase"></i>
                  </span>
                  
                  <select class="form-control" name="nombre-proveedor">
                    <option value="">Seleccione el proveedor</option>
                    <?php  foreach ($all_proveedores as $proveedor): ?>
                      <option value="<?php echo (int)$proveedor['id'] ?>">
                        <?php echo $proveedor['name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                               
                </div>
              </div>

              <button type="submit" name="add_product" class="btn btn-success">Agregar producto</button>
              <button type="submit" name="regresar" class="btn btn-danger">Cancelar</button>

          </form>
         </div>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
