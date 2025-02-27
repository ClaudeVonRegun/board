<?php
session_start();
require 'db_connect.php';
$id = $_SESSION['id'];
if(isset($_POST['tweet_id'])) {
  $_SESSION['tweet_id'] = $_POST['tweet_id'];
}
if(isset($_SESSION['tweet_id'])) {
  $tweet_id = $_SESSION['tweet_id'];
}else{
  header('Location: board.php');
  exit;
}
$sql = 'SELECT tweets.tweet, users.first_name, users.last_name 
FROM tweets
INNER JOIN users
ON tweets.user_id = users.id 
WHERE tweets.id = :tweet_id ';
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":tweet_id", $tweet_id, PDO::PARAM_INT);
$stmt->execute();
$tweet = $stmt->fetch(PDO::FETCH_ASSOC);
$sql = 'SELECT replys.reply, users.first_name, users.last_name
FROM replys
INNER JOIN users
ON replys.user_id = users.id
WHERE replys.tweet_id = :tweet_id
ORDER BY replys.id DESC';
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":tweet_id", $tweet_id, PDO::PARAM_INT);
$stmt->execute();
$replys = $stmt->fetchALL(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  <title>投稿詳細</title>
</head>
<body>
<?php include 'header.php'?>
<main>
  <div class="p-5">
  <h3 class="mt-3 mb-3">ツイート</h3> 
    <div class="card w-50">
      <div class="card-body">
        <h5 class="card-title">
          <?php echo htmlspecialchars($tweet["first_name"]).htmlspecialchars($tweet["last_name"])?>
        </h5>
        <p class="card-text"><?php echo htmlspecialchars($tweet["tweet"])?></p>
      </div>
    </div>
    <h3 class="mt-3 mb-3">リプライ一覧</h3>    
    <div class="d-flex flex-column gap-2"> 
      <?php if(count($replys) > 0): ?>
        <?php foreach($replys as $reply) :?>  
          <div class="card w-50">
            <div class="card-body">
              <h5 class="card-title">
                <?php echo htmlspecialchars($reply["first_name"]).htmlspecialchars($reply["last_name"])?>
              </h5>
              <p class="card-text"><?php echo htmlspecialchars($reply["reply"]);?></p>
            </div>
          </div>
        <?php endforeach ;?>
      <?php else: ?>
        <div class="card w-50">
          <div class="card-body">
            <p class="card-text">まだリプライはありません。</p>
          </div>
        </div>
      <?php endif; ?>
    </div>
    <div class="d-flex gap-2 mt-3">
      <a href="reply.php" class="btn btn-primary">リプライ画面へ</a>
      <a href="board.php" class="btn btn-secondary">ツイート一覧に戻る</a>
    </div>
  </div>
</main>
</body>
</html>