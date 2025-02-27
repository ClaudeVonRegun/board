<?php
session_start();
require 'db_connect.php';
$id = $_SESSION['id'];
$sql = 'SELECT t.id, t.tweet, t.first_name, t.last_name, t.reply, t.c_first, t.c_last
FROM (
  SELECT tweets.id, tweets.tweet, users.first_name, users.last_name, c.reply, c.first_name AS c_first, c.last_name AS c_last
  FROM (
    SELECT replys.tweet_id, replys.reply, replys.user_id, users.first_name, users.last_name
    FROM replys
    LEFT JOIN users
    ON replys.user_id = users.id
  )c
  RIGHT JOIN tweets
  ON c.tweet_id = tweets.id
  LEFT JOIN users
  ON tweets.user_id = users.id
)t
ORDER BY t.id DESC';
$stmt = $dbh->prepare($sql);
$stmt->execute();
$tweets = $stmt->fetchAll(PDO::FETCH_ASSOC);
if(isset($_POST['submit'])){
  $_SESSION['tweet_id'] = $_POST['tweet_id'];
  if($_POST['action'] === 'reply') {
    header('location:reply.php');
    exit;
  } else if($_POST['action'] === 'view') {
    header('location:article.php');
    exit;
  }
};
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
    <h3 class="py-5">全投稿一覧</h3>
    <div class="d-flex align-content-start flex-wrap p-2 gap-2">
    <?php foreach($tweets as $tweet ):?>
    <div class="card p-2" style="width: 18rem;">
      <div class="card-body">
        <h5 class="card-title">
          <?php echo htmlspecialchars($tweet["first_name"]).htmlspecialchars($tweet["last_name"])?>
        </h5>
        <p class="card-text"><?php echo htmlspecialchars($tweet["tweet"])?></p>
        <div class="d-flex gap-2">
          <form action="" method="post">
            <button type="submit" name="submit" class="btn btn-primary">リプライをする</button>
            <input type="hidden" name="tweet_id" value="<?php echo htmlspecialchars($tweet["id"]); ?>">
            <input type="hidden" name="action" value="reply">
          </form>
          <form action="" method="post">
            <button type="submit" name="submit" class="btn btn-secondary">詳細を見る</button>
            <input type="hidden" name="tweet_id" value="<?php echo htmlspecialchars($tweet["id"]); ?>">
            <input type="hidden" name="action" value="view">
          </form>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    </div>
  </div>
</main>   
</body>
</html>