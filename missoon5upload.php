<!DOCTYPE html>
<html lang="ja">
    <head>
    <meta charset="UFT-8">
    <tittle>mission5-1<br></tittle>
    </head>
    <body>
    <?php
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

$sql = "CREATE TABLE IF NOT EXISTS mission5  /*mission5というテーブルが無ければ作成*/
	 (
	 id INT AUTO_INCREMENT PRIMARY KEY,
	 name char(32),
	 comment TEXT,
     postdate TEXT,
     pw TEXT
	)";
    $stmt = $pdo->query($sql);
 
    
    if(isset($_POST['submit'])){   //＝nameとcomment1が押されたとき
         $name=$_POST['name'];
         $comment1=$_POST['comment1'];
         $postdate=date('Y-m-d H:i:s');
         $pw=$_POST['password'];
         $edit_num=$_POST['edit_num'];
  
         if($name!=='' && $comment1!=='' && $pw=='himitsudayo'){     //comment1=コメント
         if($edit_num==''){   //編集番号が空の時
            $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, postdate, pw) VALUES (:name, :comment, :postdate, :pw)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment1, PDO::PARAM_STR);
            $sql -> bindParam(':postdate', $postdate, PDO::PARAM_STR);
            $sql -> bindParam(':pw', $pw, PDO::PARAM_STR);
            $sql -> execute();
           }else{
            $id = $edit_num;
            $sql = 'UPDATE mission5 SET name=:name,comment=:comment, postdate=:postdate WHERE id=:id';  // @テーブル名内で指定したidの行の変更
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment1, PDO::PARAM_STR);
            $stmt -> bindParam(':postdate', $postdate, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);  // int型を指定
            $stmt->execute();
           }
           $name=""; $comment1="";}}
 
    //削除ボタンが押されたら
 if(isset($_POST['delete'])){
     $comment2=$_POST['comment2'];
     $pw=$_POST['password'];
     
     if($comment2!=='' && $pw=='himitsudayo'){
     
     $id = $comment2;
     $sql = 'DELETE FROM mission5 WHERE id=:id';  // @テーブル名内で指定したidの行の削除
     $stmt = $pdo->prepare($sql);
     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
     $stmt->execute();
 }}
 
 //編集ボタンが押されたら
 if(isset($_POST['edit'])){
      $comment3=$_POST['comment3'];
      $sql = 'SELECT * FROM mission5';  // @テーブル名からデータを戻り値として返させる
      $stmt = $pdo->query($sql);
      $results = $stmt->fetchAll();  // fetch()で戻り値から1行ずつデータを取得(All なら全部)
      foreach ($results as $row){
      if($comment3 == $row['id']){
        $edit_num = $comment3;
          $name = $row['name'];
          $comment1 = $row['comment'];
      }}
 
 
    }
  


?>
<form action="" method="post">  投稿用<br>
        <input type="text" name="name" placeholder="名前" value="<?php if(isset($name)){echo $name;}?>">
         <input type="text" name="comment1" placeholder="コメント" value="<?php if(isset($comment1)){echo $comment1;}?>">
         <input type="text" name="password" placeholder="パスワード">
         <input type="hidden" name="edit_num" value="<?php if(isset($edit_num)){echo $edit_num;}?>"> 
        <input type="submit" name="submit">
        </form>
       
    <form action=""  method="post"> 削除用<br>
        <input type="text" name="comment2"  placeholder="削除対象番号">
        <input type="text" name="password" placeholder="パスワード">
        <input type="submit" name="delete" value="削除">
        </form>
       
     <form action=""  method="post"> 編集用<br>
         <input type="text"  name="comment3" placeholder="編集対象番号">
         <input type="submit" name="edit" value="編集">
    
     </form> 
<?php
$sql = 'SELECT * FROM mission5';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
    echo $row['id'].',';
    echo $row['name'].',';
    echo $row['comment'].',';
    echo $row['postdate'].',';
    echo $row['pw'].'<br>';
echo "<hr>";
}

?>
    </body>
    </html>
