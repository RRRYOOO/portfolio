<?php
  // phpファイルの読み込み
  require_once('../database_connect/database_connect.php');

  // ユーザが登録したToDoをすべて表示するクラスを定義
  class TodoShowAll {
    // ToDoの表示に関する変数
    public $todoArray;

    // ToDoエンプティフラグ
    private $todoEmptyFlag;
    // 接続するデータベースの情報
    private $dbh;
    

  //****************************************
  // コンストラクタ
  //****************************************

  // なし


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
  // ToDoを表示する
  //****************************************

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

    // 指定された表示順でセットされたToDo情報の配列を表示する
    public function showToDoThings($departmentID, $order) {
      // ToDoを取得
      $this->setToDo($departmentID);
      // ToDoの登録がない場合、メッセージを表示
      if($this->todoEmptyFlag == 1) {
        echo '<div class="main_message"><p style="font-weight: bold; text-align: center">ToDoの登録がありません。</p></div>';
      // ToDoの登録がある場合は、各ToDo情報を指定された表示順で表示する（テーブル)
      } else {
        // Todoを指定された表示順に並び替え
        $this->setTodoOrder($order);
        // Todoをテーブルで表示
        echo '<table class="table"><thead>
        <tr><th class="t1">タイトル</th>
        <th class="t2">期日</th>
        <th class="t3">難易度</th>
        <th class="t4">重要度</th>
        <th class="t5">ステータス</th>
        <th class="t6">担当部署</th>
        <th class="t7">担当者</th>
        <th class="t8">操作</th></tr>
        </thead><tbody>';
        foreach($this->todoArray as $todo) {
          echo '<tr><td class="t1 td_left">'.$todo["todo_title"].'</td>
          <td class="t2">'.$todo["todo_deadline"].'</td>
          <td class="t3">'.$this->exchangeDifficulty($todo["todo_difficulty"]).'</td>
          <td class="t4">'.$this->exchangeImportance($todo["todo_importance"]).'</td>
          <td class="t5">'.$this->exchangeStatus($todo["todo_status"]).'</td>
          <td class="t6">'.$todo["department"].'</td>
          <td class="t7">'.$todo["user_lastname"].' '.$todo["user_firstname"].'</td>
          <td class="t8"><form method="post" action="../todo_show_all/todo_show_detail.php"><input type="hidden" name="EditTodoID" value="'.$todo["id"].'"><input class="edit_button" type="submit" value="詳細"></form></td></tr>';
        }
      }
      echo '</tbody></table>';
      return;
    }

    // ToDoエンプティフラグの値を取得する
    public function getTodoEmptyFlag() {
      // ToDoエンプティフラグの値を返す
      return $this->todoEmptyFlag;
    }


  //****************************************
  // ToDoを取得
  //****************************************

    // データベースから登録されているToDoを取得
    private function setTodo($departmentID){
      // データベースに接続
      $this->connectToDatabase();
      // 部署指定が"全部署"の場合
      if($departmentID == 0) {
        $sql = 'SELECT todo.id, todo.registration_date, todo_title, todo_content, todo_deadline, todo_difficulty, todo_importance, todo_status, user_lastname, user_firstname, department FROM todo JOIN users ON todo.user_id = users.id LEFT JOIN department ON users.user_department_id = department.id ORDER BY todo_deadline ASC;';
        $sth = $this->dbh->prepare($sql);
      } else {
        $sql = 'SELECT todo.registration_date, todo_title, todo_content, todo_deadline, todo_difficulty, todo_importance, todo_status, user_lastname, user_firstname, department FROM todo JOIN users ON todo.user_id = users.id LEFT JOIN department ON users.user_department_id = department.id WHERE user_department_id = :user_department_id ORDER BY todo_deadline ASC;';
        $sth = $this->dbh->prepare($sql);
        $sth->bindparam(':user_department_id', $departmentID);
      }
      $sth->execute();
      // 取得したデータを配列に格納
      $this->todoArray = $sth->fetchAll(PDO::FETCH_ASSOC);
      // ToDoエンプティフラグの初期化
      $this->todoEmptyFlag = 0;
      // ToDoがない場合はTodoエンプティフラグを立てる
      if(empty($this->todoArray)) {
        $this->todoEmptyFlag = 1;
      }
      return;
    }


  //****************************************
  // ToDoを指定された表示順でセットする
  //****************************************

    // 指定された表示順になるようにToDoの配列を操作
    public function setTodoOrder($order) {
      switch($order) {
        case 1:  // 期限が昇順
          $this->setTodoDeadlineAsc();
          break;
        case 2:  // 期限が降順
          $this->setTodoDeadlineDesc();
          break;
        case 3:  // 難易度が昇順
          $this->setTodoDifficultyAsc();
          break;
        case 4:  // 難易度が降順
          $this->setTodoDifficultyDesc();
          break;
        case 5:  // 重要度が昇順
          $this->setTodoImportanceAsc();
          break;
        case 6:  // 重要度が降順
          $this->setTodoImportanceDesc();
          break;
        case 7:  // ステータスが昇順
          $this->setTodoStatusAsc();
          break;
        case 8:  // ステータスが降順
          $this->setTodoStatusDesc();
          break;
        default:  // それ以外の場合は期限が昇順
          $this->setTodoDeadlineAsc();
        }
      return;
    }

    // ToDoを期限が昇順になるようにセット
    private function setTodoDeadlineAsc() {
      // ToDoを期限が昇順になるように並び替え
      $order = array_column($this->todoArray, 'todo_deadline');
      array_multisort($order, SORT_ASC, $this->todoArray);
      return;
    }

    // ToDoを期限が降順になるようにセット
    private function setTodoDeadlineDesc() {
      // ToDoを期限が降順になるように並び替え
      $order = array_column($this->todoArray, 'todo_deadline');
      array_multisort($order, SORT_DESC, $this->todoArray);
      return;
    }

    // ToDoを難易度が昇順になるようにセット
    private function setTodoDifficultyAsc() {
      // ToDoを難易度が昇順になるように並び替え
      $order = array_column($this->todoArray, 'todo_difficulty');
      array_multisort($order, SORT_ASC, $this->todoArray);
      return;
    }

    // ToDoを難易度が降順になるようにセット
    private function setTodoDifficultyDesc() {
      // ToDoを難易度が降順になるように並び替え
      $order = array_column($this->todoArray, 'todo_difficulty');
      array_multisort($order, SORT_DESC, $this->todoArray);
      return;
    }

    // ToDoを重要度が昇順になるようにセット
    private function setTodoImportanceAsc() {
      // ToDoを重要度が昇順になるように並び替え
      $order = array_column($this->todoArray, 'todo_importance');
      array_multisort($order, SORT_ASC, $this->todoArray);
      return;
    }
  
    // ToDoを重要度が降順になるようにセット
    private function setTodoImportanceDesc() {
      // ToDoを重要度が降順になるように並び替え
      $order = array_column($this->todoArray, 'todo_importance');
      array_multisort($order, SORT_DESC, $this->todoArray);
      return;
    }

    // ToDoをステータスが昇順になるようにセット
    private function setTodoStatusAsc() {
      // ToDoをステータスが昇順になるように並び替え
      $order = array_column($this->todoArray, 'todo_status');
      array_multisort($order, SORT_ASC, $this->todoArray);
      return;
    }
  
    // ToDoをステータスが降順になるようにセット
    private function setTodoStatusDesc() {
      // ToDoをステータスが降順になるよう並び替え
      $order = array_column($this->todoArray, 'todo_status');
      array_multisort($order, SORT_DESC, $this->todoArray);
      return;
    }
  }
?>
