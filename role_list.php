<?php require_once "config.php"; ?>
<?php include('Controllers/RoleController.php') ?>
<?php
  $roles = getRoles();
  ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Area - User Roles </title>
  <!-- Bootstrap CSS -->
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" /> -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<?php include('public/layouts/navbar.php') ?>

  <!-- TO DO:  -->
  <!-- 1.1: Manage users, Manage roles, manage user roles/Assign permissions, -->
  <div class="col-md-8 col-md-offset-2">
    <a href="roleForm.php" class="btn btn-success">
      <span class="glyphicon glyphicon-plus"></span>
      Create new role
    </a>
    <hr>

    <h1 class="text-center">Roles</h1>
    <br />

    <?php if (isset($roles)): ?>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>N</th>
            <th>Role name</th>
            <th colspan="3" class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($roles as $key => $value): ?>
            <tr>
              <td><?php echo $key + 1; ?></td>
              <td><?php echo $value['name'] ?></td>
              <td class="text-center">
                <a href="<?php echo BASE_URL ?>admin/roles/assignPermissions.php?assign_permissions=<?php echo $value['id'] ?>" class="btn btn-sm btn-info">
                  permissions
                </a>
              </td>
              <td class="text-center">
                <a href="<?php echo BASE_URL ?>admin/roles/roleForm.php?edit_role=<?php echo $value['id'] ?>" class="btn btn-sm btn-success">
                  <span class="glyphicon glyphicon-pencil"></span>
                </a>
              </td>
              <td class="text-center">
                <a href="<?php echo BASE_URL ?>admin/roles/roleForm.php?delete_role=<?php echo $value['id'] ?>" class="btn btn-sm btn-danger">
                  <span class="glyphicon glyphicon-trash"></span>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <h2 class="text-center">No roles in database</h2>
    <?php endif; ?>

  </div>
</body>
</html>

<script>
  $(document).ready(function () {
      $('#example').DataTable();
  });
</script>
