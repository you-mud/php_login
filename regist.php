<?php
//データベース基本情報
$host = "mysql:dbname=test;host=localhost";
$user = "root";
$pass = "";
$name = "test";
/*
 * データベース接続
*/
try{
	$PDO = new PDO($host,$user,$pass);
	$PDO->query('set names utf8');
}catch(PDOException $e){
	print('Error:'.$e->getMessage());
	die('error');
}
$name = htmlspecialchars($_POST['name']);
$mail = htmlspecialchars($_POST['mail']);
$pass = htmlspecialchars($_POST['pass']);

//データベース登録
if($_POST['regist'] == "reg"){
	//パスワードが6文字以上か確認
	}else if(mb_strlen($pass) < 6){
		$regist_error .= "IDは6文字以上で設定してください<br />";
		//パスワードが32文字以下か確認
	}else if (mb_strlen($pass) > 32){
		$regist_error .= "IDが長すぎます。32文字以下で設定してください<br />";
		//アドレスの正規表現チェック
	}else if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $pass)) {
		$regist_error .= "正しいメールアドレスを登録してくださいませ。";
		//半角英数字のみのチェック
	}elseif (!preg_match( "/[\@-\~]/" , $pass)) {
		$regist_error .= "パスワードは半角英数字及び記号のみ入力してください。<br />";
	}
	if(empty($_POST['name']) or empty($_POST['name']) or empty($_POST['name'])){
		$error = "入力情報に誤りがあり登録ができませんでした。<br>";
	}else{
		//重複チェック
		$sth  = $PDO->prepare('SELECT COUNT(*) AS cnt FROM login WHERE mail = :mail');
		$sth->bindValue(':mail',mysql_real_escape_string($mail),PDO::PARAM_STR);
		$sth->execute();
		$check = $sth->fetch(PDO::FETCH_NUM);
		//重複だったらキック
		if ($check > 0) {
			$error['double'] = "このアドレスは既に登録されております。別のアドレスでご登録くださいませ。";
		}else{
			//データ追加
			$sth ="";
			$sth = $PDO->prepare('INSERT INTO login (name,mail,pass) VALUES (?,?,?)');
			$sth->bindValue(1,$name);
			$sth->bindValue(2,$mail);
			$sth->bindValue(3,$pass);
			$sth->execute();//完了
			header('location:after.php');
	}
	 true;
}
?>

<!DOCTYPE html>
<body>
<head>
<meta charset="UTF-8">
</head>
<center>
<form action="" method="POST">
<?php  if(isset($error['double'])){	echo $error['double'];}?><BR><BR>
name：<input type="test" name="name"><BR><BR>
mail：<input type="text" name="mail"><BR><BR>
pass：<input type="text" name="pass"><BR><BR>
<input type="hidden" name="regist" value="reg">
<input type="submit" name="sub">

</form>
</center>
</body>
</html>