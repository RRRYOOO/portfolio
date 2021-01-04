<?php
  // phpファイルの読み込み
  require_once('03_01_database_connect.php');
  require_once('03_01_department_data_class.php');

  // 登録されたユーザ情報を取り出すクラスを定義
  class RegisteredUserData {
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
  
    // 渡されたメールアドレスで登録されているユーザのユーザIDを返す
    public static function getUserID($mailAddress) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT id FROM users WHERE user_mailaddress = :user_mailaddress;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':user_mailaddress', $mailAddress);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $userID = $row['id'];
      // ユーザIDを返す
      return $userID;
    }

    // 渡されたメールアドレスで登録されているユーザの登録日を返す
    public static function getRegistrationDate($mailAddress) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT registration_date FROM users WHERE user_mailaddress = :user_mailaddress;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':user_mailaddress', $mailAddress);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $registrationDate = $row['registration_date'];
      // 登録日を返す
      return $registrationDate;
    }

    // 渡されたメールアドレスで登録されているユーザの姓を返す
    public static function getUserLastName($mailAddress) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT user_lastname FROM users WHERE user_mailaddress = :user_mailaddress;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':user_mailaddress', $mailAddress);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $userLastName = $row['user_lastname'];
      // 姓を返す
      return $userLastName;
    }

    // 渡されたメールアドレスで登録されているユーザの名を返す
    public static function getUserFirstName($mailAddress) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT user_firstname FROM users WHERE user_mailaddress = :user_mailaddress;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':user_mailaddress', $mailAddress);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $userFirstName = $row['user_firstname'];
      // 名を返す
      return $userFirstName;
    }

    // 渡されたメールアドレスで登録されているユーザのメールアドレスを返す
    public static function getUserMailAddress($mailAddress) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT user_mailaddress FROM users WHERE user_mailaddress = :user_mailaddress;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':user_mailaddress', $mailAddress);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $userMailAddress = $row['user_mailaddress'];
      // メールアドレスを返す
      return $userMailAddress;
    }

    // 渡されたメールアドレスで登録されているユーザのパスワード(暗号化)を返す
    public static function getUserPasswordHashed($mailAddress) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT user_password FROM users WHERE user_mailaddress = :user_mailaddress;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':user_mailaddress', $mailAddress);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $userPasswordHashed = $row['user_password'];
      //パスワード(暗号化)を返す
      return $userPasswordHashed;
    }

    // 渡されたメールアドレスで登録されているユーザの性別("man"or"woman")を返す
    public static function getUserGender($mailAddress) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT user_gender FROM users WHERE user_mailaddress = :user_mailaddress;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':user_mailaddress', $mailAddress);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $userGender = $row['user_gender'];
      // 性別を返す
      return $userGender;
    }

    // 渡されたメールアドレスで登録されているユーザの性別("男"or"女")を返す
    public static function getUserGenderJapanese($mailAddress) {
      // 渡されたメールアドレスで登録されているユーザの性別("man"or"woman")を受け取る
      $userGender = self::getUserGender($mailAddress);
      if($userGender == "man") {
        $userGenderJapanese = "男";
      } else if($userGender == "woman") {
        $userGenderJapanese = "女";
      }
      // 日本語で性別を返す
      return $userGenderJapanese;
    }

    // 渡されたメールアドレスで登録されているユーザの年齢を返す
    public static function getUserAge($mailAddress) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT user_age FROM users WHERE user_mailaddress = :user_mailaddress;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':user_mailaddress', $mailAddress);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $userAge = $row['user_age'];
      // 年齢を返す
      return $userAge;
    }
    
    // 渡されたメールアドレスで登録されているユーザの部署IDを返す
    public static function getUserDepartmentID($mailAddress) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT user_department FROM users WHERE user_mailaddress = :user_mailaddress;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':user_mailaddress', $mailAddress);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $userDepartmentID = $row['user_department'];
      // 部署IDを返す
      return $userDepartmentID;
    }

    // 渡されたメールアドレスで登録されているユーザの部署名を返す
    public static function getUserDepartment($mailAddress) {
      // 渡されたメールアドレスで登録されているユーザの部署IDを受け取る
      $userDepartmentID = self::getUserDepartmentID($mailAddress);
      // 部署IDを部署名に変換する
      $userDepartment = DepartmentData::exchangeIDToDepartment($userDepartmentID);
      // 部署名を返す
      return $userDepartment;
    }

  }


?>