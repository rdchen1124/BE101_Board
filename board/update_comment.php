<?php 
    session_start();
    require_once('conn.php');
    require_once('utils.php');
    if(!empty($conn->error) ){
        echo "連線失敗!!<br>";
    }
    $id = $_GET['id'];

    $username = NULL;
    $user = NULL;

    if(!empty($_SESSION['username'])){
        $username = $_SESSION['username'];
        $user = getUserFromUsername($username);
    }
    
    $sql = "SELECT * FROM `comments` WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    if(!$result){
        die('Error : ' . $conn->error);
    }

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
?>
<!doctype html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <title>留言板</title>
    <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header class='warning'>
            <strong>This is a test web project!</strong>
        </header>
        <main class='board'>
            <h1 class='board__title'>編輯留言</h1>
            <?php
                if(!empty($_GET['errorCode'])){
                    $code = $_GET['errorCode'];
                    $msg = "資料有誤";
                    if($code == '1'){
                        echo "<h2 class='error'>".$msg."</h2>";
                    }
                }
            ?>
            <form class='board__comment-form' method='POST' action='handle_update_comment.php'>
                <textarea name='content' rows='4' ><?php echo escapeCharater($row['content']); ?></textarea>
                <input type='hidden' name='id' value="<?php echo $id; ?>" />
                <input class='board__submit-btn' type='submit' />
            </form>
        </main>
    </body>
</html>