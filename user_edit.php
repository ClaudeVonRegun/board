<?php
session_start();
require 'db_connect.php';
$id = $_SESSION['id'];
$sql ='SELECT * FROM users WHERE users.id = :id';
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if(!empty($_POST)){
  $hasChanges = false;
  $first_name = $user['first_name'];
  $last_name = $user['last_name'];
  $email = $user['email'];
  $error = false;
  
  if(!empty($_POST['first_name'])) {
    $first_name = $_POST['first_name'];
    $hasChanges = true;
  }
  
  if(!empty($_POST['last_name'])) {
    $last_name = $_POST['last_name'];
    $hasChanges = true;
  }
  
  if(!empty($_POST['email'])) {
    $email = $_POST['email'];
    if($email !== $user['email']) {
      $sql = 'SELECT COUNT(*) FROM users WHERE email = :email AND id != :id';
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(":email", $email, PDO::PARAM_STR);
      $stmt->bindValue(":id", $id, PDO::PARAM_INT);
      $stmt->execute();
      $count = $stmt->fetchColumn();
      if($count > 0) {
        $_SESSION['error_message'] = 'このメールアドレスは既に使用されています';
        header('Location: user_edit.php');
        exit();
      } else {
        $hasChanges = true;
      }
    }
  }  
  
  if($hasChanges && !$error) {
    $sql = 'UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email  WHERE id = :id';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->bindValue(":first_name", $first_name, PDO::PARAM_STR);
    $stmt->bindValue(":last_name", $last_name, PDO::PARAM_STR);
    $stmt->bindValue(":email", $email, PDO::PARAM_STR);
    $stmt->execute();
    
    $_SESSION['message'] = '情報を更新しました';
    header('Location: user_edit.php');
    exit();
  } else {
    $_SESSION['error_message'] = '変更する項目を入力してください';
    header('Location: user_edit.php');
    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="css/style.css">
  <title>Document</title>
</head>
<body>
  <?php include 'header.php'?>
<main>
  <div class="container">
    <h3 class="py-5">会員情報を編集する</h3>
    <?php if(isset($_SESSION['message'])): ?>
      <div class="alert alert-info w-50">
        <?php 
          echo htmlspecialchars($_SESSION['message']);
          unset($_SESSION['message']); 
        ?>
      </div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error_message'])): ?>
      <div class="alert alert-danger w-50">
        <?php 
          echo htmlspecialchars($_SESSION['error_message']);
          unset($_SESSION['error_message']); 
        ?>
      </div>
    <?php endif; ?>
    <form action="" method="post" class="registrationform">
   
      <label for="basic-url" class="form-label">first_name</label>
      <div class="edit-first_name d-flex mb-2">
        <div class="input-group ">
          <div class="form-control">
          <?php echo htmlspecialchars($user['first_name']); ?>
          </div>
        </div>
        <div class="input-group ms-2">
          <input type="text" name="first_name" class="form-control" placeholder="変更するfirst_nameを入力してください" aria-label="first_name" aria-describedby="basic-addon1">
        </div>
      </div>

      <label for="basic-url" class="form-label">last_name</label>
      <div class="edit-fist_name d-flex mb-2">
        <div class="input-group">
          <div class="form-control">
          <?php echo htmlspecialchars($user['last_name']); ?>
          </div>
        </div>
        <div class="input-group ms-2">
          <input type="text" name="last_name" class="form-control" placeholder="変更するlast_nameを入力してください" aria-label="last_name" aria-describedby="basic-addon2">
        </div>
      </div>

      <label for="basic-url" class="form-label">email</label>
      <div class="edit-email d-flex mb-4">
        <div class="input-group">
          <div class="form-control">
          <?php echo htmlspecialchars($user['email']); ?>
          </div>
        </div>
        <div class="input-group ms-2">
          <input type="email" name="email" class="form-control" placeholder="変更するemailを入力してください" aria-label="email" aria-describedby="basic-addon1">
        </div>
      </div>
      <input type="submit" class="btn btn-primary" value="再登録する" class="edit btn">
    </form>
  </div>
</main>  
</body>
</html>