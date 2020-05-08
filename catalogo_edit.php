<?php
  $page_title = 'Editar producto a vender';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
$url=$_GET['url'];
$tabla=$_GET['tabla'];

$product = find_by_id($tabla,(int)$_GET['id']);

if(!$product){
  $session->msg("d","Missing product id.");
  redirect('catalogo_pizzas.php');
}
?>
<?php
  if(isset($_POST['product'])){
    $req_fields = array('product-title','saleing-price');
    validate_fields($req_fields);
    if(empty($errors))
    {
      $p_sale  = remove_junk($db->escape($_POST['saleing-price']));

      $query   = "UPDATE $tabla SET";
      $query  .=" price ='{$p_sale}'";
      $query  .=" WHERE id ='{$product['id']}'";
      $result = $db->query($query);
      if($result && $db->affected_rows() === 1){
        $session->msg('s',"Producto ha sido actualizado. ");
        redirect($url, false);
      } else {
        $session->msg('d',' Lo siento, actualización falló.');
        redirect('catalogo_edit.php?id='.$product['id'].'&url='.$url.'&tabla='.$tabla, false);
      }

    } else{
        $session->msg("d", $errors);
        redirect('catalogo_edit.php?id='.$product['id'].'&url='.$url.'&tabla='.$tabla, false);
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
          <span>Editar producto</span>
        </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-7">
          <form method="post" action="catalogo_edit.php?id=<?php echo (int)$product['id']; ?>&url=<?php echo $url;?>&tabla=<?php echo $tabla;?>">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-th-large"></i>
                </span>
                <input readonly type="text" class="form-control" name="product-title" value="<?php echo remove_junk($product['size']).",".remove_junk($product['flavor']);?>">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="qty">Nuevo precio de venta</label>
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-usd"></i>
                  </span>
                  <input  type="number" class="form-control" name="saleing-price" value="<?php echo remove_junk($product['price']);?>" step="0.01" pattern="^\d+(?:\.\d{1,2})?$" min="0"  >
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label style="visibility: hidden;">Texto largo sdsadsa</label>
                <button type="submit" name="product" class="btn btn-success">Actualizar</button>
                <button type="submit" name="cancel" class="btn btn-danger">Cancelar</button>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
