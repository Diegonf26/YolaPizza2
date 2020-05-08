<?php
  $page_title = 'Proveedores';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $proveedores = buscar_catalogo('proveedores');
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
  <div class="col-md-12">
    <div class="panel-heading clearfix">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Lista de Proveedores</span>
      </strong>
      <div class="pull-right">
        <a href="proveedor_add.php" class="btn btn-primary">Agregar nuevo proveedor</a>
      </div>
    </div>
    <div class="panel-body">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="text-center" style="width: 1%;">#</th>
            <th class="text-center" style="width: 3%;"> Nombre</th>
            <th class="text-center" style="width: 3%;"> Dirección</th>
            <th class="text-center" style="width: 3%;"> Teléfono </th> 
            <th class="text-center" style="width: 3%;"> Celular </th>
            <th class="text-center" style="width: 3%;"> Editar </th>
          </tr>
        </thead>
        <tbody>
          <?php $i=1;?>
          <?php foreach ($proveedores as $proveedor):?>
          <tr>
            <td class="text-center"> <?php echo $i;?></td>
            <td class="text-center"> <?php echo remove_junk($proveedor['name']); ?></td>
            <td class="text-center"> <?php echo remove_junk($proveedor['address']); ?></td>
            <td class="text-center"> <?php echo remove_junk($proveedor['phone']); ?></td>
            <td class="text-center"> <?php echo remove_junk($proveedor['cellphone']); ?></td>
            <td class="text-center">
              <div class="btn-group">
                <a href="proveedores_edit.php?id=<?php echo (int)$proveedor['id'];?>&url=proveedores.php&tabla=proveedores" class="btn btn-info btn-xs"  title="Editar" data-toggle="tooltip">
                  <span class="glyphicon glyphicon-edit"></span>
                </a>
                <a onclick="return confirmDelete();" href="proveedores_delete.php?id=<?php echo (int)$proveedor['id'];?>&url=proveedores.php&tabla=proveedores" class="btn btn-danger btn-xs"  title="Eliminar" data-toggle="tooltip">
                  <span class="glyphicon glyphicon-trash"></span>
                </a>
              </div>
            </td>
          </tr>
          <?php $i++;?>
          <?php endforeach; ?>
        </tbody>
      </table>
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
