<?php
  $page_title = 'Lista de imagenes';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php $media_files = find_all('media');?>
<?php
if(isset($_POST['submit'])) {
$photo = new Media();
$photo->upload($_FILES['file_upload']);
if($photo->process_media()){
      $session->msg('s','Imagen subida al servidor.');
      redirect('media.php');
} else{
    $session->msg('d',join($photo->errors));
    redirect('media.php');
  }
}

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6"><?php echo display_msg($msg); ?></div>
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <span class="glyphicon glyphicon-camera"></span>
        <span>Lista de imagenes</span>
        <div class="pull-right">
          <form class="form-inline" action="media.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-btn">
                  <input type="file" name="file_upload" multiple="multiple" class="btn btn-primary btn-file"/>
                </span>
                <button type="submit" name="submit" class="btn btn-default">Subir</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th class="text-center" style="width: 2%;">#</th>
              <th class="text-center" style="width: 5%;">Imagen</th>
              <th class="text-center" style="width: 3%;">Descripción</th>
              <th class="text-center" style="width: 3%;">Tipo</th>
              <th class="text-center" style="width: 3%;">Eliminar</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($media_files as $media_file): ?>
            <tr class="list-inline">
              <td class="text-center"><?php echo count_id();?></td>
              <td class="text-center">
                  <img src="uploads/products/<?php echo $media_file['file_name'];?>" class="img-avatar img-circle" alt="User Image"  width="100" height="100" />
              </td>
              <td class="text-center">
                <?php echo $media_file['file_name'];?>
              </td>
              <td class="text-center">
                <?php echo $media_file['file_type'];?>
              </td>
              <td class="text-center">
                <a onclick="return confirmDelete();" href="delete_media.php?id=<?php echo (int) $media_file['id'];?>" class="btn btn-danger btn-xs"  title="Eliminar">
                  <span class="glyphicon glyphicon-trash"></span>
                </a>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  function confirmDelete() {
      var confirmar = confirm("¿Realmente desea eliminar el producto? ");
      if (confirmar) {
          return true;
      } else {
          return false;
      }
  }
</script>
<?php include_once('layouts/footer.php'); ?>
