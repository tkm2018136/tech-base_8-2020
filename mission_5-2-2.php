<!doctype html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>mission_5-1</title>
    </head>
    <body>
        <?php
           // DB接続設定
                $dsn = 'mysql:dbname=****;host;localhost';
                $user = '****';
	            $password = '****';
	            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	            //テーブル作成
	            $sql = "CREATE TABLE IF NOT EXISTS tb_mission501"
	            ." ("
	            . "id INT AUTO_INCREMENT PRIMARY KEY,"
	            . "name char(32),"
	            . "comment TEXT,"
	            . "date datetime,"
	            . "password char(32)"
	            .");";
	            $stmt = $pdo->query($sql);
	            
        //投稿フォーム
            if(isset($_POST["name"]) && isset($_POST["comment"])){
                if(!($_POST["name"])=="" && !($_POST["comment"])==""){
                    if(empty($_POST["edit_numcopy"])){
                        $name = $_POST["name"];
                        $comment = $_POST["comment"];
                        $date = date("Y/m/d H:i:s");
                        $password_T = $_POST["pass_toukou"];
                        if($password_T=="****"){
	                    
                            $sql=$pdo->prepare("INSERT INTO tb_mission501(name,comment,date,password) 
                            VALUES(:name,:comment,:date,:password)");
                            $sql->bindParam(':name',$name,PDO::PARAM_STR);
                            $sql->bindParam(':comment',$comment,PDO::PARAM_STR);
                            $sql->bindParam(':date',$date,PDO::PARAM_STR);
                            $sql->bindParam(':password',$password_T,PDO::PARAM_STR);
                            $sql->execute();
                        } else{
                            echo "誤ったパスワードです";
                        }
                    }
                } else {
                    echo "名前とコメントを入力してください"; 
                }
            }
            //確認完了
            //削除フォーム
                if(isset($_POST["deletenumber"])){
                    if(!($_POST["deletenumber"])==""){
                        $deletenum=$_POST["deletenumber"];
                        $password2=$_POST["pass_sakujo"];
                        if($password2=="****"){
                            $id = $deletenum;
	                        $sql = 'delete from tb_mission501 where id=:id';
	                        $stmt = $pdo->prepare($sql);
	                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	                        $stmt->execute();
                        } else{
                            echo "誤ったパスワードです";
                        }
                    } else{
                        echo "削除番号を入力してください";
                    }    
                }
            //確認完了
            //編集フォーム
                if(isset($_POST["edit_num"])){
                    if(!($_POST["edit_num"])==""){
                        $edit_num=$_POST["edit_num"];
                        $password3=$_POST["pass_henshu"];
                        if($password3=="****"){
                            $id = $edit_num;
                            $sql = 'SELECT * FROM tb_mission501 WHERE id=:id ';
                            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                            $stmt->execute();
                            $result = $stmt->fetchAll(); 
                            foreach ($result as $row){
                            }
                            $edit_name=$row["name"];
                            $edit_comment=$row["comment"];
                            $edit_NUM=$row["id"];
                    } else{
                        echo "誤ったパスワードです";
                    }
                } else{
                    echo "編集番号を入力してください";
                }
            }
            //確認完了
            //編集実行機能
                if(isset($_POST["edit_numcopy"]) && !($_POST["edit_numcopy"])==""){
                    $id = $_POST["edit_numcopy"]; //変更する投稿番号
                	$name = $_POST["name"];
                	$comment = $_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
                	$date = date("Y/m/d H:i:s");
                    $password_T = $_POST["pass_toukou"];
                    if(!empty($name) && !empty($comment)){
                        if($password_T=="****"){ 
                        	$sql = 'UPDATE tb_mission501 SET name=:name,comment=:comment,date=:date,password=:password WHERE id=:id';
                        	$stmt = $pdo->prepare($sql);
                        	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
                        	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                        	$stmt->bindParam(':date', $date, PDO::PARAM_STR);
                        	$stmt->bindParam(':password', $password_T, PDO::PARAM_STR);
                        	$stmt->execute();
                        } else{
                            echo "誤ったパスワードです";
                        }
                    }
                }
        ?>
        
        <form action ="" method ="post">
            <input type = "text" name="name" placeholder="名前" value="<?php 
            if(!empty($edit_name)){ echo $edit_name;} ?>"><br>
            <input type = "text" name="comment" placeholder="コメント" value="<?php
            if(!empty($edit_comment)){ echo $edit_comment;} ?>"><br>
            <input type = "hidden" name="edit_numcopy" value="<?php
            if(!empty($edit_NUM)){ echo $edit_NUM;} ?>">
            <input type = "password" name="pass_toukou" placeholder="パスワード">
            <input type = "submit" name="submit">
            
        </form>
        <form action ="" method ="post">
            <input type = "number" name="deletenumber" placeholder="削除対象番号"><br>
            <input type = "password" name="pass_sakujo" placeholder="パスワード">
            <input type = "submit" name="submit" value="削除">
        </form>
        <form action ="" method ="post">
            <input type = "number" name="edit_num" placeholder="編集対象番号"><br>
            <input type = "password" name="pass_henshu" placeholder="パスワード">
            <input type = "submit" name="submit" value="編集">
        </form><!--確認完了-->
    
    </body>
    
</html>
            <?php
            
	        //テーブル内表示
	        $sql = 'SELECT * FROM tb_mission501';
	        $stmt = $pdo->query($sql);
	        $result = $stmt->fetchAll();
	        foreach ($result as $row){
		    //$rowの中にはテーブルのカラム名が入る
		        echo $row['id'].',';
		        echo $row['name'].',';
		        echo $row['comment'].',';
		        echo $row['date'].'<br>';
		        //echo $row['password'].'<br>';
	            echo "<hr>";
	        }
	
            ?>