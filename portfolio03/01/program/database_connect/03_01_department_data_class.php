
<?php
  // phpファイルの読み込み
  require_once('03_01_database_connect.php');

  // 部署情報を扱うクラスを定義
  class DepartmentData {
    // 接続するデータベースの情報
    private static $dbh;


  //****************************************
  // データベースへの接続
  //**************************************** 

    private static function connectToDatabase() {
      // $dbhを既に作成済みの場合
      if(!is_null(self::$dbh)){
        return;
      }
      // $dbhを新規作成の場合
      // データベースへの接続開始
      try {
        self::$dbh = DatabaseConnect::getDbh();
      } catch (PDOException $e) {
        print('接続失敗:' . $e->getMessage());
        die();
      }
      return;
    }

  //****************************************
  // 部署IDと部署名の変換
  //**************************************** 

    // 部署IDを渡すとその部署名を返す
    public static function exchangeIDToDepartment($departmentID) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT department FROM department WHERE id = :id;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':id', $departmentID);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $department = $row['department'];
      // 部署名を返す
      return $department;
    }

    // 部署名を渡すとその部署IDを返す
    public static function exchangeDepartmentToID($department) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT id FROM department WHERE department = :department;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':department', $department);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $departmentID = $row['id'];
      // 部署IDを返す
      return $departmentID;
    }


  //****************************************
  // 部署IDの最大値と最小値の取得
  //****************************************     

    // 部署IDの最大値を返す
    public static function getMaxDepartmentID() {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT MAX(id) FROM department;';
      $sth = self::$dbh->query($sql);
      $maxDepartmentID = $sth->fetch(PDO::FETCH_ASSOC);
      // 部署IDの最大値を返す
      return $maxDepartmentID;
    }    

    // 部署IDの最小値を返す
    public static function getMinDepartmentID() {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT MIN(id) FROM department;';
      $sth = self::$dbh->query($sql);
      $minDepartmentID = $sth->fetch(PDO::FETCH_ASSOC);
      // 部署IDの最小値を返す
      return $minDepartmentID;
    }    
    
  }

?>
