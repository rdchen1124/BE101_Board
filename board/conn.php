<?php 
//此為PHP連接 SQL 的範例
$server = 'localhost';//檔案庫的Server網址
$user = 'ryan';//account
$password = 'ryan';//password
$db_name = 'ryan';//database
$conn = new mysqli($server, $user, $password, $db_name);//建立連線成功!!
?>