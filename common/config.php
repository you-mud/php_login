<?php
//ﾃﾞｰﾀﾍﾞｰｽ基本情報,configに記載
define('DB_HOST','mysql:dbname=test;host=localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','test');
define('ERROR_NONE_NAME','お名前をご記入ください。');
define('ERROR_BIG_NAME','25文字以下でご登録ください');
define('ERROR_SMALL_NAME','お名前の文字列が少なすぎます');
define('ERROR_NONE_MAIL','ｱﾄﾞﾚｽをご記入ください');
define('ERROR_NONE_PASS','ﾊﾟｽﾜｰﾄﾞをご記入ください。');
define('ERROR_SMALL_PASS','ﾊﾟｽﾜｰﾄﾞは6文字以上です');
define('ERROR_BIG_PASS','ﾊﾟｽﾜｰﾄﾞは32文字以下となります。');
define('ERROR_MSG2','ｱﾄﾞﾚｽを半角英数字でご登録ください');
define('ERROR_CHECK_PASS','ﾊﾟｽﾜｰﾄﾞは半角英数字でご登録ください');
define('ERROR_MSG4','既にこのｱﾄﾞﾚｽは登録されております');
define('ERROR_NOT_ACCESS','不正なｱｸｾｽになります');
define('ERROR_CHECK_AD','ｱﾄﾞﾚｽをご確認くださいませ');
define('SUCCESS_MSG','ﾃﾞｰﾀﾍﾞｰｽ登録が完了いたしました。');
define('SUCCESS_LOG','ﾛｸﾞｲﾝに成功いたしました。');
define('ERROR_LOG','ﾛｸﾞｲﾝｴﾗｰです。ｱﾄﾞﾚｽとﾊﾟｽﾜｰﾄﾞをご確認くださいませ。');


/*
 * ﾒｰﾙ用定数
 */
define('MAIL_FROM','ｻｲﾄ管理者');
define('MAIL_TITLE','パスワードの再発行手続き');
define('SUCCESS_TRANS','ﾒｰﾙ送信に成功しました。');
define('ERROR_NONE_REGIST_MAIL','お探しのﾒｰﾙｱﾄﾞﾚｽが見つかりませんでした');
define('ERROR_NOT_TRANS','ﾒｰﾙ送信に失敗しました。');
define('SITE_ROOT','ｻｲﾄinfo');
define('FROM_ADRESS','●●●@●●.jp');
//pass変更用のﾘﾝｸ
define('PASS_LINK','http://www.google.jp');