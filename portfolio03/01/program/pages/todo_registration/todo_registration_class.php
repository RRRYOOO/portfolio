<?php
  // phpファイルの読み込み
  require_once('../database_connect/database_connect.php');
  require_once('todo_registration_check_class.php');

  // 入力されたtodo登録情報のチェックとtodo登録を行うクラスを定義
  class ToDoRegistration {
    // todo登録情報に関する変数
    private $tempUserID;
    private $tempTodoTitle;
    private $tempTodoContent;
    private $tempTodoDeadline;
    private $tempTodoDifficulty;
    private $tempTodoImportance;
    private $tempTodoStatus;
    // 接続するデータベースの情報
    private $dbh;
    // エラーメッセージに関する変数
    public $errorMessageTodoTitle;
    public $errorMessageTodoContent;
    public $errorMessageTodoDeadline;
    public $errorMessageTodoDifficulty;
    public $errorMessageTodoImportance;
    public $errorMessageRegistration;
    // todo登録に使用する変数
    private $todoRegistrationExecuteCompFlag;

  //****************************************
  // コンストラクタ
  //****************************************

    public function __construct($tempUserID, $tempTodoTitle, $tempTodoContent, $tempTodoDeadline, $tempTodoDifficulty, $tempTodoImportance){
      // 登録画面で入力されたデータを取得
      $this->tempUserID = $tempUserID;
      $this->tempTodoTitle = $tempTodoTitle;
      $this->tempTodoContent = $tempTodoContent;
      $this->tempTodoDeadline = $tempTodoDeadline;
      $this->tempTodoDifficulty = $tempTodoDifficulty;
      $this->tempTodoImportance = $tempTodoImportance;
      $this->tempTodoStatus = 1;
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
  // 入力されたtodo登録情報を返す
  //****************************************

    // ユーザIDを返す
    public function getTempUserID() {
      return $this->tempUserID;
    }

    // タイトルを返す
    public function getTempTodoTitle() {
      return $this->tempTodoTitle;
    }

    // 内容を返す
    public function getTempTodoContent() {
      return $this->tempTodoContent;
    }

    // 期限を返す
    public function getTempTodoDeadline() {
      return $this->tempTodoDeadline;
    }

    // 難易度を返す("低"or"中"or"高"で返す)
    public function getTempTodoDifficulty() {
      switch ($this->tempTodoDifficulty) {
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

    // 重要度を返す("低"or"中"or"高"で返す)
    public function getTempTodoImportance() {
      switch ($this->tempTodoImportance) {
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

    // ステータスを返す("未着手"or"進行中"or"完了"or"キャンセル"で返す)
    public function getTempTodoIStatus() {
      switch ($this->tempTodoStatus) {
        case 1:
          return '未着手';
        case 2:
          return '進行中';
        case 3:
          return '完了';
        case 4:
          return 'キャンセル';
        default:
          return;
      }
    }   


  //****************************************
  // 入力情報の確認
  //**************************************** 

    // Todo登録情報が正しく入力されているか確認
    public function inputDataCheck() {
      // エラーフラグの初期化
      $errorFlag = 0;
      // タイトルの確認
      if(!empty($this->todoTitleCheck())) {
        $errorFlag = 1;
      }
      // 内容の確認
      if(!empty($this->todoContentCheck())) {
        $errorFlag = 1;
      }
      // 期限の確認
      if(!empty($this->todoDeadlineCheck())) {
        $errorFlag = 1;
      }
      // 難易度の確認
      if(!empty($this->todoDifficultyCheck())) {
        $errorFlag = 1;
      }
      // 重要度の確認
      if(!empty($this->todoImportanceCheck())) {
        $errorFlag = 1;
      }
      // ユーザIDの確認
      if(!empty($this->userIDcheck())) {
        $errorFlag = 1;
      // ステータスの確認
      } else if (!empty($this->todoStatuscheck())) {
        $errorFlag = 1;
      }
      // エラーフラグを返す
      return $errorFlag;
    }

    // ユーザIDが正しく設定されているか確認
    public function userIDcheck() {
      $this->errorMessageTodoUserID = ToDoRegistrationCheck::userIDCheck($this->tempUserID);
      if (!empty($this->errorMessageTodoUserID)) {
        // エラー処理を実施する
        $this->setErrorProcedure();
      }
      // エラーメッセージを返す
      return $this->errorMessageRegistration;
    }

    // タイトルが正しく入力されているか確認
    public function todoTitleCheck() {
      $this->errorMessageTodoTitle = ToDoRegistrationCheck::todoTitleCheck($this->tempTodoTitle);
      if(!empty($this->errorMessageTodoTitle)){
        $this->tempTodoTitle = null;
      }
      // エラーメッセージを返す
      return $this->errorMessageTodoTitle;
    }
 
    // 内容が正しく入力されているか確認
    public function todoContentCheck() {
      $this->errorMessageTodoContent = ToDoRegistrationCheck::todoContentCheck($this->tempTodoContent);
      if(!empty($this->errorMessageTodoContent)){
        $this->tempTodoContent = null;
      }      
      // エラーメッセージを返す
      return $this->errorMessageTodoContent;
    }

    // 期限が正しく選択されているか確認
    public function todoDeadlineCheck() {
      $this->errorMessageTodoDeadline = ToDoRegistrationCheck::todoDeadlineCheck($this->tempTodoDeadline);
      if(!empty($this->errorMessageTodoDeadline)){
        $this->tempTodoDeadline = null;
      }           
      return $this->errorMessageTodoDeadline;
    }

    // 難易度が正しく選択されているか確認
    public function todoDifficultyCheck() {
      $this->errorMessageTodoDifficulty = ToDoRegistrationCheck::todoDifficultyCheck($this->tempTodoDifficulty);
      if(!empty($this->errorMessageTodoDifficulty)){
        $this->tempTodoDifficulty = null;
      }       
      // エラーメッセージを返す
      return $this->errorMessageTodoDifficulty;
    }

    // 重要度が正しく選択されているか確認
    public function todoImportanceCheck() {
      $this->errorMessageTodoImportance = ToDoRegistrationCheck::todoImportanceCheck($this->tempTodoImportance);
      if(!empty($this->errorMessageTodoImportance)){
        $this->tempTodoImportance = null;
      }       
      // エラーメッセージを返す
      return $this->errorMessageTodoImportance;
    }
 
    // ステータスが正しく設定されているか確認（登録時は"未着手"(1))
    public function todoStatuscheck() {
      if ($this->tempTodoStatus != 1) {
        // エラー処理を実施する
        $this->setErrorProcedure();
      }
      // エラーメッセージを返す
      return $this->errorMessageRegistration;
    }

    // エラー発生時の処理を実行
    public function setErrorProcedure() {
      // エラーメッセージを設定
      $this->errorMessageRegistration = "エラーが発生しました。もう一度登録してください。";
      // todo登録情報を破棄
      $this->tempUserID = null;
      $this->tempTodoTitle = null;
      $this->tempTodoContent = null;
      $this->tempTodoDeadline = null;
      $this->tempTodoDifficulty = null;
      $this->tempTodoImportance = null;
      $this->tempTodoStatus = null;
      // エラーメッセージを破棄
      $this->errorMessageTodoUserID = null;
      $this->errorMessageTodoTitle = null;
      $this->errorMessageTodoContent = null;
      $this->errorMessageTodoDeadline = null;
      $this->errorMessageTodoDifficulty = null;
      $this->errorMessageTodoImportance = null;
    }
 
    
  //****************************************
  // Todo登録
  //**************************************** 

    // Todo登録を実行する
    public function todoRegistrationExecute() {
      // 登録完了フラグの初期化
      $this->todoRegistrationExecuteCompFlag = null;
      // 念のために再度入力情報の確認を実施
      if($this->inputDataCheck() == 0) {
        // データベースに接続
        $this->connectToDatabase();
        // 登録日をセット（日本時間）
        date_default_timezone_set('Asia/Tokyo');
        $registrationDate = date('Y-m-d');
        // Todo登録を実行
        $sql = 'INSERT INTO todo (user_id, registration_date, todo_title, todo_content, todo_deadline, todo_difficulty, todo_importance, todo_status) 
        VALUES (:user_id, :registration_date, :todo_title, :todo_content, :todo_deadline, :todo_difficulty, :todo_importance, :todo_status);';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':user_id', $this->tempUserID, PDO::PARAM_INT);
        $sth->bindParam(':registration_date', $registrationDate, PDO::PARAM_STR);
        $sth->bindParam(':todo_title', $this->tempTodoTitle, PDO::PARAM_STR);
        $sth->bindParam(':todo_content', $this->tempTodoContent, PDO::PARAM_STR);
        $sth->bindParam('todo_deadline', $this->tempTodoDeadline, PDO::PARAM_STR);
        $sth->bindParam(':todo_difficulty', $this->tempTodoDifficulty, PDO::PARAM_INT);
        $sth->bindParam(':todo_importance', $this->tempTodoImportance, PDO::PARAM_INT);
        $sth->bindParam(':todo_status', $this->tempTodoStatus, PDO::PARAM_INT);
        $sth->execute();
        unset($this->dbh);
        // 登録が完了したか確認（登録したタイトルを読み出すことができるか確認）
        // データベースに接続
        $this->connectToDatabase();
        $sql = 'SELECT todo_title FROM todo WHERE todo_title = :todo_title;';
        $sth = $this->dbh->prepare($sql);
        $sth->bindparam(':todo_title', $this->tempTodoTitle);
        $sth->execute();
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        if(!empty($row['todo_title'])){
          $this->todoRegistrationExecuteCompFlag = 1;
        }
      }
      // Todo登録の実行が失敗した場合
      if(empty($this->todoRegistrationExecuteCompFlag)) {
        // エラー処理
        $this->setErrorProcedure();
      }
      // 登録完了フラグを返す
      return $this->todoRegistrationExecuteCompFlag;
    }
  }
?>
