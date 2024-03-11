<?php
$errors = [];
if($_POST){
    $id = null;
    $name = $_POST["name"];
    $contents = $_POST["contents"];
    // $nameと$contentsが空欄だったらerror
    if(empty($name)){
        $errors[] .= "名前を入力してください";
    }
    if(empty($contents)){
        $errors[] .= "投稿内容を入力してください";
    }
    // errorがなければクエリを実行
    if(!$errors){
        date_default_timezone_set('Asia/Tokyo');
        $created_at = date("Y-m-d H:i:s");
        // $fileに画像ファイルのパスを格納
        $image = uniqid(mt_rand(), true);//ファイル名をユニーク化
        $image .= '.' . substr(strrchr($_FILES['image']['name'], '.'), 1);//アップロードされたファイルの拡張子を取得
        $file = "images/$image";
        //DB接続情報を設定します。(MySQLの接続時に「文字セットをUTF-8に設定する」というコマンドを指定)
        $pdo = new PDO(
            "mysql:dbname=sample;host=localhost","root","",array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET `utf8`")
        );
        //ここで「DB接続NG」だった場合、接続情報に誤りがあります。
        if ($pdo) {
            echo "DB接続OK";
        } else {
            echo "DB接続NG";
        }
        //SQLを実行。prepare() メソッドはPDOオブジェクトに属しているメソッドであり、データベースのクエリを準備するために使用されます
        $regist = $pdo->prepare("INSERT INTO post(id, name, contents, created_at, image) VALUES (:id,:name,:contents,:created_at,:image)");
        $regist->bindParam(":id", $id);
        $regist->bindParam(":name", $name);
        $regist->bindParam(":contents", $contents);
        $regist->bindParam(":created_at", $created_at);
        $regist->bindValue(':image', $image, PDO::PARAM_STR);
        if (!empty($_FILES['image']['name'])) {//ファイルが選択されていれば$imageにファイル名を代入
            move_uploaded_file($_FILES['image']['tmp_name'], './images/' . $image);//imagesディレクトリにファイル保存
            if (exif_imagetype($file)) {//画像ファイルかのチェック
                $message = '画像をアップロードしました';
            } else {
                $message = '画像ファイルではありません';
            }
        }
        $regist->execute();
        // execute() メソッドを呼び出すことでSQLクエリが実行され、バインドされた変数の値がデータベースに挿入されます。
        //ここで「登録失敗」だった場合、SQL文に誤りがあります。
        if ($regist) {
            echo "登録成功";
            // 投稿が成功した場合は、リダイレクトしてフォームが再送信されないようにする
            header('Location: index.php');
            exit();
        } else {
            echo "登録失敗";
        }
    }
}
?>

<?php
//DB接続情報を設定します。
$pdo = new PDO(
    "mysql:dbname=sample;host=localhost","root","",array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET `utf8`")
);
//ここで「DB接続NG」だった場合、接続情報に誤りがあります。
if ($pdo) {
    echo "DB接続OK";
} else {
    echo "DB接続NG";
}
//SQLを実行。
$regist = $pdo->prepare("SELECT * FROM post ORDER BY created_at DESC LIMIT 10");
$regist->execute();
//ここで「登録失敗」だった場合、SQL文に誤りがあります。
if ($regist) {
    echo "登録成功";
} else {
    echo "登録失敗";
}
?>

<!DOCTYPE html>
<meta charset="UTF-8">
<title>りすさんの掲示板</title>
<h1>りすさんの掲示板</h1>
<section>
    <h2>新規投稿</h2>
    <div id="error"><?php foreach($errors as $error){
        echo $error."<br>";
    } ?></div>
    <form action="index.php" method="post" enctype="multipart/form-data">
        名前 : <input type="text" name="name" value=""><br>
        投稿内容: <input type="text" name="contents" value=""><br>
        添付画像: <input type="file" name="image"><br>
        <button type="submit">投稿</button>
    </form>
</section>

<section>
	<h2>投稿内容一覧</h2>
		<?php foreach($regist as $loop): ?>
			<div>No：<?php echo $loop['id'] ?></div>
			<div>名前：<?php echo $loop['name'] ?></div>
			<div>投稿内容：<?php echo $loop['contents'] ?></div>
            <div>
            <?php if (!empty($loop['image'])): ?>
                <img src="images/<?php echo $loop['image']; ?>" width="350" height="350">
            <?php endif; ?>
            </div>
            <div>投稿時間：<?php echo $loop['created_at'] ?></div>
            <div><a href="delete.php?id=<?php echo $loop['id'] ?>">削除</a></div>
			<div>------------------------------------------</div>
		<?php endforeach; ?>
	
</section>