<?php
  $page_title = 'Agregar usuarios';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  $groups = find_all('user_groups');
?>
<?php
  if(isset($_POST['add_user'])){

   $req_fields = array('full-name','username','password','level','clave_caja' );
   validate_fields($req_fields);

   if(empty($errors)){
       $name   = remove_junk($db->escape($_POST['full-name']));
       $username   = remove_junk($db->escape($_POST['username']));
       $password   = remove_junk($db->escape($_POST['password']));
       $clave_caja   = remove_junk($db->escape($_POST['clave_caja']));
       $user_level = (int)$db->escape($_POST['level']);
       $clave_caja = sha1($clave_caja);
       $password = sha1($password);
        $query = "INSERT INTO users (";
        $query .="name,username,password,user_level,status,clave_caja";
        $query .=") VALUES (";
        $query .=" '{$name}', '{$username}', '{$password}', '{$user_level}','1','{$clave_caja}'";
        $query .=")";
        if($db->query($query)){
          //sucess
          $session->msg('s'," Cuenta de usuario ha sido creada");
          redirect('users.php',false);
        } else {
          //failed
          $session->msg('d',' No se pudo crear la cuenta.');
          redirect('add_user.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('add_user.php',false);
   }
 }
 else if(isset($_POST['cancel'])) redirect('users.php',false);
?>
<?php include_once('layouts/header.php'); ?>
  <?php echo display_msg($msg); ?>
  <div class="row">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Agregar usuario</span>
       </strong>
      </div>
      <div class="panel-body">
        <div class="col-md-6">
          <form method="post" action="add_user.php">
            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" class="form-control" name="full-name" placeholder="Nombre completo" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" class="form-control" name="username" placeholder="Nombre de usuario" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" name ="password" placeholder="Contraseña">
            </div>
            <div class="form-group">
                <label for="clave_caja">Clave de la caja</label>
                <input type="password" class="form-control" name ="clave_caja" placeholder="Clave">
            </div>
            <div class="form-group">
              <label for="level">Rol de usuario</label>
                <select class="form-control" name="level">
                  <?php foreach ($groups as $group ):?>
                   <option value="<?php echo $group['group_level'];?>"><?php echo ucwords($group['group_name']);?></option>
                <?php endforeach;?>
                </select>
            </div>
            <div class="form-group clearfix">
              <button type="submit" name="add_user" class="btn btn-success">Guardar</button>
              <button type="submit" name="cancel" class="btn btn-danger">Cancelar</button>

            </div>
        </form>
        </div>

      </div>

    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
