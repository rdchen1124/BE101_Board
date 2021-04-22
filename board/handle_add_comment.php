<?php
session_start();
require_once('conn.php');
require_once('utils.php');
if(empty($_POST['content'])){
    header("Location:index.php?errorCode=1");
    die('資料不齊全');
}
//get username by session before query in 'users'. 
$username = $_SESSION['username'];
$row = getUserFromUsername($username);

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