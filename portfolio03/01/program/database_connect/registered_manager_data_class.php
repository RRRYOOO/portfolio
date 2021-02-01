<?php
  // phpファイルの読み込み
  require_once('database_connect.php');

  // 登録された管理者情報を取り出すクラスを定義
  class RegisteredManagerData {
    // 接続するデータベースの情報
    private static $dbh;

    //****************************************
    // データベースへの接続
    //**************************************** 

    private static function connectToDatabase() {
      // $dbhを既に作成済みの場合
      if(!empty(self::$dbh)){
        return;
      }
      // $dbhを新規作成の場合
      // データベースへの接続開始
      try {
        self::$dbh = DatabaseConnect::GetDbh();
      } catch (PDOException $e) {
        print('接続失敗:' . $e->getMessage());
        die();
      }
      return;
    }
  
    // 渡された管理者IDで登録されている管理者の管理者IDを返す
    public static function getManagerID($managerID) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT manager_id FROM manager WHERE manager_id = :manager_id;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':manager_id', $managerID);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $managerID = $row['manager_id'];
      // 管理者IDを返す
      return $managerID;
    }

    // 渡された管理者IDで登録されている管理者のパスワード(暗号化)を返す
    public static function getManagerPasswordHashed($managerID) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT manager_password FROM manager WHERE manager_id = :manager_id;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':manager_id', $managerID);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $managerPasswordHashed = $row['manager_password'];
      // パスワード(暗号化)を返す
      return $managerPasswordHashed;
    }

    // 渡された管理者IDで登録されている管理者の登録日を返す
    public static function getRegistrationDate($managerID) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT registration_date FROM manager WHERE manager_id = :manager_id;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':manager_id', $managerID);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $registrationDate = $row['registration_date'];
      // 登録日を返す
      return $registrationDate;
    }

  }


?>