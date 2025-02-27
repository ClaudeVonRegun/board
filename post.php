<?php
require 'db_connect.php';
session_start();
$id = $_SESSION['id'];
if(!empty($_POST['submit'])){
  if(isset($_POST['tweet'])){
    $tweet = $_POST['tweet'];
    $sql = 'INSERT INTO tweets(user_id, tweet) VALUES(:id, :tweet)';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":tweet", $tweet, PDO::PARAM_STR);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    header('location:board.php');
    exit;
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
    <h3 class="py-5">新しいツイートをする</h3>
    <form action="" method="post">
      <div class="mb-5">
        <label for="exampleFormControlTextarea1" class="form-label">今の気持ちをツイートしよう！</label>
        <textarea class="form-control mb-5 w-50" name="tweet" id="exampleFormControlTextarea1" rows="5"></textarea>
        <button type="submit" name="submit" value="1" class="btn btn-primary">投稿する</button>
      </div>
    </form>
  </div>
</main>
</body>
</html>