<?php
    require_once "postTable.php";

    $id = null;
    $name = htmlspecialchars($_POST["name"], ENT_QUOTES, "UTF-8");
    $contents = htmlspecialchars($_POST["contents"], ENT_QUOTES, "UTF-8");


    $post_tbl = new PostTable();
    $post_tbl->insertPost($id, $name, $contents, $image);

    // board.phpにリダイレクトさせる
    header('Location: index.php');
    exit();
?>