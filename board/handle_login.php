<?php
session_start();
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
// check if username is match and return this information, or pop error.
$sql = sprintf(
    "SELECT * FROM `users` WHERE username = '%s'",
    $username
);

$result = $conn->query($sql);
if (!$result) {
    die($conn->error);
}

// if result is empty, back to login page and set errorCode = 2.
if ($result->num_rows === 0){
    header("Location: Location:login.php?errorCode=2");
}
$row = $result->fetch_assoc();

//check password with hash of php
if(password_verify($password, $row['password'])){
    // set cookie by session.
    $_SESSION['username'] = $username;
    header("Location:index.php");
} else{
    header("Location:login.php?errorCode=2");
}
?>