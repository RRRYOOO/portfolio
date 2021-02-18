<?php
  // phpファイルの読み込み
  require_once('../database_connect/database_connect.php');

  // 管理者が選択したToDoの詳細を表示するクラスを定義
  class TodoDetailShow {
    // ToDoの表示に関する変数
    private $todoArray;
    private $todoID;

    // 接続するデータベースの情報
    private $dbh;
    

  //****************************************
  // コンストラクタ
  //****************************************

  public function __construct($todoID){
    // ログイン中のユーザのユーザIDを取得
    $this->todoID = $todoID;
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
  // 取得したToDo情報を返す
  //****************************************

    // 取得したToDoのタイトルを返す 
    public function getTodoTitle() {
      // データベースからToDoを取得
      $this->setTodo();
      // 取得したToDoのタイトルを返す 
      foreach($this->todoArray as $todo) {
        return $todo["todo_title"];
      }
    }

    // 取得したToDoの内容を返す 
    public function getTodoContent() {
      // データベースからToDoを取得
      $this->setTodo();
      // 取得したToDoの内容を返す 
      foreach($this->todoArray as $todo) {
        return $todo["todo_content"];
      }
    }

    // 取得したToDoの登録日を返す 
    public function getTodoRegistrationDate() {
      // データベースからToDoを取得
      $this->setTodo();
      // 取得したToDoの登録日を返す 
      foreach($this->todoArray as $todo) {
        return $todo["registration_date"];
      }
    }

    // 取得したToDoの期限を返す 
    public function getTodoDeadline() {
      // データベースからToDoを取得
      $this->setTodo();
      // 取得したToDoの期限を返す 
      foreach($this->todoArray as $todo) {
        return $todo["todo_deadline"];
      }
    }

    // 取得したToDoの担当部署を返す 
    public function getTodoDepartment() {
      // データベースからToDoを取得
      $this->setTodo();
      // 取得したToDoの担当部署を返す 
      foreach($this->todoArray as $todo) {
        return $todo["department"];
      }
    }

    // 取得したToDoの担当者の姓を返す 
    public function getTodoUserLastName() {
      // データベースからToDoを取得
      $this->setTodo();
      // 取得したToDoの担当者の姓を返す 
      foreach($this->todoArray as $todo) {
        return $todo["user_lastname"];
      }
    }

    // 取得したToDoの担当者の名を返す 
    public function getTodoUserFirstName() {
      // データベースからToDoを取得
      $this->setTodo();
      // 取得したToDoの担当者の名を返す 
      foreach($this->todoArray as $todo) {
        return $todo["user_firstname"];
      }
    }

    // 取得したToDoの難易度を返す 
    public function getTodoDifficulty() {
      // データベースからToDoを取得
      $this->setTodo();
      // 取得したToDoの難易度を返す 
      foreach($this->todoArray as $todo) {
        return $this->exchangeDifficulty($todo["todo_difficulty"]);
      }
    }

    // 取得したToDoの重要度を返す 
    public function getTodoImportance() {
      // データベースからToDoを取得
      $this->setTodo();
      // 取得したToDoの重要度を返す 
      foreach($this->todoArray as $todo) {
        return $this->exchangeImportance($todo["todo_importance"]);
      }
    }

    // 取得したToDoのステータスを返す 
    public function getTodoStatus() {
      // データベースからToDoを取得
      $this->setTodo();
      // 取得したToDoのステータスを返す 
      foreach($this->todoArray as $todo) {
        return $this->exchangeStatus($todo["todo_status"]);
      }
    }    

    // 難易度を日本語に変換 
    private function exchangeDifficulty($difficultyLevel) {
      switch ($difficultyLevel) {
        case 1:
          return "低";
        case 2:
          return "中";
        case 3:
          return "高";
        default:
          return;
      }
    }

    // 重要度を日本語に変換 
    private function exchangeImportance($importanceLevel) {
      switch ($importanceLevel) {
        case 1:
          return "低";
        case 2:
          return "中";
        case 3:
          return "高";
        default:
          return;
      }
    }

    // ステータスを日本語に変換 
    private function exchangeStatus($statusLevel) {
      switch ($statusLevel) {
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
  // ToDoを取得
  //****************************************

    // データベースから指定されたToDoIDに紐づくToDoの情報を取得
    private function setTodo(){
      // データベースからToDoの情報を取得していない場合、データベースに接続し、ToDo情報を習得する
      // 既にデータベースからToDoの情報を取得していた場合、データベースに再アクセスしない
      if(empty($this->todoArray)) {
        $this->connectToDatabase();
        $sql = 'SELECT todo.registration_date, todo_title, todo_content, todo_deadline, todo_difficulty, todo_importance, todo_status, user_lastname, user_firstname, department FROM todo JOIN users ON todo.user_id = users.id LEFT JOIN department ON users.user_department_id = department.id WHERE todo.id = :todo_id;';
        $sth = $this->dbh->prepare($sql);
        $sth->bindparam(':todo_id', $this->todoID); 
        $sth->execute();
        // 取得したデータを配列に格納
        $this->todoArray = $sth->fetchAll(PDO::FETCH_ASSOC);
      }
      return;
    }
  }
?>
