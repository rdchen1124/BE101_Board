<?php
require_once('conn.php');

//get user infomation by username(which comes from sessoin).
function getUserFromUsername($username){
    //sql : find username by token in tokens
    global $conn;
    $sql = "SELECT * FROM `users` WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $result = $stmt->execute();
    if(!$result){
        die('Error : ' . $conn->error);
    }
    $result = $stmt->get_result();
    // //sql : find user infomation in 'users' by username
    // $sql = sprintf(
    //     "SELECT * FROM `users` WHERE username = '%s'",
    //     $username
    // );
    // $result = $conn->query($sql);
    // $row = $result->fetch_assoc();
    $row = $result->fetch_assoc();
    return $row;
}

function escapeCharater($string){
    return htmlspecialchars($string, ENT_QUOTES);
}

function getCtrlRightFromUsername($username){
    global $conn;

    $sql = 'SELECT ctrl_right FROM `users` WHERE username = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $result = $stmt->execute();
    if(!$result){
        die("Error : ".$conn->error);
    }
    $result = $stmt->get_result();
    $ctrl_right = $result->fetch_assoc()['ctrl_right'];
    return $ctrl_right;
}

?>