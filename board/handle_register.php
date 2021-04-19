<?php
require_once('conn.php'); 
if(
    empty($_POST['nickname']) || 
    empty($_POST['username']) || 
    empty($_POST['password'])
    ){
    header("Location:register.php?errorCode=1");
    die();
}

$nickname = $_POST['nickname'];
$username = $_POST['username'];
$password = $_POST['password'];

$sql = sprintf(
    "INSERT INTO `users`(nickname, username, `password`) VALUES ('%s','%s','%s')",
    $nickname,
    $username,
    $password
);
echo $sql.'<br>';
$result = $conn->query($sql);
if (!$result) {
    $code = $conn->errno;
    if($code === 1062){
        header("Location:register.php?errorCode=2");
    }
    die($conn->error);
}
header("Location:login.php");
?>