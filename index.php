<?php
session_start();
require 'db_connect.php';
$msg = "";
if(!empty($_POST)){
  if(isset($_POST['email']) && isset($_POST['password'])){
    $_SESSION = array();
    session_destroy();
    session_start(); 
    $email = $_POST['email'];
    $hashed= hash('sha256', $_POST['password']);
    $sql = 'SELECT * FROM users WHERE email = :email';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":email", $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($user){
      if($hashed == $user['password']){
        session_regenerate_id();
        $_SESSION['id'] = $user['id'];
        header('Location: board.php');
        exit;
      }else{
        $msg = "email、またはpasswordが間違っています。";
      }
    }else{
      $msg = "email、またはpasswordが間違っています。";
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
  <title>Document</title>
</head>
<body>
  <div class="container">
    <h1 class="py-5">ログイン画面</h1>
    <form action="", method="post" class="loginform">  
      <div class="mb-3 w-50">
        <label for="basic-url" class="form-label">メールアドレスを入力してください</label>
        <div class="input-group">
          <input type="email" name="email" class="form-control" placeholder="メールアドレス" aria-label="Username" aria-describedby="basic-addon1">
        </div>
      </div>
      <div class="mb-4 w-50">
        <label for="basic-url" class="form-label">パスワードを入力してください</label>
        <div class="input-group">
        <input type="password" name="password" class="form-control" placeholder="パスワード" aria-label="Recipient's username" aria-describedby="basic-addon2">
        </div>
        <?php if(!empty($msg)):?>
          <div class="alert alert-danger mt-4">
            <?php echo htmlspecialchars($msg);?>
          </div>        
        <?php endif;?>
      </div>
      <input type="submit" class="btn btn-primary mb-4" value="ログインする" >
    </form>
    <h2 class="mb-4">パスワードをお持ちでない方へ</h2>
    <input type="submit" class="btn btn-primary" value="新規登録する" onclick="location.href='register.php'">
  </div>
</body>
</html>