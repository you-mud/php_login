<?php
session_start();

?>
<html>
<body><center><br>
<form action="./session.php" method="POST">
<span color="red"><?php echo $msg;?></span><BR>
アドレース<input type="text" name="mail"><br><br>
パスワード<input type="text" name="pass"><br><br>
<input type="hidden" name="code" value="one_code">
<input type="submit" value="送信" name="in">
</form>
</center>








</body>
</html>