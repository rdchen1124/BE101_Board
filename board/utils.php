<?php
require_once('conn.php');

//get user infomation by username(which comes from sessoin).
function getUserFromUsername($username){
    //sql : find username by token in tokens
    global $conn;

    //sql : find user infomation in 'users' by username
    $sql = sprintf(
        "SELECT * FROM `users` WHERE username = '%s'",
        $username
    );
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row;
}
?>