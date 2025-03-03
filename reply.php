<?php
require 'db_connect.php';
session_start();
$id = $_SESSION['id'];
$tweet_id = $_SESSION['tweet_id'];
$sql = 'SELECT users.first_name, users.last_name, tweets.id, tweets.tweet
FROM users
LEFT JOIN tweets
ON users.id = tweets.user_id
WHERE tweets.id = :tweet_id';
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":tweet_id", $tweet_id, PDO::PARAM_INT);
$stmt->execute();
$tweet = $stmt->fetch(PDO::FETCH_ASSOC);
if(!empty($_POST['submit'])){
  if(isset($_POST['reply'])){
    $reply = $_POST['reply'];
    $sql = "INSERT INTO replys(tweet_id, user_id, reply) VALUES(:tweet_id, :id, :reply)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->bindValue(":tweet_id", $tweet_id, PDO::PARAM_STR);
    $stmt->bindValue(":reply", $reply, PDO::PARAM_STR);
    $stmt->execute();
    header('location:article.php');
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
<div class="container">  
  <h3 class="py-5">リプライをする</h3>
  <div class="card mb-4">
    <div class="card-header">
      元のツイート
    </div>
    <div class="card-body">
      <h5 class="card-title">
        <?php echo htmlspecialchars($tweet["first_name"]).htmlspecialchars($tweet["last_name"])?>
      </h5>
      <p class="card-text"><?php echo htmlspecialchars($tweet["tweet"])?></p>
    </div>
  </div>
  <form action="" method="post">
    <div class="mb-5">
      <label for="exampleFormControlTextarea1" class="form-label">あなたのリプライをどうぞ</label>
      <textarea class="form-control mb-3" name="reply" id="exampleFormControlTextarea1" rows="3" required></textarea>
    </div>
    <div class="d-flex gap-2">
      <button type="submit" name="submit" value="1" class="btn btn-primary">リプライする</button>
      <a href="board.php" class="btn btn-secondary">戻る</a>
    </div>
  </form>
</div>
  </div>
</body>
</html>