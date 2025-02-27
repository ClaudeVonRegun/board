<?php
require 'db_connect.php';
session_start();
$error_message = '';
if(!empty($_POST['submit'])) {
  if(empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['email']) || empty($_POST['password'])) {
    $error_message = '全ての項目を入力してください';
  } 
  else if(strlen($_POST['password']) < 8) {
    $error_message = 'パスワードは8文字以上で入力してください';
  }
  else {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $hashed = hash('sha256', $_POST['password']);
    $sql = 'SELECT COUNT(*) FROM users WHERE email = :email';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":email", $email, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    if($count > 0) {
      $error_message = 'このメールアドレスは既に使用されています';
    } else {
      $sql = 'INSERT INTO users(first_name, last_name, email, password) 
      VALUES (:first_name, :last_name, :email, :password)';
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(":first_name", $first_name, PDO::PARAM_STR);
      $stmt->bindValue(":last_name", $last_name, PDO::PARAM_STR);
      $stmt->bindValue(":email", $email, PDO::PARAM_STR);
      $stmt->bindValue(":password", $hashed, PDO::PARAM_STR);
      $stmt->execute();
      $sql = 'SELECT * FROM users WHERE email = :email';
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(":email", $email, PDO::PARAM_STR);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      $_SESSION['id'] = $user['id'];
      header('location: board.php');
      exit();
    }
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
  <link rel="stylesheet" href="css/style.css" >
  <title>会員登録フォーム</title>
</head>
<body>
  <div class="container">
    <h1 class="py-5">新規会員登録をする</h1>
    <?php if(!empty($error_message)): ?>
      <div class="alert alert-danger">
        <?php echo htmlspecialchars($error_message); ?>
      </div>
    <?php endif; ?>
    <?php if(isset($_SESSION['message'])): ?>
      <div class="alert alert-danger">
        <?php 
          echo htmlspecialchars($_SESSION['message']);
          unset($_SESSION['message']); 
        ?>
      </div>
    <?php endif; ?>
    <form action="" method="post" class="registrationform">
      <div class="row g-3 mb-5">
        <div class="col-6">
          <label>First name</label>
          <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
        </div>
        <div class="col-6">
          <label>Last name</label>
          <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
        </div>
        <div class="col-12">
          <label>Email</label>
          <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>
        <div class="col-12">
          <label>パスワード</label>
          <input type="password" name="password" class="form-control" placeholder="8文字以上で入力してください" value="<?php echo htmlspecialchars($_POST['password'] ?? ''); ?>">
        </div>
      </div>  
      <div class="d-flex gap-3 align-items-center">
        <button class="btn btn-primary" type="submit" name="submit" value="1">新規登録する</button>
        <a href="index.php" class="btn btn-secondary">ログイン画面に戻る</a>
      </div>    
    </form>
  </div>
</body>
</html>