<?php
    require_once "postTable.php";
    session_start();
    // リロードしたらセッションデータを消す
    if(isset($_GET['reload'])){
    unset($_SESSION['error_message_name']);
    unset($_SESSION['error_message_contents']);
    }
    // エラーメッセージの初期化
    $error_message_name = "";
    $error_message_contents = "";
    // 三項演算子 error_message_nameがあればセット、なければ空の文字列をセット
    $error_message_name = isset($_SESSION['error_message_name']) ? $_SESSION['error_message_name'] : "";
    $error_message_contents = isset($_SESSION['error_message_contents']) ? $_SESSION['error_message_contents'] : "";
?>

<html lang="ja">
    <head>
    <meta charset="utf-8" />
    <title>りすさんの掲示板</title>
    <meta name="description" content="掲示板" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="robots" content="noindex,nofollow,noarchive" />
    <link rel="icon" type="image/png" href="images/favicon.png" />
    <!-- CSS -->
    <link
      rel="stylesheet"
      href="https://unpkg.com/destyle.css@1.0.5/destyle.css?1.2"
    />
    <link rel="stylesheet" href="css/style.css" />
    <!-- JS -->
    <script src="js/script.js" defer></script>
    <!-- fontawesome -->
    <script
      src="https://kit.fontawesome.com/1d29d80e5a.js"
      crossorigin="anonymous"
    ></script>
    </head>

<body>
    <header class="header">
        <h1 class="title"><a href="./">りすさんの掲示板</a></h1>
    </header>
    <main>
        <section class="wrapper">
            <h2 class="sub-title">新規投稿</h2>
            
            <form action="send.php" method="post" enctype="multipart/form-data">
                <P class="error">
                    <?php
                    // エラーメッセージがある場合にのみ表示
                    if (!empty($error_message_name)) {
                        echo $error_message_name;
                    } ?>
                </P>
                <div class="input-area"><label for="name">名前</label> <input type="text" id="name" name="name" value=""></div>
                <P class="error">
                    <?php
                    // エラーメッセージがある場合にのみ表示
                    if (!empty($error_message_contents)) {
                        echo $error_message_contents;
                    } ?>
                </P>
                <div class="input-area"><label for="contents">投稿内容</label> <textarea type="text" id="contents" name="contents" value=""></textarea></div>
                <div class="input-area"><label for="image">添付画像</label> <input type="file" id="image" name="image"></div>
                <button class="button" type="submit" name="submit">投稿</button>
            </form>
            <form action="index.php" method="get" id="reload">
                <input type="hidden" name="reload" value="true">
            </form>
        </section>
        

        <section class="wrapper">
            <h2 class="sub-title">投稿検索</h2>
            <form action="search.php" method="post">
                <div class="input-area"><input type="text" name="word" value=""></div>
                <button class="button" type="submit"S>検索</button>
            </form>
        </section>
        

        <section class="wrapper">
            <h2 class="sub-title">投稿内容一覧</h2>
<div class="post-container">
    
                                <?php
                                $post_tbl = new PostTable();
                                $regist = $post_tbl->selectPosts();
                                foreach($regist as $loop):
                                ?>
                                <div class="post-content">
                                    <div><span class="bold">No：</span><?php echo $loop['id'] ?></div>
                                    <div><span class="bold">名前：</span><?php echo $loop['name'] ?></div>
                                    <div><span class="bold">投稿内容：</span><?php echo $loop['contents'] ?></div>
                                    <div>
                                    <?php if (isset($loop['image']) && !empty($loop['image'])): ?>
                                        <img src="images/<?php echo $loop['image']; ?>" width="350" height="350">
                                    <?php endif; ?>
                                    </div>
                                    <div class="text-right"><span class="margin-right"><a href="delete.php?id=<?php echo $loop['id'] ?>"><i class="fas fa-eraser"></i>削除</a></span>投稿時間：<?php echo $loop['created_at'] ?></div>
                                </div>
    
                    <?php endforeach; ?>
    
</div>
        </section>
    </main>
    <footer><p class="copy">りすさんの掲示板&copy;2024</p></footer>
</body>
</html>