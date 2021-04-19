<?php
require_once('conn.php'); 
if(
    empty($_POST['username']) || 
    empty($_POST['password'])
    ){
    header("Location:login.php?errorCode=1");
    die();
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = sprintf(
    "SELECT * FROM `users` WHERE username = '%s' AND password = '%s'",
    $username,
    $password
);
$result = $conn->query($sql);

if (!$result) {
    die($conn->error);
}

if ($result->num_rows){
    echo "登入成功";
    $expires = time() + 3600*24*30;//let expire of cookie as one month.
    $cookieName = "username";
    setcookie($cookieName, $username, $expires);
    header("Location:index.php");
}
else{
    header("Location:login.php?errorCode=2");
}
?>