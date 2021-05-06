<?php
session_start();
require_once('conn.php');
require_once('utils.php');

if(empty($_POST['username'])){
    header("Location:edit_user.php?errorCode=1");
    die('資料不齊全');
}
//get username by session before query in 'users'. 
$admin = $_SESSION['username'];
$username = $_POST['username'];
$user_ctrl_right = $_POST['ctrl_right_'.$username];



$ctrl_right = 0;
$ctrl_right = getCtrlRightFromUsername($admin);
if($ctrl_right != 2){
    header("Location:edit_user.php?errorCode=2");
    die('編輯者權限有誤');
}

$sql = "UPDATE `users` SET ctrl_right = ? WHERE username = ? ";
$stmt = $conn->prepare($sql);
$stmt->bind_param('is', $user_ctrl_right, $username);
$result = $stmt->execute();
if(!$result){
    die("Error : ".$conn->error);
}
header("Location:edit_user.php");
// echo "<h1>";
// echo "Adminstrator : ".$admin.", Username : ".$username.", Selected : ".$user_ctrl_right;
// echo "</h1>";

?>