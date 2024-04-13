<?php
    require_once "postTable.php";
    session_start();

    if(isset($_POST['name']) && isset($_POST['contents'])) {
        $id = null; 
        $name = htmlspecialchars($_POST["name"], ENT_QUOTES, "UTF-8");
        $contents = htmlspecialchars($_POST["contents"], ENT_QUOTES, "UTF-8");
        $image = null;
    
        $post_tbl = new PostTable();
        $post_tbl->insertPost($id, $name, $contents, $image);

        if (empty($name)) {
            $_SESSION['error_message_name']="名前を入力してください";
        } else {
            unset($_SESSION['error_message_name']);
        }

        if (empty($contents)) {
            $_SESSION['error_message_contents']="投稿内容を入力してください";
        } else {
            unset($_SESSION['error_message_contents']);
        }

        // board.phpにリダイレクトさせる
        header('Location: index.php');
        exit();
    }
?>