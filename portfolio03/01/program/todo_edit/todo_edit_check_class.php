<?php
  // phpファイルの読み込み
  require_once('../database_connect/database_connect.php');
  require_once('../database_connect/registered_todo_data_class.php');
  require_once('../todo_registration/todo_registration_check_class.php');

  // 入力されたtodo編集情報のチェックを行うクラスを定義
  class ToDoEditCheck {
    // 接続するデータベースの情報
    private $dbh;
    // todo編集情報に関する変数
    private $editTodoID;
    private $editTodoUserID;
    private $editTodoRegistrationDate;
    private $editTodoTitle;
    private $editTodoContent;
    private $editTodoDeadline;
    private $editTodoDifficulty;
    private $editTodoImportance;
    private $editTodoStatus;
    // エラーメッセージに関する変数
    private $errorMessageEditTodoUserID;
    private $errorMessageEditTodoTitle;
    private $errorMessageEditTodoContent;
    private $errorMessageEditTodoDeadline;
    private $errorMessageEditTodoDifficulty;
    private $errorMessageEditTodoImportance;
    private $errorMessageEditTodoStatus;

  //****************************************
  // コンストラクタ
  //****************************************

  public function __construct($editTodoID, $editTodoUserID) {
    // 渡されたToDoIDで登録されているToDoの情報をToDo編集情報として設定する
    $this->editTodoID = $editTodoID;
    $this->editTodoUserID = $editTodoUserID;
    $this->editTodoRegistrationDate = RegisteredTodoData::getTodoRegistrationDate($editTodoID, $editTodoUserID);
    $this->editTodoTitle = RegisteredTodoData::getTodoTitle($editTodoID, $editTodoUserID);
    $this->editTodoContent = RegisteredTodoData::getTodoContent($editTodoID, $editTodoUserID);
    $this->editTodoDeadline = RegisteredTodoData::getTodoDeadline($editTodoID, $editTodoUserID);
    $this->editTodoDifficulty = RegisteredTodoData::getTodoDifficulty($editTodoID, $editTodoUserID);
    $this->editTodoImportance = RegisteredTodoData::getTodoImportance($editTodoID, $editTodoUserID);
    $this->editTodoStatus = RegisteredTodoData::getTodoStatus($editTodoID, $editTodoUserID);
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
  // ToDo編集情報を返す
  //****************************************

    // 編集するToDoのTodoIDを返す
    public function getEditTodoID() {
      return $this->editTodoID;
    }

    // 編集するToDoのユーザIDを返す
    public function getEditTodoUserID() {
      return $this->editTodoUserID;
    }

    // 編集するToDoの登録日を返す
    public function getEditTodoRegistrationDate() {
      return $this->editTodoRegistrationDate;
    }

    // 編集するToDoのタイトルを返す
    public function getEditTodoTitle() {
      return $this->editTodoTitle;
    }

    // 編集するToDoの内容を返す
    public function getEditTodoContent() {
      return $this->editTodoContent;
    }

    // 編集するToDoの期限を返す
    public function getEditTodoDeadline() {
      return $this->editTodoDeadline;
    }

    // 編集するToDoの難易度を返す("低"or"中"or"高"で返す)
    public function getEditTodoDifficulty() {
      switch ($this->editTodoDifficulty) {
        case 1:
          return '低';
        case 2:
          return '中';
        case 3:
          return '高';
        default:
          return;
      }
    }

    // 編集するToDoの重要度を返す("低"or"中"or"高"で返す)
    public function getEditTodoImportance() {
      switch ($this->editTodoImportance) {
        case 1:
          return '低';
        case 2:
          return '中';
        case 3:
          return '高';
        default:
          return;
      }
    }

    // 編集するToDoのステータスを返す
    public function getEditTodoStatus() {
      switch ($this->editTodoStatus) {
        case 1:
          return "未着手";
        case 2:
          return "進行中";
        case 3:
          return "完了";
        case 4:
          return "キャンセル";       
        default:
          return;
      }
    }    


  //****************************************
  // ToDo編集情報を変更する
  //****************************************

    // ToDoのタイトルを更新する
    private function setEditTodoTitle($editTodoTitle) {
      $this->editTodoTitle = $editTodoTitle;
      return;
    }

    // ToDoの内容を更新する
    private function setEditTodoContent($editTodoContent) {
      $this->editTodoContent = $editTodoContent;
      return;
    }

    // ToDoの期限を更新する
    private function setEditTodoDeadline($editTodoDeadline) {
      $this->editTodoDeadline = $editTodoDeadline;
      return;
    }

    // ToDoの難易度を更新する
    private function setEditTodoDifficulty($editTodoDifficulty) {
      $this->editTodoDifficulty = $editTodoDifficulty;
      return;
    }

    // ToDoの重要度を更新する
    private function setEditTodoImportance($editTodoImportance) {
      $this->editTodoImportance = $editTodoImportance;
      return;
    }

    // ToDoのステータスを更新する
    private function setEditTodoStatus($editTodoStatus) {
      $this->editTodoStatus = $editTodoStatus;
      return;
    }

 
  //****************************************
  // 入力情報の確認
  //**************************************** 

    // Todo登録情報が正しく入力されているか確認
    public function inputDataCheck($editUserID, $editTodoTitle, $editTodoContent, $editTodoDeadline, $editTodoDifficulty, $editTodoImportance, $editTodoStatus) {
      // エラーフラグの初期化
      $errorFlag = 0;
      // ユーザIDの確認
      $errorMessageEditTodoUserID = ToDoRegistrationCheck::userIDCheck($editUserID);
      if(!empty($errorMessageEditTodoUserID)) {
        $errorFlag = 1;
      }
      // タイトルの確認
      $errorMessageEditTodoTitle = ToDoRegistrationCheck::todoTitleCheck($editTodoTitle);
      if(!empty($errorMessageEditTodoTitle)) {
        $errorFlag = 1;
      }
      // 内容の確認
      $errorMessageEditTodoContent = ToDoRegistrationCheck::todoContentCheck($editTodoContent);
      if(!empty($errorMessageEditTodoContent)) {
        $errorFlag = 1;
      }
      // 期日の確認
      $errorMessageEditTodoDeadline = ToDoRegistrationCheck::todoDeadlineCheck($editTodoDeadline);
      if(!empty($errorMessageEditTodoDeadline)) {
        $errorFlag = 1;
      }
      // 難易度の確認
      $errorMessageEditTodoDifficulty = ToDoRegistrationCheck::todoDifficultyCheck($editTodoDifficulty);
      if(!empty($errorMessageEditTodoDifficulty)) {
        $errorFlag = 1;
      }
      // 重要度の確認
      $errorMessageEditTodoImportance = ToDoRegistrationCheck::todoImportanceCheck($editTodoImportance);
      if(!empty($errorMessageEditTodoImportance)) {
        $errorFlag = 1;
      }
      // ステータスの確認
      $errorMessageEditTodoStatus = ToDoRegistrationCheck::todoStatusCheck($editTodoStatus);
      if(!empty($errorMessageEditTodoStatus)) {
        $errorFlag = 1;
      }
      // エラーフラグを返す
      return $errorFlag;
    }
    
  }
?>
