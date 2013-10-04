<?php
/*
 * ﾃﾞｰﾀﾍﾞｰｽ登録・ﾛｸﾞｲﾝ・ﾛｸﾞｱｳﾄｸﾗｽ
 * 機能//DB接続、入力確認、ﾃﾞｰﾀﾍﾞｰｽ登録
 * 備考：ﾃﾞｰﾀﾍﾞｰｽのｱﾄﾞﾚｽｶﾗﾑはﾕﾆｰｸに設定しておく必要がある
 * ﾊﾟｽﾜｰﾄﾞはsha256でﾊｯｼｭ処理
 */
require_once './config.php';
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
		$this->hidden = htmlspecialchars($hidden);
		$this->check($this->name,$this->mail,$this->pass,$this->hidden);
	}
	//文字列チェック
	public function check($name,$mail,$pass,$hidden){
		if($this->hidden === 'hidden'){
			if(empty($this->name)){
				//nameがnullだったら
				$this->error_msg  = ERROR_NAME;
				return $this->error_msg;
			}else if(empty($this->mail)){
				//mailがnullだったら
				$this->error_msg .= ERROR_MAIL;
				return $this->error_msg;
			}else if(empty($this->pass)){
				//ﾊﾟｽﾜｰﾄﾞがnullだったら
				$this->error_msg .= ERROR_PASS;
				return $this->error_msg;
			}else if (mb_strlen($this->pass) < 6) {
				//ﾊﾟｽﾜｰﾄﾞが6文字以下だったら
				$this->error_msg .= ERROR_MSG;
				return $this->error_msg;
			}else if (mb_strlen($this->pass) > 32){
				//ﾊﾟｽﾜｰﾄﾞが32文字以下か確認
				$this->error_msg .= ERROR_MSG1;
				return $this->error_msg;
			}else if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $this->mail)) {
				//ｱﾄﾞﾚｽの正規表現ﾁｪｯｸ
				$this->error_msg .= ERROR_MAG6;
				return $this->error_msg;
			}else if (!preg_match("/^[a-zA-Z0-9]+$/", $this->pass)) {
				//ｱﾄﾞﾚｽの半角英数ﾁｪｯｸ
				$this->error_msg .= ERROR_MSG3;
				return $this->error_msg;
			}
			//確認後ﾊﾟｽﾜｰﾄﾞをﾊｯｼｭ処理
			$this->pass = hash("sha256",$this->pass);
			//ﾁｪｯｸ後登録関数へ渡す
			$this->duplication_check($this->name,$this->mail,$this->pass,$this->hidden);
		}else{
			$this->error_msg = ERROR_MSG5;
			return $this->error_msg;
		}
	}
	/*
	 * ﾃﾞｰﾀﾍﾞｰｽ登録
	 * 二重ﾁｪｯｸ後登録
	 * 任意のﾃｰﾌﾞﾙに変更する必要がある
	 */
	public function duplication_check($name,$mail,$pass,$hidden){
		//ﾒｰﾙｱﾄﾞﾚｽ重複ﾁｪｯｸ
		$sth  = $this->PDO->prepare('SELECT COUNT(*)  FROM login WHERE mail = :mail');
		$sth->bindValue(':mail',$this->mail,PDO::PARAM_STR);
		$sth->execute();
		$check = $sth->fetch(PDO::FETCH_NUM);
		if($check['0'] > 0){
			$this->error_msg = ERROR_MSG4;
			return $this->error_msg;
		}else{
			//ﾃﾞｰﾀ登録
			$sth ="";
			$sth = $this->PDO->prepare('INSERT INTO login (name,mail,pass) VALUES (?,?,?)');
			$sth->bindValue(1,$this->name);
			$sth->bindValue(2,$this->mail);
			$sth->bindValue(3,$this->pass);
			$sth->execute();//完了
			$this->success_msg = SUCCESS_MSG;
			return $this->success_msg;
			//registar終了
		}
	}
	/*
	 * ﾛｸﾞｲﾝ機能
	*/
	public function login($mailad,$password,$hidden){
		//初期化処理
		$this->login_mail = "";
		$this->login_pass = "";
		//ｴｽｹｰﾌﾟ処理
		$this->login_mail = htmlspecialchars($mailad);
		$this->login_pass = htmlspecialchars($password);
		$this->login_hidd = htmlspecialchars($hidden);
		//ﾊﾟｽﾜｰﾄﾞのﾊｯｼｭ処理、sha256
		$this->login_pass = hash("sha256",$this->login_pass);
		var_dump($this->login_pass);
		if ($this->login_hidd == "hidden") {
			//form情報が渡ってきたらﾃﾞｰﾀﾍﾞｰｽ確認
			$sth = $this->PDO->prepare('select * from login where mail = :mail and pass = :pass');
			$sth->bindValue(':mail',$this->login_mail,PDO::PARAM_STR);
			$sth->bindValue(':pass',$this->login_pass,PDO::PARAM_STR);
			$sth->execute();
			$row = $sth->fetch(PDO::FETCH_ASSOC);
			//ﾛｸﾞｲﾝﾃﾞｰﾀがあったら以下の処理
			if($row['mail'] === $this->login_mail && $row['pass'] === $this->login_pass){
				$_SESSION['id']   = $row['id'];
				$_SESSION['time'] = time();
				$_SESSION['name'] = $row['name'];
				$this->success_msg = SUCCESS_LOG;
				//header('location:./after.php');
				//rinda-rinda@rinda.com
			}else{
				$this->error_msg = "";
				$this->error_msg = ERROR_LOG;
				return $this->error_msg;
			}
		}
	}
}












