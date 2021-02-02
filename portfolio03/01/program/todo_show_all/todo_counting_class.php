<?php
  // phpファイルの読み込み
  require_once('../database_connect/database_connect.php');
  require_once('../database_connect/department_data_class.php');

  // 部署ごとのToDoの集計を表示するクラスを定義
  class TodoCounting {
    // ToDoの表示に関する変数
    private static $todoArray;
    // 接続するデータベースの情報
    private static $dbh;
    

  //****************************************
  // コンストラクタ
  //****************************************

  // なし


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
  // ToDoの集計を表示する
  //****************************************
     
    // 部署ごとのToDoの集計結果を表示する
    public static function showToDoCounting() {
      // データベースに接続
      self::connectToDatabase();
      // テーブルを表示
      echo 
        '<table><thead>
        <tr class="thead1">
        <th class="th1">担当部署</th>
        <th class="th2">ToDo総数</th>
        <th class="th3" colspan="3">難易度<br><span class="th_under">(低/中/高)</span></th>
        <th class="th4" colspan="3">重要度<br><span class="th_under">(低/中/高)</span></th>
        <th class="th5" colspan="4">ステータス<br><span class="th_under">(未着手/進行中/完了/キャンセル)</span></th>
        </tr></thead><tbody>' ;
      // 部署データを取得
      $sql = 'SELECT * FROM department';
      $departments = self::$dbh->query($sql);
      // 部署ごとのToDoの集計結果を取得
      foreach($departments as $row) {
        // 部署ごとのToDoを取得
        self::setToDo($row["id"]);
        // Todoの集計結果をテーブルで表示
        echo 
          '<tr><td class="th1">'.$row["department"].' </td>
          <td class="th2 th_right">'.self::getTodoTotalNum().' </td>
          <td class="th3-1 th_right">'.self::getTodoTotalNumDifficultyLow().'</td>
          <td class="th3-1 th_right">'.self::getTodoTotalNumDifficultyMiddle().'</td>
          <td class="th3-1 th_right">'.self::getTodoTotalNumDifficultyHigh().'</td>
          <td class="th4-1 th_right">'.self::getTodoTotalNumImportanceLow().'</td> 
          <td class="th4-1 th_right">'.self::getTodoTotalNumImportanceMiddle().'</td>
          <td class="th4-1 th_right">'.self::getTodoTotalNumImportanceHigh().'</td>
          <td class="th5-1 th_right">'.self::getTodoTotalNumStatusNotStart().'</td>
          <td class="th5-1 th_right">'.self::getTodoTotalNumStatusDoing().'</td>
          <td class="th5-1 th_right">'.self::getTodoTotalNumStatusComp().'</td>
          <td class="th5-1 th_right">'.self::getTodoTotalNumStatusCancel().'</td></tr>';
      }
      echo '</tbody></table>';
      return;
    }


  //****************************************
  // ToDoを取得
  //****************************************

    // 渡された部署IDの登録されているToDoを取得
    private static function setTodo($departmentID){
      // データベースに接続
      self::connectToDatabase();
      $sql = 'SELECT * FROM todo JOIN users ON todo.user_id = users.id LEFT JOIN department ON users.user_department_id = department.id WHERE department.id = :department_id';
      $sth = self::$dbh->prepare($sql);
      $sth->bindparam(':department_id', $departmentID);
      $sth->execute();
      // 取得したデータを配列に格納
      self::$todoArray = $sth->fetchAll(PDO::FETCH_ASSOC);
      return;
    }

  //****************************************
  // ToDoを集計
  //****************************************

    // 取得したToDoの総数を返す
    private static function getTodoTotalNum(){
      $number = 0;
      $number = count(self::$todoArray);
      return $number;
    }

    // 取得したToDoのうち、難易度が"低"のものの総数を返す
    private static function getTodoTotalNumDifficultyLow(){
      $number = 0;
      foreach(self::$todoArray as $row) {
        if($row['todo_difficulty'] == 1) {
          $number++;
        }
      }
      return $number;
    }

    // 取得したToDoのうち、難易度が"中"のものの総数を返す
    private static function getTodoTotalNumDifficultyMiddle(){
      $number = 0;
      foreach(self::$todoArray as $row) {
        if($row['todo_difficulty'] == 2) {
          $number++;
        }
      }
      return $number;
    }

    // 取得したToDoのうち、難易度が"高"のものの総数を返す
    private static function getTodoTotalNumDifficultyHigh(){
      $number = 0;
      foreach(self::$todoArray as $row) {
        if($row['todo_difficulty'] == 3) {
          $number++;
        }
      }
      return $number;
    }

    // 取得したToDoのうち、重要度が"低"のものの総数を返す
    private static function getTodoTotalNumImportanceLow(){
      $number = 0;
      foreach(self::$todoArray as $row) {
        if($row['todo_importance'] == 1) {
          $number++;
        }
      }
      return $number;
    }

    // 取得したToDoのうち、重要度が"中"のものの総数を返す
    private static function getTodoTotalNumImportanceMiddle(){
      $number = 0;
      foreach(self::$todoArray as $row) {
        if($row['todo_importance'] == 2) {
          $number++;
        }
      }
      return $number;
    }

    // 取得したToDoのうち、重要度が"高"のものの総数を返す
    private static function getTodoTotalNumImportanceHigh(){
      $number = 0;
      foreach(self::$todoArray as $row) {
        if($row['todo_importance'] == 3) {
          $number++;
        }
      }
      return $number;
    }

    // 取得したToDoのうち、ステータスが"未着手"のものの総数を返す
    private static function getTodoTotalNumStatusNotStart(){
      $number = 0;
      foreach(self::$todoArray as $row) {
        if($row['todo_status'] == 1) {
          $number++;
        }
      }
      return $number;
    }

    // 取得したToDoのうち、ステータスが"進行中"のものの総数を返す
    private static function getTodoTotalNumStatusDoing(){
      $number = 0;
      foreach(self::$todoArray as $row) {
        if($row['todo_status'] == 2) {
          $number++;
        }
      }
      return $number;
    }

    // 取得したToDoのうち、ステータスが"完了"のものの総数を返す
    private static function getTodoTotalNumStatusComp(){
      $number = 0;
      foreach(self::$todoArray as $row) {
        if($row['todo_status'] == 3) {
          $number++;
        }
      }
      return $number;
    }    

    // 取得したToDoのうち、ステータスが"キャンセル"のものの総数を返す
    private static function getTodoTotalNumStatusCancel(){
      $number = 0;
      foreach(self::$todoArray as $row) {
        if($row['todo_status'] == 4) {
          $number++;
        }
      }
      return $number;
    } 

  }
?>
