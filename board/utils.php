<?php
require_once('conn.php');
//generate tokens
function generateToken(){
    $str = '';
    for ($i = 0; $i < 16 ; $i++) { 
        $str .= chr(rand(65,90));//A-Z
    }
    return $str;
}

//get user infomation by tokens 
function getUserFromToken($token){
    //sql : find username by token in tokens
    global $conn;
    $token_sql = sprintf(
        "SELECT username FROM `tokens` WHERE token = '%s'",
        $token,
    );

    $token_result = $conn->query($token_sql);
    // if(!$token_result){
    $row = $token_result->fetch_assoc();
    $username = $row['username'];
    // }
    //sql : find user infomation in 'users' by username
    $user_sql = sprintf(
        "SELECT * FROM `users` WHERE username = '%s'",
        $username
    );
    $user_result = $conn->query($user_sql);
    $row = $user_result->fetch_assoc();
    return $row;
}
?>