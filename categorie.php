<?php
  $page_title = 'Lista de categorías';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  $all_photo = find_all('media');
  $categorias = join_categories_table();
  $all_categories = find_all('categories')
?>
<?php
 if(isset($_POST['add_cat'])){
   $req_field = array('categorie-name');
   validate_fields($req_field);
   $cat_name = remove_junk($db->escape($_POST['categorie-name']));
   if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
      $media_id = '0';
    } else {
      $media_id = remove_junk($db->escape($_POST['product-photo']));
    }
   if(empty($errors)){
      $query  = "INSERT INTO categories (";
      $query .=" name,media_id";
      $query .= ") VALUES ('{$cat_name}','{$media_id}')";
      if($db->query($query)){
        $session->msg("s", "Categoría agregada exitosamente.");
        redirect('categorie.php',false);
      } else {
        $session->msg("d", "Lo siento, registro falló");
        redirect('categorie.php',false);
      }
   } else {
     $session->msg("d", $errors);
     redirect('categorie.php',false);
   }
 }
?>
<?php include_once('layouts/header.php'); ?>

  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
  </div>
  <div class="row">
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Agregar categoría</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="categorie.php">
            <div class="form-group">
                <input type="text" class="form-control" name="categorie-name" placeholder="Nombre de la categoría" required>
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
            <button type="submit" name="add_cat" class="btn btn-primary">Agregar categoría</button>
        </form>
        </div>
      </div>
    </div>
    <!-- Visualizador de categorias -->
    <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Lista de categorías</span>
       </strong>
      </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped table-hover">
            <thead>                                                             <!--Cabecera dentro de la tabla-->
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Categorías</th>
                    <th class="text-center">Imágen</th>
                    <th class="text-center" style="width: 100px;">Acciones</th>
                </tr>
            </thead>
            <tbody>                                                              <!--Cuerpo dentro de la tabla-->
              <?php foreach ($categorias as $cat):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td><?php echo remove_junk(ucfirst($cat['name'])); ?></td>
                    <td>
                      <?php if($cat['media_id'] === '0'): ?>
                          <img class="img-avatar img-circle" src="uploads/products/no_image.jpg" alt="User Image"  width="100" height="100">
                        <?php else: ?>
                        <img class="img-avatar img-circle" src="uploads/products/<?php echo $cat['image']; ?>" alt="User Image"  width="100" height="100">
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_categorie.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Editar">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a href="delete_categorie.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Eliminar">
                          <span class="glyphicon glyphicon-trash"></span>
                        </a>
                      </div>
                    </td>

                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
       </div>
    </div>
    </div>
   </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
