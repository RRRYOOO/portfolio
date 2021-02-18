<?php
  // phpファイルの読み込み
  require_once('../database_connect/database_connect.php');

  // 管理者情報を登録する
  class ManagerRegistration {
    // 管理者情報に関する変数
    private $managerID;
    private $managerPassword;
    private $managerPasswordHashed;
    private $registrationDate;
    // 接続するデータベースの情報
    private $dbh;
    
  //****************************************
  // コンストラクタ
  //****************************************

    public function __construct($managerID, $managerPassword){
      // 登録画面で入力されたデータを取得
      $this->managerID = $managerID;
      $this->managerPassword = $managerPassword;
    }    

  //****************************************
  // データベースへの接続
  //**************************************** 

    private function connectToDatabase() {
    // データベースへの接続開始
    try {
      $this->dbh = DatabaseConnect::getDbh();
    } catch (PDOException $e) {
      print('接続失敗:' . $e->getMessage());
      die();
    }
    return;
  }
      
  //****************************************
  // 管理者登録
  //**************************************** 

    // パスワードを暗号化する
    private function passwordHashing() {
      // パスワードを暗号化
      $this->managerPasswordHashed = password_hash($this->managerPassword, PASSWORD_DEFAULT);
      return ;
    }

    // 管理者登録を実行する
    public function managerRegistrationExecute() {
      // データベースに接続
      $this->connectToDatabase();
      // パスワードを暗号化
      $this->passwordHashing();
      // 登録日をセット（日本時間）
      date_default_timezone_set('Asia/Tokyo');
      $this->registrationDate = date('Y-m-d H:i:s');
      // 管理者登録を実行
      $sql = 'INSERT INTO manager (manager_id, manager_password, registration_date) 
      VALUES (:manager_id, :manager_password, :registration_date);';
      $sth = $this->dbh->prepare($sql);
      $sth->bindParam(':manager_id', $this->managerID, PDO::PARAM_STR);
      $sth->bindParam(':manager_password', $this->managerPasswordHashed, PDO::PARAM_STR);
      $sth->bindParam(':registration_date', $this->registrationDate, PDO::PARAM_STR);
      $sth->execute();
      unset($this->dbh);
      return;
    }
  
  }
?>
