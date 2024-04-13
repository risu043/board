<?php
    require_once "postTable.php";
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
                <div class="input-area"><label for="name">名前</label> <input type="text" id="name" name="name" value=""></div>
                <div class="input-area"><label for="contents">投稿内容</label> <textarea type="text" id="contents" name="contents" value=""></textarea></div>
                <div class="input-area"><label for="image">添付画像</label> <input type="file" id="image" name="image"></div>
                <button class="button" type="submit">投稿</button>
            </form>
        </section>
        

        <section class="wrapper">
            <h2 class="sub-title">投稿検索</h2>
            <form action="search.php" method="post">
                <div class="input-area"><input type="text" name="word" value=""></div>
                <button class="button" type="submit">検索</button>
            </form>
        </section>
        

        <section class="wrapper">
            <h2 class="sub-title">検索結果一覧</h2>
<div class="post-container">
    
<?php
                                $search = null;
                                if(isset($_POST['word']) && !empty($_POST['word'])) {
                                        $word = htmlspecialchars($_POST["word"], ENT_QUOTES, "UTF-8");
                                        $post_tbl = new PostTable();
                                        $search = $post_tbl->searchPosts($word);
                                        foreach($search as $loop):
                                            
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
    
                                <?php
                                    endforeach;
                                }
                                
                                if(isset($_POST['word']) && empty($_POST['word'])) {
                                    echo '<p>検索ワードを入力してください</p>';
                                }
                                if(isset($_POST['word']) && !empty($_POST['word']) && $search==null) {
                                    echo '<p>検索ワードを含む投稿がありません</p>';
                                }
                                ?>
    
</div>
        </section>
    </main>
    <footer><p class="copy">りすさんの掲示板&copy;2024</p></footer>
</body>
</html>