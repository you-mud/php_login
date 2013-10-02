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
	if($_POST['name'] ==""){
		$error['name'] = "お名前をご記入くださいませ。<BR>";
	}else if($_POST['mail'] ==""){
		$error['name'] = "アドレスをご記入くださいませ。<BR>";
	}else if($_POST['pass'] ==""){
		$error['pass'] = "パスワードをご記入くださいませ。<BR>";
	}else{
	    true;
	}
	$sth = $PDO->prepare('INSERT INTO login (name,mail,pass) VALUES (?,?,?)');
	$sth->bindValue(1,$name);
	$sth->bindValue(2,$mail);
	$sth->bindValue(3,$pass);
	$sth->execute();
}
?>

<html>
<body>
<center>
<form action="" method="POST">
<?php if(isset($error)){ ?>
<?php foreach($error as $ms){echo $ms;}?>
<?php }?>
name：<input type="test" name="name"><BR><BR>
mail：<input type="text" name="mail"><BR><BR>
pass：<input type="text" name="pass"><BR><BR>
<input type="hidden" name="regist" value="reg">
<input type="submit" name="sub">
</form>
</center>
</body>
</html>