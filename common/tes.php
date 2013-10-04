<?php
require_once '../auth.php';

$regist = new auth();
if(isset($_POST['name'])){
$regist->registar($_POST['name'],$_POST['mail'],$_POST['pass'],$_POST['hidden']);
}



?>
<!-- login 用 -->
<html>
<head>
<body>
<center>
登録用<br><br>
<?php if($regist->error_msg){echo $regist->error_msg;}?>
<?php if($regist->success_msg){echo $regist->success_msg;}?>
<form action="" method="POST"><BR>
おなまえ：<input type="text" name="name"><BR><BR>
アドレス：<input type="text" name="mail"><BR><BR>
パスワード<input type="password" name="pass"><BR><BR>
<input type="hidden" name="hidden" value="hidden">
<input type="submit" name="sub" value="送信"><BR><BR>
</form>
</center>



</body>

</html>