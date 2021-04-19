<?php
$cookieName = "username";
$expires = time() - 3600;
setcookie($cookieName, "", $expires);
header("Location:index.php");
?>