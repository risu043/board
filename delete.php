<?php
    require_once "postTable.php";

    $id = $_GET["id"];

    $post_tbl = new PostTable();
    $post_tbl->deletePost($id);

    header('Location: index.php');
    exit();
?>