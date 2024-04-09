<?php
    class PostTable {
        const DSN = 'mysql:dbname=sumple;host=localhost';
        const USER = 'root';
        const PASSWORD = '';

        
        // データベースに接続
        private function connectDB() {
            $pdo = new PDO(self::DSN, self::USER, self::PASSWORD);
            return $pdo;
            if ($pdo) {
                echo "DB接続OK";
            } else {
                echo "DB接続NG";
            }
        }

        // データベースを閉じる
        private function closeDB($pdo) {
            $pdo = null;
        }

        // 投稿されたポストのうち最新の10件を取得
        public function selectPosts() {
            //データベースに接続
            $pdo = $this->connectDB();

            //SQLを準備
            $regist = $pdo->prepare("SELECT * FROM post ORDER BY created_at DESC LIMIT 10");
            //クエリを実行
            $regist->execute();

            //データベースを閉じる
            $this->closeDB($pdo);
            // 取得したポストを返す
            return $regist;
        }

        // 検索ワードを含むポストを取得
        public function searchPosts($word) {
            //データベースに接続
            $pdo = $this->connectDB();
            //SQLを準備
            $search = $pdo->prepare("SELECT * FROM post WHERE contents LIKE ?");
            // バインドパラメータの設定
            $search->bindValue(1, "%$word%", PDO::PARAM_STR);
            //クエリを実行
            $search->execute();
            //データベースを閉じる
            $this->closeDB($pdo);
            
            // 結果を取得して返す
            return $search->fetchAll(PDO::FETCH_ASSOC);
        }

        // ポストを投稿する
        public function insertPost($id, $name, $contents, $image) {
                $name = $_POST["name"];
                $contents = $_POST["contents"];
                
                // 空欄がなければクエリを実行
                if (!empty($name) && !empty($contents)) {
                    //投稿時間を日本の時間で取得
                    date_default_timezone_set('Asia/Tokyo');
                    $created_at = date("Y-m-d H:i:s");
                    // 画像ファイルがアップロードされた場合
                    if (!empty($_FILES['image']['name'])) {
                        // 画像ファイルに名前をつける
                        $image = uniqid(mt_rand(), true);//ファイル名をユニーク化
                        $image .= '.' . substr(strrchr($_FILES['image']['name'], '.'), 1);//アップロードされたファイルの拡張子を取得
                        // imageフォルダに画像ファイルを保存
                        move_uploaded_file($_FILES['image']['tmp_name'], './images/' . $image);
                        
                    } else {
                        // 画像ファイルがアップロードされなかった場合
                        $image = null;
                    }
                    //データベース接続
                    $pdo = $this->connectDB();
                    //SQL準備。prepare() メソッドはPDOオブジェクトに属しているメソッドであり、データベースのクエリを準備するために使用される
                    $regist = $pdo->prepare("INSERT INTO post(id, name, contents, created_at, image) VALUES (:id,:name,:contents,:created_at,:image)");
                    $regist->bindParam(":id", $id);
                    $regist->bindParam(":name", $name);
                    $regist->bindParam(":contents", $contents);
                    $regist->bindParam(":created_at", $created_at);
                    $regist->bindValue(':image', $image, PDO::PARAM_STR);
                    // execute() メソッドを呼び出すことでSQLクエリが実行され、バインドされた変数の値がデータベースに挿入される
                    $regist->execute();
                    //データベースを閉じる
                    $this->closeDB($pdo);
                    // 投稿内容を返す
                    return $regist;
                } 
        }

        public function deletePost($id) {

            $pdo = $this->connectDB();

            $delete_query = $pdo->prepare("DELETE FROM post WHERE id = :id");
            $delete_query->bindParam(":id", $id);
            $delete_query->execute();

            $this->closeDB($pdo);
        }

    }