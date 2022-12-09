<?php include('config.php') ?>
<?php include('Controllers/ArticleController.php') ?>
<?php
$articles = getAllArticles();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Area - Articles List </title>
  <!-- Bootstrap CSS -->
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" /> -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<?php include('public/layouts/navbar.php') ?>

  <!-- TO DO:  -->
  <!-- 1.1: Manage users, Manage posts, manage user roles/Assign permissions, -->
  <div class="col-md-12 col-md-offset-2">
    
    <!-- <a href="#" class="btn btn-success">
      <span class="glyphicon glyphicon-plus"></span>
      Create new article
    </a> -->
    <!-- <a href="#" class="btn btn-danger">
      <span class="glyphicon glyphicon-trash"></span>
      Trash
    </a> -->
    <hr>

    <h1 class="text-center">Articles</h1>
    <br />

    <?php if (isset($articles)): ?>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th> title</th>
            <th>Description</th>
            <th>Picture</th>
            <th colspan="3" class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($articles as $key => $value): ?>
            <tr>
              <td><?php echo $key + 1; ?></td>
              <td><?php echo $value['title'] ?></td>
              <td><?php echo $value['short_description'] ?></td>
              <td><?php echo $value['picture'] ?></td>
              <td class="text-center">
                <a href="<?php echo BASE_URL ?>admin/posts/#?delete_article=<?php echo $value['id'] ?>" class="btn btn-sm btn-info">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <h2 class="text-center">No articles in database</h2>
    <?php endif; ?>

  </div>
</body>
</html>
