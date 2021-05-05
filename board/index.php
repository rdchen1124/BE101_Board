<?php 
    session_start();
    require_once('conn.php');
    require_once('utils.php');
    if(!empty($conn->error) ){
        echo "連線失敗!!<br>";
    }
    $username = NULL;
    $user = NULL;
    $ctrl_right = 0;
    if(!empty($_SESSION['username'])){
        $username = $_SESSION['username'];
        $user = getUserFromUsername($username);
        $ctrl_right = getCtrlRightFromUsername($username);
    }
    
    $page = 1;
    if(!empty($_GET['page'])){
        $page = (int)$_GET['page'];
    }
    $limit = 5;
    $offset = ($page-1)*$limit;

    // $ctrl_right = 2;

    $sql = "SELECT A.id AS id, A.content as content, A.created_at AS created_at, ".
    "B.username AS username, B.nickname AS nickname, B.ctrl_right AS ctrl_right FROM `comments` AS A ".
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
                    <span class='board__btn update-nickname'>編輯暱稱</span>
                    <form class='hide board__nickname_form board__comment-form' method='POST' action='handle_update_nickname.php'>
                        <div class='board__nickname'>
                            <span>新的暱稱 : </span>
                            <input type='text' name='nickname'/>
                        </div>
                        <input class='board__submit-btn' type='submit' />
                    </form>
                    <h3>您好, <?php echo $user['nickname'] ?></h3>
                <?php } ?>
            </div>
            <h1 class='board__title'>Comments</h1>
            <?php
                if(!empty($_GET['errorCode'])){
                    $code = $_GET['errorCode'];
                    $msg = "Default";
                    if($code == '1'){
                        $msg = "資料有誤";
                    }
                    if($code == '2'){
                        $msg = "權限有誤";
                    }
                    echo "<h2 class='error'>".$msg."</h2>";
                }
            ?>
            <form class='board__comment-form' method='POST' action='handle_add_comment.php'>
                <textarea name='content' rows='4'></textarea>
                <?php if($username){ 
                    if($ctrl_right >= 1){ ?> 
                    <input class='board__submit-btn' type='submit' />
                <?php }else{ ?>
                    <h3> 抱歉 <?php echo $user['nickname'] ?> ， 您已被停權，無法發布留言!! </h3>
                <?php } 
                    }else{ ?>
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
                                <span class='card__author'>
                                    <?php echo escapeCharater($row['nickname']); ?>
                                    (@<?php echo escapeCharater($row['username']); ?>)
                                </span>
                                <span class='card__date'><?php echo escapeCharater($row['created_at']); ?></span>
                                <?php if($row['username'] === $username || $ctrl_right == 2){ ?>
                                    <a href="update_comment.php?id=<?php echo $row['id']; ?>">編輯</a>
                                    <a href="handle_delete_comment.php?id=<?php echo $row['id']; ?>">刪除</a>
                                <?php } ?>
                            </div>
                            <p class='card__content'>
                                <?php echo escapeCharater($row['content']); ?>
                            </p>
                        </div>
                    </div>
                <?php } ?>
            </section>
            <div class='board__hr'></div>
            <?php 
                $sql = "SELECT COUNT(id) AS count FROM `comments` WHERE is_deleted is NULL ";
                $stmt = $conn->prepare($sql);
                $result = $stmt->execute();
                if(!$result){
                    die('Error : ' . $conn->error);
                }

                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $count = $row['count'];
                $totol_page = ceil($count/$limit);
            ?>
            <div class='page-info'>
                <span>總共 <?php echo $count ?> 筆留言，</span>
                <span>第 <?php echo $page ?>頁 / 總共 <?php echo $totol_page ?> 頁</span>
            </div>
            <div class='paginator'>
                <?php if($page != 1){ ?>
                    <a href='index.php?page=1' >到首頁</a>
                    <a href='index.php?page=<?php echo ($page-1)?>'>上一頁</a>
                <?php } ?><?php if($page != $totol_page) { ?>
                    <a href='index.php?page=<?php echo ($page+1)?>'>下一頁</a>
                    <a href='index.php?page=<?php echo $totol_page?>'>到末頁</a>
                <?php } ?>
            </div>
        </main>
        <script>
            try {
                var btn = document.querySelector(".update-nickname");
                btn.addEventListener('click', function(){
                    var form = document.querySelector(".board__nickname_form");
                    form.classList.toggle('hide');
                })
            } catch (e) {
                console.log(e);
                console.log("未登入所以找不到 class=update-nickname 的 button");
            }
        </script>
    </body>
</html>