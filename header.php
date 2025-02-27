<?php
$id = $_SESSION['id'];
$sql = 'SELECT * FROM users WHERE users.id = :id';
$stmt = $dbh->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch();
if(isset($_POST['signout'])) {
  $_SESSION = array();
  session_destroy();
  header("Location: index.php");
  exit();
}
?>
<header class="d-flex flex-wrap justify-content-center px-4 py-2 border-bottom text-bg-dark">
  <h4 class= "mt-1 me-md-auto ">
    Welcome  <?php echo htmlspecialchars($user['first_name']) ?> <?php echo htmlspecialchars($user['last_name'])?>！
  </h4>
  <ul class="nav nav-pills ">
    <li class="nav-item"><a href="board.php" class="nav-link text-white" aria-current="page">Board</a></li>
    <li class="nav-item"><a href="post.php" class="nav-link text-white">Post</a></li>
    <li class="nav-item"><a href="mypage.php" class="nav-link text-white">Mypage</a></li>
    <li class="nav-item"><a href="user_edit.php" class="nav-link text-white">Profile_edit</a></li>
    <li class="nav-item"><a href="password_edit.php" class="nav-link text-white">Password_edit</a></li>
    <form action="" method="post">
      <button type="submit" name="signout" value="1" class="btn btn-warning" onclick="location.href = 'index.php'">Sign-out</button>
    </form>
  </ul>
</header>
