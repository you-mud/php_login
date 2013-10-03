<?php
/*
 * ﾃﾞｰﾀﾍﾞｰｽ登録・ﾛｸﾞｲﾝ・ﾛｸﾞｱｳﾄｸﾗｽ
 * 機能//DB接続、入力確認、ﾃﾞｰﾀﾍﾞｰｽ登録
 * @you-mud
 *
 * 備考：ﾃﾞｰﾀﾍﾞｰｽのｱﾄﾞﾚｽｶﾗﾑはﾕﾆｰｸに設定しておく必要がある
 */
//ﾃﾞｰﾀﾍﾞｰｽ基本情報,configに記載
define('DB_HOST','mysql:dbname=test;host=localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','test');
define('ERROR_NAME','お名前をご記入ください。');
define('ERROR_MAIL','ｱﾄﾞﾚｽをご記入ください');
define('ERROR_PASS','ﾊﾟｽﾜｰﾄﾞをご記入ください。');
define('ERROR_MSG','ﾊﾟｽﾜｰﾄﾞは6文字以上です');
define('ERROR_MSG1','ﾊﾟｽﾜｰﾄﾞは32文字以下となります。');
define('ERROR_MSG2','ｱﾄﾞﾚｽを半角英数字でご登録ください');
define('ERROR_MSG3','ﾊﾟｽﾜｰﾄﾞは半角英数字でご登録ください');
define('ERROR_MSG4','既にこのｱﾄﾞﾚｽは登録されております');
define('ERROR_MSG5','不正なｱｸｾｽになります');

//クラス開始
class  auth{
	const HOST = DB_HOST;
	const USER = DB_USER;
	const PASS = DB_PASS;

	public $name;
	public $mail;
	public $pass;
	public $hidden;
	public $login_mail;
	public $login_pass;
	public $login_hidden;

	private $PDO = null;

	public function __construct(){
		session_start();
		//簡易データベース接続
		//PDO
		try{
			$this->PDO = new PDO(DB_HOST,DB_USER,DB_PASS);
			$this->PDO->query('set names utf8');
		}catch(PDOException $e){
			print('Error:'.$e->getMessage());
			die('error');
		}
	}
/*
 * 新規登録関数
 * 入力文字列確認
 * 名前、ﾒｰﾙ、ﾊﾟｽﾜｰﾄﾞを登録
 */

	public function registar($name,$mail,$pass,$hidden){
		$this->name   = htmlspecialchars($name);
		$this->mail   = htmlspecialchars($mail);
		$this->pass   = htmlspecialchars($pass);
		$this->hidden = htmlspecialchars($hidden);
		$this->check($this->name,$this->mail,$this->pass,$this->hidden);
	}

	//文字列チェック
	public function check($name,$mail,$pass,$hidden){
		if($this->hidden === 'hidden'){
			if(empty($this->name)){
				//nameがnullだったら
				$this->error_msg  = ERROR_NONE_NAME;
				return $this->error_msg;
			}else if(empty($this->mail)){
				//mailがnullだったら
				$this->error_msg .= ERROR_NONE_MAIL;
				return $this->error_msg;
			}else if(empty($this->pass)){
				//ﾊﾟｽﾜｰﾄﾞがnullだったら
				$this->error_msg .= ERROR_NONE_PASS;
				return $this->error_msg;
			}else if (mb_strlen($this->pass) < 6) {
				//ﾊﾟｽﾜｰﾄﾞが6文字以下だったら
				$this->error_msg .= ERROR_MSG;
				return $this->error_msg;
			}else if (mb_strlen($this->pass) > 32){
				//ﾊﾟｽﾜｰﾄﾞが32文字以下か確認
				$this->error_msg .= ERROR_MSG1;
				return $this->error_msg;
			}else if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $this->pass)) {
				//ｱﾄﾞﾚｽの正規表現ﾁｪｯｸ
				$this->error_msg .= ERROR_MSG2;
				return $this->error_msg;
			}else if (!preg_match( "/[\@-\~]/" , $this->pass)) {
				//ﾊﾟｽﾜｰﾄﾞの半角英数ﾁｪｯｸ
				$this->error_msg .= ERROR_MSG3;
				return $this->error_msg;
			}
			//ﾁｪｯｸ後登録関数へ渡す
			$this->duplication_check($this->name,$this->mail,$this->pass,$this->hidden);
		}else{
			$this->error_msg = ERROR_MSG5;
			return $this->error_msg;
		}
	}
	public function duplication_check(){
		//ﾒｰﾙｱﾄﾞﾚｽ重複ﾁｪｯｸ
		$sth  = $PDO->prepare('SELECT COUNT(*) AS cnt FROM login WHERE mail = :mail');
		$sth->bindValue(':mail',mysql_real_escape_string($this->mail),PDO::PARAM_STR);
		$sth->execute();
		$check = $sth->fetch(PDO::FETCH_NUM);
		if ($check > 0) {
			$this->error_msg = ERROR_MSG4;
			return $this->error_msg;
		}else{
			//ﾃﾞｰﾀ登録
			$sth ="";
			$sth = $PDO->prepare('INSERT INTO login (name,mail,pass) VALUES (?,?,?)');
			$sth->bindValue(1,mysqli_real_escape_string($this->name));
			$sth->bindValue(2,mysqli_real_escape_string($this->mail));
			$sth->bindValue(3,mysqli_real_escape_string($this->pass));
			$sth->execute();//完了
			header('location:after.php');
		}
	}
/*
 * ﾛｸﾞｲﾝ機能
 */
	public function login($mailad,$password,$hidden){
		$this->login_mail = htmlspecialchars($mailad);
		$this->login_pass = htmlspecialchars($password);
		if ($this->hidden == "code_one") {
			//form情報が渡ってきたら
			$sth = $this->PDO->prepare('select * from login where mail = :mail and pass = :pass');
			$sth->bindValue(':mail',mysql_real_escape_string($this->login_mail),PDO::PARAM_STR);
			$sth->bindValue(':pass',mysql_real_escape_string($this->login_pass),PDO::PARAM_STR);
			$sth->execute();
			$row = $sth->fetch(PDO::FETCH_ASSOC);
			//ﾛｸﾞｲﾝﾃﾞｰﾀがあったら以下の処理
			if($row['mail'] === $req_mail && $row['pass'] === $req_pass){
				$_SESSION['id']   = $row['id'];
				$_SESSION['time'] = time();
				$_SESSION['name'] = $row['name'];
				header('location:./after.php');
			}
	}
}
















