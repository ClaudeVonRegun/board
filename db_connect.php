<?php
$dsn = 'mysql:dbname=twitter2;host=127.0.0.1';
$user = 'root';
$password = '';

try {
    $dbh = new PDO ($dsn, $user, $password);
    $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
} catch (PDOException $e) {
    echo 'システムエラーが発生しました。しばらく経ってからもう一度お試しください。' . $e->getMessage();
    exit();
}
?>