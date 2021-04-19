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
                <a class='board__btn' href='register.php'>註冊</a>
            </div>
            <h1 class='board__title'>登入</h1>
            <?php
                if(!empty($_GET['errorCode'])){
                    $code = $_GET['errorCode'];
                    $msg = "Error";
                    if($code == '1'){
                        $msg = "資料不齊全";
                    }
                    elseif($code == '2'){
                        $msg = "帳號或密碼輸入錯誤";
                    }
                    echo "<h2 class='error'>".$msg."</h2>";
                }
            ?>
            <form class='board__comment-form' method='POST' action='handle_login.php'>
                <div class='board__nickname'>
                    <span>帳號:</span>
                    <input type='text' name='username'/>
                </div>
                <div class='board__nickname'>
                    <span>密碼:</span>
                    <input type='password' name='password'/>
                </div>
                <input class='board__submit-btn' type='submit' />
            </form>
            <section>
            </section>
        </main>
    </body>
</html>