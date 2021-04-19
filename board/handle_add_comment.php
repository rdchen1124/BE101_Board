<?php
require_once('conn.php'); 
if(empty($_POST['content'])){
    header("Location:index.php?errorCode=1");
    die('資料不齊全');
}

$username = $_COOKIE['username'];
$findUser_sql = sprintf(
    "SELECT nickname FROM `users` WHERE username = '%s'",
    $username
);
$findUser_result = $conn->query($findUser_sql);
if (!$findUser_result) {
    die($conn->error);
}
$row = $findUser_result->fetch_assoc();
$nickname = $row['nickname'];

$content = $_POST['content'];
$sql = sprintf(
    "INSERT INTO `comments`(nickname, content) VALUES ('%s','%s')",
    $nickname,
    $content
);

$result = $conn->query($sql);
if (!$result) {
    die($conn->error);
}
header("Location:index.php");
?>