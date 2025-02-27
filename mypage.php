<?php
session_start();
require 'db_connect.php';
$id = $_SESSION['id'];
$sql = 'SELECT users.first_name, users.last_name, tweets.id, tweets.tweet
FROM users 
LEFT JOIN tweets
ON users.id = tweets.user_id WHERE users.id = :id 
ORDER BY tweets.id DESC';
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchALL(PDO::FETCH_ASSOC);

if(isset($_POST['submit'])){
  $_SESSION['tweet_id'] = $_POST['tweet_id'];
  header('location:article.php');
  exit;
};
?>
<!DOCTYPE html>
<html lang="ja" class="h-100">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="css/style.css">
  <title>Mypage</title>
</head>
<body class="d-flex flex-column h-100" >
  <?php include 'header.php'?>
<main> 
  <div class="container">  
    <div class="py-5"> 
      <h3 class="mb-5">あなたの今までのツイート</h3>
      <?php if($users[0]['tweet']): ?>
      <?php foreach($users as $user): ?>
      <div class="card p-2 mb-2 w-50">
        <div class="card-body ">
          <p class="card-text"><?php echo htmlspecialchars($user["tweet"])?></p>
          <form action="" method="post">
            <button type="submit" name="submit" class="btn btn-primary" >コメントを確認する</button>
            <input type="hidden" name="tweet_id" value="<?php echo htmlspecialchars($user["id"]); ?>">
          </form>
        </div>
      </div>
      <?php endforeach; ?>
      <?php else:?>
        <p>まだ投稿はありません。</p>
      <?php endif; ?>
    </div>
  </div>
</main>
<footer class="footer mt-auto bg-body-tertiary ">   
  <div class="d-flex justify-content-between align-items-center"> 
    <div class="col-md-3 d-flex flex-column align-items-center p-3">
      <h5 class="mb-3">新しい投稿をする！</h5>
      <button type = "button" class="btn btn-secondary mb-3" onclick="location.href='post.php'">記事を投稿する</button>
    </div>
    <div class="col-md-3 d-flex flex-column align-items-center p-3">
      <h5 class="mb-3">全ての投稿を確認する</h5>
      <button type = "button" class="btn btn-secondary mb-3" onclick="location.href='board.php'">全投稿一覧へ</button>
    </div>
    <div class="col-md-3 d-flex flex-column align-items-center p-3">
      <h5 class="mb-3">会員情報を編集する</h5>
      <button type = "button" class="btn btn-secondary mb-3" onclick="location.href='user_edit.php'">会員情報編集ページへ</button>
    </div>
    <div class="col-3 d-flex flex-column align-items-center p-3">
      <h5 class="mb-3">パスワードを変更する</h5>
      <button type = "button" class="btn btn-secondary mb-3" onclick="location.href='password_edit.php'">パスワード変更ページへ</button>
    </div>  
  </div>
</footer>
</body>
</html>