
<?php
  //部署データを扱うクラスを定義
  class DatabaseConnect{

    // 接続するデータベースの情報
      private static $dsn = 'mysql:host=localhost;dbname=to_do_list;charset=utf8';
      private static $user = 'user01';
      private static $password = '4JTEZ9Dx4lSfzvcZ';
      protected static $dbh;
      public static $count = 0;



  //****************************************
  // データベースへの接続
  //**************************************** 

    private static function connectToDatabase() {
      // データベースへの接続開始
      try {
        self::$dbh = new PDO(self::$dsn, self::$user, self::$password);
      } catch (PDOException $e) {
        print('接続失敗:' . $e->getMessage());
        die();
      }
    }

    public static function getDbh() {
      if(!empty(self::$dbh)){
        return self::$dbh;
      }
      self::$count++;
      self::connectToDatabase();
      return self::$dbh;
    }
  }

?>
