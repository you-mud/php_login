<?php
require_once './auth.php';
$suc = new auth();
$suc->login($_POST['mail'],$_POST['pass'],$_POST['hidden'],$_POST['behind']);

?>
<!DOCTYPE html>
<head>
<meta charset="UTF-8">
</head>
<body>
<center>
ログインテスト<br>
<?php if(isset($suc->success_msg)){echo $suc->success_msg;}else {true;} ?><br>
<?php if(isset($suc->error_msg)){echo $suc->error_msg;}else {true;} ?><br>
<?php if(isset($_SESSION['name'])){echo "ようこそ！！".$_SESSION['name']."さんお待ちしておりました！！";}?>
<form action="" method="POST"><BR>
アドレス：<input type="text" name="mail" value=""><BR><BR>
パスワード<input type="password" name="pass"><BR><BR>
ﾒｰﾙｱﾄﾞﾚｽを保存する<input type ="checkbox" name="behind" value="cook"><BR><BR>
<input type="hidden" name="hidden" value="hidden">
<input type="submit" name="sub" value="送信"><BR><BR>
</form>
</center>
</body>
</html>
