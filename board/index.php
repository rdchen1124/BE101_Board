<?php 
    session_start();
    require_once('conn.php');
    require_once('utils.php');
    if(!empty($conn->error) ){
        echo "連線失敗!!<br>";
    }
    $username = NULL;
    if(!empty($_SESSION['username'])){
        $username = $_SESSION['username'];
    }
    $result = $conn->query("SELECT * FROM `comments` ORDER BY id DESC ");
    if(!$result){
        die('Error : ' . $conn->error);
    }
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
            <div>
                <?php if(!$username){ ?> 
                    <a class='board__btn' href='register.php'>註冊</a>
                    <a class='board__btn' href='login.php'>登入</a>
                <?php }else{ ?>
                    <a class='board__btn' href='logout.php'>登出</a>
                    <h3>您好, <?php echo $username ?></h3>
                <?php } ?>
            </div>
            <h1 class='board__title'>Comments</h1>
            <?php
                if(!empty($_GET['errorCode'])){
                    $code = $_GET['errorCode'];
                    $msg = "資料有誤";
                    if($code == '1'){
                        echo "<h2 class='error'>".$msg."</h2>";
                    }
                }
            ?>
            <form class='board__comment-form' method='POST' action='handle_add_comment.php'>
                <textarea name='content' rows='4'></textarea>
                <?php if($username){ ?> 
                    <input class='board__submit-btn' type='submit' />
                <?php }else{ ?>
                    <h3> 請登入後再發布留言!! </h3>
                <?php } ?>
            </form>
            <div class='board__hr'></div>
            <section>
                <?php while($row = $result->fetch_assoc()){ ?>
                    <div class='card'>
                        <div class='card__avatar'>

                        </div>
                        <div class='card__body'>
                            <div class='card__info'>
                                <span class='card__author'><?php echo $row['nickname']; ?></span>
                                <span class='card__date'><?php echo $row['created_at']; ?></span>
                            </div>
                            <p class='card__content'>
                                <?php echo $row['content']; ?>
                            </p>
                        </div>
                    </div>
                <?php } ?>
            </section>
        </main>
    </body>
</html>