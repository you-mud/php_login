<?php
session_start();

if (isset($_POST['in'])){
	if(empty($_POST['mail']) && empty($_POST['pass'])){
		echo "メールアドレスとパスワードをご記入ください。";
	}else if(empty($_POST['mail'])){
		echo "メールアドレスをご記入ください。";
	}else if($_POST['pass'] == ""){
		echo "パスワードをご記入ください。";
	}else{
		true;
	}
}

$host = "mysql:dbname=test;host=localhost";
$user = "root";
$pass = "";
$name = "test";
define('ERROR_MSG','正しいページからログインしてください。');
define('ERROR_MSG_ONE','ユーザー情報が確認できません');
define('ERROR_MSG_TWO','ログイン情報が間違っております。もう一度初めからログインしてください。');

$req_mail  = htmlspecialchars($_REQUEST['mail'],ENT_QUOTES);
$req_pass  = htmlspecialchars($_REQUEST['pass'],ENT_QUOTES);

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
//ログイン処理
if ($_POST['code'] === 'one_code') {
	//form情報が渡ってきたら

	$sth = $PDO->prepare('select * from login where mail = :mail and pass = :pass');
	$sth->bindValue(':mail',$req_mail,PDO::PARAM_STR);
	$sth->bindValue(':pass',$req_pass,PDO::PARAM_STR);
	$sth->execute();
	$row = $sth->fetch(PDO::FETCH_ASSOC);
	//ログイン処理
	if($row['mail'] === $req_mail && $row['pass'] === $req_pass){
		$_SESSION['id']   = $row['id'];
		$_SESSION['time'] = time();
		$_SESSION['name'] = $row['name'];
		header('location:http://localhost/girlscoupon/top');
	}else{
		$msg = "ログイン情報が違います";
		header('location:./index.php');
	}
}else{
	die('不正なアクセスです');
}











?>
