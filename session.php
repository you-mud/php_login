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
ログインページです。










?>
