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
if($_POST['regist'] == 'reg'){
	if(empty($_POST['name']) or empty($_POST['name']) or empty($_POST['name'])){
		$error = "入力情報に誤りがあり登録ができませんでした。<br>";
	}
	//データ追加
	$sth = $PDO->prepare('INSERT INTO login (name,mail,pass) VALUES (?,?,?)');
	$sth->bindValue(1,$name);
	$sth->bindValue(2,$mail);
	$sth->bindValue(3,$pass);
	$sth->execute();//完了
	header('location:after.php');
}
?>

<html>
<body>
<center>
<form action="" method="POST">
<?php echo $error?>
name：<input type="test" name="name"><BR><BR>
mail：<input type="text" name="mail"><BR><BR>
pass：<input type="text" name="pass"><BR><BR>
<input type="hidden" name="regist" value="reg">
<input type="submit" name="sub">

</form>
</center>
</body>
</html>