<?php
//---------------------------
//パーミッションはconfig
//test用のログイン機能
//
//---------------------------
session_start();

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
if ($_POST['code'] == "code_one") {
	//form情報が渡ってきたら
    //ログイン
	$sth = $PDO->prepare('select * from login where mail = :mail and pass = :pass');
	$sth->bindValue(':mail',mysql_real_escape_string($req_mail),PDO::PARAM_STR);
	$sth->bindValue(':pass',mysql_real_escape_string($req_pass),PDO::PARAM_STR);
	$sth->execute();
	$row = $sth->fetch(PDO::FETCH_ASSOC);
	//ログイン処理
	if($row['mail'] === $req_mail && $row['pass'] === $req_pass){
		$_SESSION['id']   = $row['id'];
		$_SESSION['time'] = time();
		$_SESSION['name'] = $row['name'];
		header('location:./after.php');
	}else{
		if (isset($_POST['in'])){
			if(empty($_POST['mail']) && empty($_POST['pass'])){
				$error = "メールアドレスとパスワードをご登録ください。";
			}else if(empty($_POST['mail'])){
				$error = "メールアドレスをご記入くださいませ。";
			}else if($_POST['pass'] == ""){
				$error = "パスワードをご記入ください。";
			}else{
				$error = "ログイン情報が違います";;
			}
		}
	}
}else{
	true;
}
?>

<html>
<body>
<center><br>
<form action="" method="POST">
<?php echo $error."<BR>";?>
　アドレス<input type="text" name="mail"><br><br>
パスワード<input type="password" name="pass"><br><br>
<input type="hidden" name="code" value="code_one">
<input type="submit" value="送信" name="in">
</form>
</center>
</body>
</html>