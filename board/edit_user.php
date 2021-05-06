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
    
    //將編輯使用者權限頁面依 $ctrl_right 卡控
    if($ctrl_right != 2){
        header("Location: index.php?errorCode=2");
        die('您的權限不符，不允許進入此頁面');
    }
    //for 翻頁功能
    $page = 1;
    if(!empty($_GET['page'])){
        $page = (int)$_GET['page'];
    }
    $limit = 5;
    $offset = ($page-1)*$limit;
    // sql for 從 users 資料表取出對應  username, nickname, ctrl_right
    $sql = "SELECT username, nickname, ctrl_right FROM `users`";
    // $sql = "SELECT username, nickname, ctrl_right FROM `users` LIMIT ? OFFSET ? ";
    $stmt = $conn->prepare($sql);
    // $stmt->bind_param("ii", $limit, $offset);
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
                <a class='board__btn' href='index.php'>返回首頁</a>
                <h3>您好, <?php echo $user['nickname'] ?></h3>
            </div>
            <h1 class='board__title'>管理使用者權限</h1>
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
            <!-- <form class='board__comment-form' method='POST' action='handle_add_comment.php'>
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
            <div class='board__hr'></div> -->
            <section>
                <?php while($row = $result->fetch_assoc()){ ?>
                    <!-- <tr>
                        <td><?php echo escapeCharater($row['username']); ?></td>
                        <td><?php echo escapeCharater($row['nickname']); ?></td>
                        <td><?php echo escapeCharater($row['ctrl_right']); ?></td>
                    </tr> -->
                    <div class='role_row'>
                        <div class='role_col'>
                            <?php echo escapeCharater($row['username']); ?>
                        </div>
                        <div class='role_col'>
                            <?php echo escapeCharater($row['nickname']); ?>
                        </div>
                        <div class='role_col'>
                            <form method='POST' action='handle_edit_user.php'>
                                <select name='ctrl_right_<?php echo escapeCharater($row['username']); ?>'>
                                    <?php 
                                        for($i=0; $i<=2; $i++){
                                            if($i == $row['ctrl_right']){
                                                echo "<option value=".$i." selected>".$i."</option>";
                                            }else{
                                                echo "<option value=".$i.">".$i."</option>";
                                            }
                                        }
                                    ?>
                                    <!-- <option value="0">baned</option>
                                    <option value="1">normal</option>
                                    <option value="2">admin</option> -->
                                </select>
                                <input type='submit' <?php if($username == $row['username']) echo "disabled"; ?> />
                                <input type='hidden' name='username' value='<?php echo escapeCharater($row['username']); ?>' />
                            </form>
                        </div>
                        <!-- <div class='role_col'>
                            <input type='submit' name='edit_<?php echo escapeCharater($row['username']); ?>' />
                        </div> -->
                    </div>
                <?php } ?>
            </section>
            <!-- <div class='board__hr'></div>
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
            </div> -->
        </main>
    </body>
</html>