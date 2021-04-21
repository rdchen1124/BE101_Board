<?php
session_start();
session_destroy();
// after delete seeion by session_destroy, go back to index.php.
header("Location:index.php");
?>