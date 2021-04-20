<?php
require_once('conn.php');
require_once('utils.php');
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

//login successfully, generate token and insert it to 'tokens'.
if ($result->num_rows){
    $token = generateToken();
    $token_sql = sprintf(
        "INSERT INTO `tokens`(token, username) VALUES('%s', '%s')",
        $token,
        $username
    );
    $token_result = $conn->query($token_sql);
    if(!$token_result){
        die($conn->error);
    }
    //insert token by username successful.
    $expires = time() + 3600 * 24 * 30;//let expire of cookie as one month.
    $cookieName = "token";
    setcookie($cookieName, $token, $expires);
    header("Location:index.php");
}
else{
    header("Location:login.php?errorCode=2");
}
?>