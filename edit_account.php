<?php
  $page_title = 'Editar Cuenta';
  require_once('includes/load.php');
   page_require_level(3);
?>
<?php
  $id = (int)$_SESSION['user_id'];
  redirect('edit_user.php?id='.$id, false);
?>

