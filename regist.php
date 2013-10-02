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

?>

<html>
<body>
<center>
<form action="" method="POST">
name：<input type="test" name="name"><BR><BR>
mail：<input type="text" name="mail"><BR><BR>
pass：<input type="text" name="pass"><BR><BR>
<input type="hidden" name="regist" value="reg">
<input type="submit" name="sub">
</form>
</center>
</body>
</html>