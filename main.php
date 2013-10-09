<?php


require_once '/common/config.php';
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
	public $error_msg;
	public $success_msg;
	public $cookie;
	public $user_name;
	private $PDO = null;
	//処理開始
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
		$this->name   = htmlspecialchars($name,ENT_QUOTES);
		$this->mail   = htmlspecialchars($mail,ENT_QUOTES);
		$this->pass   = htmlspecialchars($pass,ENT_QUOTES);
		$this->hidden = htmlspecialchars($hidden,ENT_QUOTES);
		$this->duplication_check($name,$mail,$pass,$hidden);
	}
	public function name_check($name){
		if(empty($name)){
			$this->error_msg  = ERROR_NONE_NAME;
			return $this->error_msg;
		}else if(mb_strlen($name) >32){
			$this->error_msg  = ERROR_BIG_NAME;
			return $this->error_msg;
		}else if(mb_strlen($name) <6){
			$this->error_msg  = ERROR_SMALL_NAME;
			return $this->error_msg;
		}else{
			true;
		}
	}
	public function mail_check($mail){
		if(empty($mail)){
			$this->error_msg = ERROR_NONE_MAIL;
			return $this->error_msg;
		}else if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",$mail)){
			//ｱﾄﾞﾚｽの正規表現ﾁｪｯｸ
			$this->error_msg .= ERROR_CHECK_AD;
			return $this->error_msg;
		}else{
			return;
		}
	}
	public function pass_check($pass){
		if(empty($pass)){
			$this->error_msg .= ERROR_NONE_PASS;
			return $this->error_msg;
		}else if(mb_strlen($pass) < 6){
			//ﾊﾟｽﾜｰﾄﾞが6文字以下だったら
			$this->error_msg .= ERROR_SMALL_PASS;
			return $this->error_msg;
		}else if(mb_strlen($pass) > 32){
			//ﾊﾟｽﾜｰﾄﾞが32文字以下か確認
			$this->error_msg .= ERROR_BIG_PASS;
			return $this->error_msg;
		}else if(!preg_match("/^[a-zA-Z0-9]+$/",$pass)){
			//ｱﾄﾞﾚｽの半角英数ﾁｪｯｸ
			$this->error_msg .= ERROR_CHECK_PASS;
			return $this->error_msg;
		}else{
			return;
		}
	}
	public function pass_hash($pass){
		$pass = hash('sha256',$pass);
		return $pass;
	}
	/*
	 * 重複ﾁｪｯｸ
	 * データ登録
	 */
	public function duplication_check($name,$mail,$pass,$hidden){
		$this->name_check($name);
		$this->mail_check($mail);
		$this->pass_check($pass);
		$this->pass_hash($pass);
			//ﾒｰﾙｱﾄﾞﾚｽ重複ﾁｪｯｸ
		$sth  = $this->PDO->prepare('SELECT COUNT(*)  FROM login WHERE mail = :mail');
		$sth->bindValue(':mail',$mail,PDO::PARAM_STR);
		$sth->execute();
		$check = $sth->fetch(PDO::FETCH_NUM);
		if($check['0'] > 0){
			$this->error_msg = ERROR_MSG4;
			return $this->error_msg;
		}else{
			//ﾃﾞｰﾀ登録
			$sth ="";
			$sth = $this->PDO->prepare('INSERT INTO login (name,mail,pass) VALUES (?,?,?)');
			$sth->bindValue(1,$name);
			$sth->bindValue(2,$maill);
			$sth->bindValue(3,$pass);
			$sth->execute();//完了
			$this->success_msg = SUCCESS_MSG;
			return $this->success_msg;
			//registar終了
		}
	}
	/*
	 * ﾛｸﾞｲﾝ
	 */
	public function login($mail,$pass,$hidden){
		//ｴｽｹｰﾌﾟ処理
		$this->login_mail = htmlspecialchars($mailad);
		$this->login_pass = htmlspecialchars($password);
		$this->login_hidd = htmlspecialchars($hidden);
		//ﾊｯｼｭ処理
		$this->login_pass = $this->pass_hash($this->login_pass);

	}
}

































