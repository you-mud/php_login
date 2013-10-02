<?php
//------------------------
//アドレス確認
//------------------------
function is_mail($text) {
	if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $text)) {
		return TRUE;
	} else {
		return FALSE;
	}
}
//------------------------
//半角英数字か確認
//------------------------
function is_alnum($text) {
	if (preg_match("/^[a-zA-Z0-9]+$/",$text)) {
		return TRUE;
	} else {
		return FALSE;
	}
}
//------------------------
//6文字以上か確認
//------------------------
function is_almu($text){
	if (mb_strlen($text) > 5){
		true;
	}else{
		false;
	}
}
//------------------------
//
//------------------------






