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
    public $errorMessageEditTodoTitle;
    public $errorMessageEditTodoContent;
    public $errorMessageEditTodoDeadline;
    public $errorMessageEditTodoDifficulty;
    public $errorMessageEditTodoImportance;
    public $errorMessageEditTodoStatus;
    public $errorMessageEdit;
    public $errorMessageNoChange;
    // todo編集に使用する変数
    private $todoEditExecuteCompFlag;
    // todo削除に使用する変数
    private $todoDeleteExecuteCompFlag;

  //****************************************
  // コンストラクタ
  //****************************************

  public function __construct($editTodoID, $editTodoUserID) {
    // 渡されたToDoIDで登録されているToDoの情報をToDo編集情報として設定する
    $this->editTodoID = $editTodoID;
    $this->editTodoUserID = $editTodoUserID;
    $this->editTodoRegistrationDate = RegisteredTodoData::getTodoRegistrationDate($editTodoID);
    $this->editTodoTitle = RegisteredTodoData::getTodoTitle($editTodoID);
    $this->editTodoContent = RegisteredTodoData::getTodoContent($editTodoID);
    $this->editTodoDeadline = RegisteredTodoData::getTodoDeadline($editTodoID);
    $this->editTodoDifficulty = RegisteredTodoData::getTodoDifficulty($editTodoID);
    $this->editTodoImportance = RegisteredTodoData::getTodoImportance($editTodoID);
    $this->editTodoStatus = RegisteredTodoData::getTodoStatus($editTodoID);
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

    // Todo情報が正しく入力されているか確認
    public function inputDataCheck($editTodoTitle, $editTodoContent, $editTodoDeadline, $editTodoDifficulty, $editTodoImportance, $editTodoStatus) {
      // エラーフラグの初期化
      $errorFlag = 0;
      // エラーメッセージの初期化
      $this->unsetErrorMessages();
      // タイトルの確認
      $this->errorMessageEditTodoTitle = ToDoRegistrationCheck::todoTitleCheck($editTodoTitle);
      // エラーがあれば、エラーフラグを立てる
      if(!empty($this->errorMessageEditTodoTitle)) {
        $errorFlag = 1;
      // エラーがなければ、ToDo編集情報を書き換える
      } else {
        $this->setEditTodoTitle($editTodoTitle);
      }
      // 内容の確認
      $this->errorMessageEditTodoContent = ToDoRegistrationCheck::todoContentCheck($editTodoContent);
      // エラーがあれば、エラーフラグを立てる
      if(!empty($this->errorMessageEditTodoContent)) {
        $errorFlag = 1;
      // エラーがなければ、ToDo編集情報を書き換える
      } else {
        $this->setEditTodoContent($editTodoContent);
      }      
      // 期日の確認
      $this->errorMessageEditTodoDeadline = ToDoRegistrationCheck::todoDeadlineCheck($editTodoDeadline);
      // エラーがあれば、エラーフラグを立てる
      if(!empty($this->errorMessageEditTodoDeadline)) {
        $errorFlag = 1;
      // エラーがなければ、ToDo編集情報を書き換える
      } else {
        $this->setEditTodoDeadline($editTodoDeadline);
      }
      // 難易度の確認
      $this->errorMessageEditTodoDifficulty = ToDoRegistrationCheck::todoDifficultyCheck($editTodoDifficulty);
      // エラーがあれば、エラーフラグを立てる
      if(!empty($this->errorMessageEditTodoDifficulty)) {
        $errorFlag = 1;
      // エラーがなければ、ToDo編集情報を書き換える
      } else {
        $this->setEditTodoDifficulty($editTodoDifficulty);
      }
      // 重要度の確認
      $this->errorMessageEditTodoImportance = ToDoRegistrationCheck::todoImportanceCheck($editTodoImportance);
      // エラーがあれば、エラーフラグを立てる
      if(!empty($this->errorMessageEditTodoImportance)) {
        $errorFlag = 1;
      // エラーがなければ、ToDo編集情報を書き換える
      } else {
        $this->setEditTodoImportance($editTodoImportance);
      }
      // ステータスの確認
      $this->errorMessageEditTodoStatus = ToDoRegistrationCheck::todoStatusCheck($editTodoStatus);
      // エラーがあれば、エラーフラグを立てる
      if(!empty($this->errorMessageEditTodoStatus)) {
        $errorFlag = 1;
      // エラーがなければ、ToDo編集情報を書き換える
      } else {
        $this->setEditTodoStatus($editTodoStatus);
      }
      // エラーフラグを返す
      return $errorFlag;
    }


  //****************************************
  // 変更があるか確認
  //**************************************** 

    // Todo情報に変更があるか確認
    public function changeCheck($editTodoTitle, $editTodoContent, $editTodoDeadline, $editTodoDifficulty, $editTodoImportance, $editTodoStatus) {
      // 変更フラグの初期化
      $changeFlag = 0;
      // エラーメッセージの初期化
      $this->unsetErrorMessages();
      // タイトルの確認
      if ($editTodoTitle != RegisteredTodoData::getTodoTitle($this->editTodoID)) {
        $changeFlag = 1;
      }
      // 内容の確認
      if ($editTodoContent != RegisteredTodoData::getTodoContent($this->editTodoID)) {
        $changeFlag = 1;
      }
      // 期日の確認
      if ($editTodoDeadline != RegisteredTodoData::getTodoDeadline($this->editTodoID)) {
        $changeFlag = 1;
      }
      // 難易度の確認
      if ($editTodoDifficulty != RegisteredTodoData::getTodoDifficulty($this->editTodoID)) {
        $changeFlag = 1;
      }
      // 重要度の確認
      if ($editTodoImportance != RegisteredTodoData::getTodoImportance($this->editTodoID)) {
        $changeFlag = 1;
      }
      // ステータスの確認
      if ($editTodoStatus != RegisteredTodoData::getTodoStatus($this->editTodoID)) {
        $changeFlag = 1;
      }
      // 変更がなければ、エラーメッセージを設定する
      if($changeFlag == 0) {
        $this->errorMessageNoChange = "変更がありません。";
      }
      // 変更フラグを返す
      return $changeFlag;
    }    
    
    // 汎用的なエラー発生時の処理を実行
    public function setErrorProcedure() {
      // エラーメッセージの初期化
      $this->unsetErrorMessages();
      // エラーメッセージを設定
      $this->errorMessageEdit = "エラーが発生しました。もう一度やり直してください。";
    }


  //****************************************
  // エラーメッセージの初期化
  //**************************************** 
  
    // エラーメッセージの初期化
    private function unsetErrorMessages() {
      // エラーメッセージを初期化する
      $this->errorMessageEditTodoTitle = null;
      $this->errorMessageEditTodoContent = null;
      $this->errorMessageEditTodoDeadline = null;
      $this->errorMessageEditTodoDifficulty = null;
      $this->errorMessageEditTodoImportance = null;
      $this->errorMessageEditTodoStatus = null;
      $this->errorMessageEdit = null;
      $this->errorMessageNoChange = null;    
    }


  //****************************************
  // ToDoの編集処理
  //**************************************** 

    public function todoEdit() {
      // 編集完了フラグの初期化
      $this->todoEditExecuteCompFlag = null;
      // Todo情報が正しく入力されているか確認
      // 念のために再度入力情報の確認を実施
      if ($this->inputDataCheck($this->editTodoTitle, $this->editTodoContent, $this->editTodoDeadline, $this->editTodoDifficulty, $this->editTodoImportance, $this->editTodoStatus) == 0) {
        // 編集前のToDo登録情報を取得
        $todoTitle = RegisteredTodoData::getTodoTitle($this->editTodoID);
        $todoContent = RegisteredTodoData::getTodoContent($this->editTodoID);
        $todoDeadline = RegisteredTodoData::getTodoDeadline($this->editTodoID);
        $todoDifficulty = RegisteredTodoData::getTodoDifficulty($this->editTodoID);
        $todoImportance = RegisteredTodoData::getTodoImportance($this->editTodoID);
        $todoStatus = RegisteredTodoData::getTodoStatus($this->editTodoID);
        // データベースに接続
        $this->connectToDatabase();
        // Todoの編集を実行
        $sql = 'UPDATE todo SET todo_title = :todo_title, todo_content = :todo_content, todo_deadline = :todo_deadline, todo_difficulty = :todo_difficulty, todo_importance = :todo_importance, todo_status = :todo_status WHERE id = :id;';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':todo_title', $this->editTodoTitle, PDO::PARAM_STR);
        $sth->bindParam(':todo_content', $this->editTodoContent, PDO::PARAM_STR);
        $sth->bindParam('todo_deadline', $this->editTodoDeadline, PDO::PARAM_STR);
        $sth->bindParam(':todo_difficulty', $this->editTodoDifficulty, PDO::PARAM_INT);
        $sth->bindParam(':todo_importance', $this->editTodoImportance, PDO::PARAM_INT);
        $sth->bindParam(':todo_status', $this->editTodoStatus, PDO::PARAM_INT);
        $sth->bindParam(':id', $this->editTodoID, PDO::PARAM_INT);
        $sth->execute();
        unset($this->dbh);
        // 編集が完了したか確認（編集前のToDo登録情報と編集実行後の情報が異なっているか）
        $this->todoEditExecuteCompFlag = $this->changeCheck($todoTitle, $todoContent, $todoDeadline, $todoDifficulty, $todoImportance, $todoStatus);
      }
      // Todo登録の実行が失敗した場合
      if($this->todoEditExecuteCompFlag != 1) {
        // エラー処理
        $this->setErrorProcedure();
      }
      // 編集完了フラグを返す
      return $this->todoEditExecuteCompFlag;
    }
  

  //****************************************
  // ToDoの削除処理
  //**************************************** 

    public function todoDelete() {
      // 削除完了フラグの初期化
      $this->todoDeleteExecuteCompFlag = null;
      // データベースに接続
      $this->connectToDatabase();
      // Todoの削除を実行
      $sql = 'DELETE FROM todo WHERE id = :id;';
      $sth = $this->dbh->prepare($sql);
      $sth->bindParam(':id', $this->editTodoID, PDO::PARAM_INT);
      $sth->execute();
      unset($this->dbh);
      // 削除が完了したか確認（削除したToDoIDのタイトルが読み出せるか）
      $todoTitle = RegisteredTodoData::getTodoTitle($this->editTodoID);
      if(empty($todoTitle)) {
        $this->todoDeleteExecuteCompFlag = 1;
      } else {
        // エラー処理
        $this->setErrorProcedure();
      }
    // 削除完了フラグを返す
    return $this->todoDeleteExecuteCompFlag;
    }

  }

?>