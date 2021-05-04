<?php
session_start();
require_once('conn.php');
require_once('utils.php');

if(empty($_GET['id'])){
    header("Location:index.php?errorCode=1");
    die('資料有誤');
}
//get username by session before query in 'users'. 
$username = $_SESSION['username'];
$is_deleted = 1;
$id = $_GET['id'];

$sql = "UPDATE `comments` SET is_deleted = ? WHERE id = ? AND username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $is_deleted, $id, $username);
$result = $stmt->execute();
if (!$result) {
    die($conn->error);
}
header("Location:index.php");
?>