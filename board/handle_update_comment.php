<?php
session_start();
require_once('conn.php');
require_once('utils.php');

if(empty($_POST['content'])){
    header("Location:update_comment.php?errorCode=1&id=".$_POST['id']);
    die('資料不齊全');
}
//get username by session before query in 'users'. 
$username = NULL;
$ctrl_right = 0;
if(!empty($_SESSION['username'])){
    $username = $_SESSION['username'];
    $ctrl_right = getCtrlRightFromUsername($username);
}
$contnet = $_POST['content'];
$id = $_POST['id'];

if($ctrl_right == 2){
    $sql = "UPDATE `comments` SET content = ? WHERE id = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $contnet, $id);
}else{
    $sql = "UPDATE `comments` SET content = ? WHERE id = ? AND username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $contnet, $id, $username);
}
$result = $stmt->execute();
if (!$result) {
    die($conn->error);
}
header("Location:index.php");
?>