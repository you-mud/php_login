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