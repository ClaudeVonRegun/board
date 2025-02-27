<?php
session_start();
require_once('db_connect.php');
$id = $_SESSION['id'];
$sql = 'SELECT users.password
FROM users
WHERE users.id = :id';
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$password = $stmt->fetch(PDO::FETCH_ASSOC);
if(!empty($_POST['submit'])){
  $hashed= hash('sha256', $_POST['password']);
  $new_password = $_POST['new_password'];
  $new = hash('sha256', $new_password);
  if($hashed == $password['password']){
    $sql = 'UPDATE users SET password = :new
    WHERE users.id = :id';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->bindValue(":new", $new, PDO::PARAM_STR);
    $stmt->execute();
    header('location:welcome.php');
    exit;
  }else{
    $msg = "password変更に失敗しました。";
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
    <?php if(!empty($msg)):?>
      <div class="alert alert-danger mt-4">
        <?php echo htmlspecialchars($msg);?>
      </div>        
    <?php endif;?>
    <form action=""  method="post" class="loginform">
    <div class="mb-5 w-50">
      <label for="basic-url" class="form-label">新しいパスワードを入力してください</label>
      <div class="input-group">
        <input type="password" name="new_password" class="form-control" placeholder="new_password" aria-label="password" aria-describedby="basic-addon1">
      </div>
    </div>
    <div class="mb-5 w-50">
      <label for="basic-url" class="form-label">従来のパスワードを入力してください</label>
      <div class="input-group">
        <input type="password" name="password" class="form-control" placeholder="password" aria-label="password" aria-describedby="basic-addon2">
      </div>
    </div>
    <input type="submit" name="submit" class="btn btn-primary mb-5" value="パスワードを変更する" class="password_edit btn">    
    </form>    
  </div>
</main>  
</body>
</html>