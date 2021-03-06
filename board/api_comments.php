<?php 
    require_once('conn.php');
    if(!empty($conn->error) ){
        echo "連線失敗!!<br>";
    }

    //for 翻頁功能
    $page = 1;
    if(!empty($_POST['page'])){
        $page = (int)$_POST['page'];
    }
    $limit = 5;
    $offset = ($page-1)*$limit;

    // sql for 從 comments 資料表依 username 欄位關聯到 users 資料表，並取出對應 nickname
    $sql = "SELECT A.id AS id, A.content as content, A.created_at AS created_at, ".
    "B.username AS username, B.nickname AS nickname FROM `comments` AS A ".
    "LEFT JOIN `users` AS B ON A.username = B.username ".
    "WHERE A.is_deleted is NULL ".
    "ORDER BY id DESC ".
    "LIMIT ? OFFSET ? ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
    $result = $stmt->execute();
    if(!$result){
        die('Error : ' . $conn->error);
    }
    $result = $stmt->get_result();

    $comments = array();
    while($row = $result->fetch_assoc()){
        array_push($comments, array(
            "id" => $row['id'],
            "username" => $row['username'],
            "nickname" => $row['nickname'],
            "content" => $row['content'],
            "created_at" => $row['created_at']
        ));
    }
    //get count and calcute total page and send to api_demo.html
    $sql = "SELECT COUNT(id) AS count FROM `comments` WHERE is_deleted IS NULL";
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute();
    if(!$result){
        die('Error : ' . $conn->error);
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = $row['count'];
    $total_page = ceil($count/$limit);

    $json_data =  array(
        "comments" => $comments,
        "page" => $page,
        "total_page" => $total_page
    );
    $response = json_encode($json_data);

    header('Content-Type: application/json; charset=utf-8');
    echo $response;
?>