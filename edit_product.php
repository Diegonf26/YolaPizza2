<?php
  $page_title = 'Editar producto';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
$product = find_by_id('products',(int)$_GET['id']);
$all_categories = find_all('categories');
$all_photo = find_all('media');
$all_proveedores = find_all('proveedores');
if(!$product){
  $session->msg("d","Missing product id.");
  redirect('product.php');
}
?>
<?php
  $uni=array("Unidad", "Kg", "Litro", "Latas", "gr");
  if(isset($_POST['product'])){
      $req_fields = array('product-title','product-categorie','product-quantity','buying-price' );
      validate_fields($req_fields);
      
    if(empty($errors)){
        $p_name  = remove_junk($db->escape($_POST['product-title']));
        $p_cat   = (int)$_POST['product-categorie'];
        $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
        $p_buy   = remove_junk($db->escape($_POST['buying-price']));
        $p_unidades  = remove_junk($db->escape($_POST['product-unidades']));
        $p_pro  = (int)$_POST['nombre-proveedor'];
        if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
          $media_id = '0';
        } else {
          $media_id = remove_junk($db->escape($_POST['product-photo']));
        }
        $query   = "UPDATE products SET";
        $query  .=" name ='{$p_name}', quantity ='{$p_qty}',";
        $query  .=" unidades='{$p_unidades}', buy_price ='{$p_buy}', categorie_id ='{$p_cat}',media_id='{$media_id}',proveedor_id='{$p_pro}'";
        $query  .=" WHERE id ='{$product['id']}'";
        $result = $db->query($query);
                if($result && $db->affected_rows() === 1){
                  $session->msg('s',"Producto ha sido actualizado. ");
                  redirect('product.php', false);
                } else {
                  $session->msg('d',' Lo siento, actualización falló.');
                  redirect('edit_product.php?id='.$product['id'], false);
                }

    } else{
        $session->msg("d", $errors);
        redirect('edit_product.php?id='.$product['id'], false);
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
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Editar producto</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-7">
           <form method="post" action="edit_product.php?id=<?php echo (int)$product['id'] ?>">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" value="<?php echo remove_junk($product['name']);?>">
               </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="product-categorie">
                    <option value="">Selecciona una categoría</option>
                   <?php  foreach ($all_categories as $cat): ?>
                     <option value="<?php echo (int)$cat['id']; ?>" <?php if($product['categorie_id'] === $cat['id']): echo "selected"; endif; ?> >
                       <?php echo remove_junk($cat['name']); ?></option>
                   <?php endforeach; ?>
                 </select>
                  </div>
                  <div class="col-md-6">
                    <select class="form-control" name="product-photo">
                      <option value=""> Sin imagen</option>
                      <?php  foreach ($all_photo as $photo): ?>
                        <option value="<?php echo (int)$photo['id'];?>" <?php if($product['media_id'] === $photo['id']): echo "selected"; endif; ?> >
                          <?php echo $photo['file_name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group">
               <div class="row">
                 <div class="col-md-4">
                  <div class="form-group">
                    <label for="qty">Cantidad</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                       <i class="glyphicon glyphicon-shopping-cart"></i>
                      </span>
                      <input type="number" min="0" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" class="form-control" name="product-quantity"  autocomplete="off" value="<?php echo remove_junk($product['quantity']); ?>">
                   </div>
                  </div>
                 </div>

                <div class="col-md-8">
                  <div class="form-group">
                    <label for="qty">Unidades</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                       <i class="glyphicon glyphicon-asterisk"></i>
                      </span>
                      <select class="form-control" name="product-unidades">
                        <option value="<?php echo remove_junk($product['unidades']); ?>"><?php echo remove_junk($product['unidades']);?></option>
                        <?php  foreach ($uni as $u): ?>
                          <?php if($u!=remove_junk($product['unidades'])):?>
                            <option value=<?php echo $u?>><?php echo $u?></option>
                          <?php endif; ?>
                        <?php endforeach; ?>
                      </select>
                      <!--input type="text" class="form-control" name="product-unidades" value="<?php //echo remove_junk($product['unidades']); ?>"-->
                   </div>
                  </div>
                </div>

                 <div class="col-md-4">
                  <div class="form-group">
                    <label for="qty">Precio de compra</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-usd"></i>
                      </span>
                      <input type="number" class="form-control" name="buying-price" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" value="<?php echo remove_junk($product['buy_price']);?>">
                   </div>
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
                      <option value="<?php echo (int)$proveedor['id'];?>" <?php if($product['proveedor_id'] === $proveedor['id']): echo "selected"; endif; ?> >
                        <?php echo $proveedor['name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                               
                </div>
              </div>

              <button type="submit" name="product" class="btn btn-success">Actualizar</button>
              <button type="submit" name="regresar" class="btn btn-danger">Cancelar</button>

          </form>
         </div>
        </div>
      </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
