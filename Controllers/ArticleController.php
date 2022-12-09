<?php
  $article_id = 0;
  $title = "";
  $shortDescription = "";
  $createdAt = "";
  $updatedAt = "";
  $isEditting = false;
  $published = false;
  $articles = array();
  $errors = array();

  // ACTION: update an article
  if (isset($_POST['update_article'])) {
      $article_id = $_POST['article_id'];
      updateArticle($article_id);
  }
  // ACTION: Save Post
  if (isset($_POST['save_article'])) {
      store();
  }

  // ACTION: Delete post
  if (isset($_GET['delete_article'])) {
    $article_id = $_GET['delete_article'];
    softDeleteArticle($article_id);
  }

  function updateArticle($article_id){
    // pull in global form variables into function
    global $dataBaseConnection, $errors, $title, $published, $isEditting;
    // validate form
    $errors = dataValidator($_POST, ['update_article']);

    if (count($errors) === 0) {
      // receive form values
      $title = esc($_POST['title']);

      if (isset($_POST['published'])) {
        $published = "true";
      } else {
        $published = "false";
      }

      $sql = "UPDATE articles SET title='$title', published=$published WHERE id=$article_id";

      if (mysqli_query($dataBaseConnection, $sql)) {
        $_SESSION['success_msg'] = "Article successfully updated";
        $isEditting = false;
        header("location: " . BASE_URL . "/articles_list.php");
      } else {
        $_SESSION['error_msg'] = "Something went wrong. Could not save post in Database";
      }
    }
  }

  function getAllArticles(){
    global $dataBaseConnection;
    $sql = "SELECT * FROM articles";
    $result = mysqli_query($dataBaseConnection, $sql);

    $articles = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $articles;
  }

  function softDeleteArticle($article_id)
  {
    global $dataBaseConnection;
    $sql = "UPDATE articles SET isDeleted=true WHERE id=$article_id";
    $result = mysqli_query($dataBaseConnection, $sql);

    $_SESSION['success_msg'] = "Post trashed!!";
    header("location: " . BASE_URL . "admin/articles/postList.php");
  }

  function dataValidator($role, $ignoreFields)
{
    global $dataBaseConnection;
    $errors = [];

    foreach ($role as $key => $value) {
        if (in_array($key, $ignoreFields)) {
            continue;
        }
        if (empty($role[$key])) {
            $errors[$key] = "This field is required";
        }
    }

    return $errors;
}

?>
