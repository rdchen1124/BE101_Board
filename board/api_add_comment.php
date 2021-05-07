<?php
require_once('conn.php');
header('Content-Type: application/json; charset=utf-8');


if(empty($_POST['content'])||empty($_POST['username'])){
    // header("Location:index.php?errorCode=1");
    // die('資料不齊全');
    $json_data = array(
        'ok' => false,
        'message' => "both username and content can't be empty." 
    );
    $response = json_encode($json_data);
    echo $response;
    die();
}

$username = $_POST['username'];
$content = $_POST['content'];

$sql = "INSERT INTO `comments`(username, content) VALUES (?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $content);
$result = $stmt->execute();

if (!$result) {
    $json_data = array(
        'ok' => false,
        'message' => $conn->error 
    );
    $response = json_encode($json_data);
    echo $response;
    die();
}

$json_data = array(
    'ok' => true,
    'message' => "adding comment succed." 
);

$response = json_encode($json_data);
echo $response;
?>