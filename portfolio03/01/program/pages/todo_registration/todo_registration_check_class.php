<?php
  // phpファイルの読み込み
  require_once('../database_connect/database_connect.php');

  // 入力されたtodo登録情報のチェックを行うクラスを定義
  class ToDoRegistrationCheck {
    // 接続するデータベースの情報
    private static $dbh;
    // エラーメッセージに関する変数
    private static $errorMessageTodoUserID;
    private static $errorMessageTodoTitle;
    private static $errorMessageTodoContent;
    private static $errorMessageTodoDeadline;
    private static $errorMessageTodoDifficulty;
    private static $errorMessageTodoImportance;
    private static $errorMessageTodoStatus;
    // 日付に適合可能なパターン
    private static $adaptableDatePattern ='/\A[1-9][0-9]{3}\-(0[1-9]|1[0-2])\-(0[1-9]|[1-2][0-9]|3[0-1])\z/';
    

  //****************************************
  // データベースへの接続
  //**************************************** 

    private static function connectToDatabase() {
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
  // 入力情報の確認
  //**************************************** 

    // Todo登録情報が正しく入力されているか確認
    public static function inputDataCheck($userID, $todoTitle, $todoContent, $todoDeadline, $todoDifficulty, $todoImportance, $todoStatus) {
      // エラーフラグの初期化
      $errorFlag = 0;
      // ユーザIDの確認
      if(!empty(self::userIDCheck($userID))) {
        $errorFlag = 1;
      }
      // タイトルの確認
      if(!empty(self::todoTitleCheck($todoTitle))) {
        $errorFlag = 1;
      }
      // 内容の確認
      if(!empty(self::todoContentCheck($todoContent))) {
        $errorFlag = 1;
      }
      // 期日の確認
      if(!empty(self::todoDeadlineCheck($todoDeadline))) {
        $errorFlag = 1;
      }
      // 難易度の確認
      if(!empty(self::todoDifficultyCheck($todoDifficulty))) {
        $errorFlag = 1;
      }
      // 重要度の確認
      if(!empty(self::todoImportanceCheck($todoImportance))) {
        $errorFlag = 1;
      }
      // ステータスの確認
      if(!empty(self::todoStatusCheck($todoStatus))) {
        $errorFlag = 1;
      }
      // エラーフラグを返す
      return $errorFlag;
    }

    // ユーザIDが正しく設定されているか確認
    public static function userIDCheck($userID) {
      // エラーメッセージの初期化
      self::$errorMessageTodoUserID = null;
      // ユーザIDの設定が空でないか確認
      if(empty($userID)) {
        self::$errorMessageTodoUserID = 'ユーザIDが設定されていません。';
        // ユーザIDがデータベースに存在しているか確認
      } else {
        // データベースに接続
        self::connectToDatabase();
        // 入力されたユーザIDと同じユーザIDをデータベースから取得
        $sql = 'SELECT id FROM users WHERE id = :id;';
        $sth = self::$dbh->prepare($sql);
        $sth->bindparam(':id', $userID);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        // 取得したユーザIDが空の場合
        if (empty($row['id'])) {
          self::$errorMessageTodoUserID  = 'ユーザIDが存在しません。';
        }
      }
      // エラーメッセージを返す
      return self::$errorMessageTodoUserID;
    }

    // タイトルが正しく入力されているか確認
    public static function todoTitleCheck($todoTitle) {
      // エラーメッセージの初期化
      self::$errorMessageTodoTitle = null;
      // タイトルの入力が空でないか確認
      if(empty($todoTitle)) {
        self::$errorMessageTodoTitle = 'タイトルが入力されていません。';
      // タイトルの入力が30文字より長くないか確認
      } else if(mb_strlen($todoTitle, 'UTF-8') > 30) {
        self::$errorMessageTodoTitle = 'タイトルは30文字以下にしてください。';
      }
      // エラーメッセージを返す
      return self::$errorMessageTodoTitle;
    }

    // 内容が正しく入力されているか確認
    public static function todoContentCheck($todoContent) {
      // エラーメッセージの初期化
      self::$errorMessageTodoContent = null;
      // 内容の入力が空でないか確認
      if(empty($todoContent)) {
        self::$errorMessageTodoContent = '内容が入力されていません。';
      // 内容の入力が200文字より長くないか確認
      } else if(mb_strlen($todoContent, 'UTF-8') > 200) {
        self::$errorMessageTodoContent = '内容は200文字以下にしてください。';
      }
      // エラーメッセージを返す
      return self::$errorMessageTodoContent;
    }

    // 期限が正しく入力されているか確認
    public static function todoDeadlineCheck($todoDeadline) {
      // エラーメッセージの初期化
      self::$errorMessageTodoDeadline = null;
      // 期限の入力が空でないか確認
      if(empty($todoDeadline)) {
        self::$errorMessageTodoDeadline = '期限が選択されていません。';
      // 期限の形式が正しいか確認
      } else if(!preg_match(self::$adaptableDatePattern, $todoDeadline)){
        self::$errorMessageTodoDeadline = '期限の形式が正しくありません。';
      }
        // エラーメッセージを返す
        return self::$errorMessageTodoDeadline;
    }

    // 難易度が正しく入力されているか確認
    public static function todoDifficultyCheck($todoDifficulty) {
      // エラーメッセージの初期化
      self::$errorMessageTodoDifficulty = null;
      // 難易度の選択が空でないか確認
      if(empty($todoDifficulty)) {
        self::$errorMessageTodoDifficulty = '難易度が選択されていません。';
      // 難易度の選択が有効かどうか確認
      } else if(!($todoDifficulty == 1 || $todoDifficulty == 2 || $todoDifficulty == 3)) {
        self::$errorMessageTodoDifficulty = '難易度の選択が有効ではありません。';
      }
      // エラーメッセージを返す
      return self::$errorMessageTodoDifficulty;
    }      

    // 重要度が正しく入力されているか確認
    public static function todoImportanceCheck($todoImportance) {
      // エラーメッセージの初期化
      self::$errorMessageTodoImportance = null;
      // 重要度の選択が空でないか確認
      if(empty($todoImportance)) {
        self::$errorMessageTodoImportance = '重要度が選択されていません。';
      // 重要度の選択が有効かどうか確認
      } else if(!($todoImportance == 1 || $todoImportance == 2 || $todoImportance == 3)) {
        self::$errorMessageTodoImportance = '重要度の選択が有効ではありません。';
      }
      // エラーメッセージを返す
      return self::$errorMessageTodoImportance;
    }

    // ステータスが正しく入力されているか確認
    public static function todoStatusCheck($todoStatus) {
      // エラーメッセージの初期化
      self::$errorMessageTodoStatus = null;
      // ステータスの選択が空でないか確認
      if(empty($todoStatus)) {
        self::$errorMessageTodoStatus = 'ステータスが選択されていません。';
      // ステータスの選択が有効かどうか確認
      } else if(!($todoStatus == 1 || $todoStatus == 2 || $todoStatus == 3 || $todoStatus == 4)) {
        self::$errorMessageTodoImportance = 'ステータスの選択が有効ではありません。';
      }
      // エラーメッセージを返す
      return self::$errorMessageTodoStatus;
    }
    
  }
?>
