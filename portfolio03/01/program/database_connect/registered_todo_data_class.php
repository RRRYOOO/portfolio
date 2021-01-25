<?php
  // phpファイルの読み込み
  require_once('database_connect.php');

  // 登録されたToDoを取り出すクラスを定義
  class RegisteredTodoData {
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
  
    // 渡されたToDoIDに紐づくユーザIDを返す
    public static function getTodoUserID($todoID) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT user_id FROM todo WHERE id = :todoID;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':todoID', $todoID);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $userID = $row['user_id'];
      // ユーザIDを返す
      return $userID;
    }

    // 渡されたToDoIDに紐づく登録日を返す
    public static function getTodoRegistrationDate($todoID) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT registration_date FROM todo WHERE id = :todoID;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':todoID', $todoID);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $registrationDate = $row['registration_date'];
      // 登録日を返す
      return $registrationDate;
    }

    // 渡されたToDoIDに紐づくタイトルを返す
    public static function getTodoTitle($todoID) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT todo_title FROM todo WHERE id = :todoID;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':todoID', $todoID);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $todoTitle = $row['todo_title'];
      // タイトルを返す
      return $todoTitle;
    }

    // 渡されたToDoIDに紐づく内容を返す
    public static function getTodoContent($todoID) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT todo_content FROM todo WHERE id = :todoID;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':todoID', $todoID);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $todoContent = $row['todo_content'];
      // 内容を返す
      return $todoContent;
    } 

    // 渡されたToDoIDに紐づく期限を返す
    public static function getTodoDeadline($todoID) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT todo_deadline FROM todo WHERE id = :todoID;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':todoID', $todoID);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $todoDeadline = $row['todo_deadline'];
      // 期限を返す
      return $todoDeadline;
    } 

    // 渡されたToDoIDに紐づく難易度を返す
    public static function getTodoDifficulty($todoID) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT todo_difficulty FROM todo WHERE id = :todoID;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':todoID', $todoID);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $todoDifficulty = $row['todo_difficulty'];
      // 難易度を返す
      return $todoDifficulty;
    }

    // 渡されたToDoIDに紐づく重要度を返す
    public static function getTodoImportance($todoID) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT todo_importance FROM todo WHERE id = :todoID;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':todoID', $todoID);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $todoImportance = $row['todo_importance'];
      // 重要度を返す
      return $todoImportance;
    }

    // 渡されたToDoIDに紐づくステータスを返す
    public static function getTodoStatus($todoID) {
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT todo_status FROM todo WHERE id = :todoID;';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':todoID', $todoID);
      $sth->execute();
      $row = $sth->fetch(PDO::FETCH_ASSOC);
      $todoStatus = $row['todo_status'];
      // ステータスを返す
      return $todoStatus;
    } 

  }


?>