<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  $url=$_GET['url'];
  $tabla=$_GET['tabla'];
  $proveedor = find_by_id($tabla,(int)$_GET['id']);
  if(!$proveedor){
    $session->msg("d","ID vacío");
    redirect('$url.php');
  }
?>
<?php
  $delete_id = delete_by_id($tabla,(int)$proveedor['id']);
  if($delete_id){
      $session->msg("s","Proveedor eliminado");
      redirect($url);
  } else {
      $session->msg("d","Eliminación falló");
      redirect($url);
  }
?>
