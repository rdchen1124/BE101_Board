<?php
require_once('conn.php');
//Delete this token in 'tokens' when user logout.
$token = $_COOKIE['token'];
$sql = sprintf(
    "DELETE FROM `tokens` WHERE token = '%s'",
    $token
);
$conn->query($sql);

$cookieName = "token";
$expires = time() - 3600;
setcookie($cookieName, "", $expires);
header("Location:index.php");
?>