<?php
  // phpファイルの読み込み
  require_once('../database_connect/registered_todo_data_class.php');
  require_once('../user_login/present_login_user_class.php');
  require_once('todo_edit_class.php');

  // セッションスタート
  session_start();

  // ログインユーザに関するセッション情報を取得
  $loginUser = $_SESSION['LoginUser'] ;
  // ユーザIDを取得
  $editUserID = $loginUser->getUserId();

  // ToDo編集情報に関するセッション情報を取得
  $editTodo = $_SESSION['EditTodo'];
  // Todo表示画面で選択されたTodoのIDを取得
  $editTodoID = $editTodo->getEditTodoID();

  // 選択されたToDOのIDとユーザID登録情報を基にToDo編集情報のインスタンスを再生成
  // （編集の過程でインスタンス内のToDo編集情報に関する変数が書き換えられていることを想定）
  $editTodo = new ToDoEditCheck($editTodoID, $editUserID);
  // 作成したインスタンスをセッションに保存
  $_SESSION['EditTodo'] = $editTodo;

  // 削除フラグをセッション情報に保存
  $_SESSION['DeleteFlag'] = 1;
    
  // エラーフラグを開放し、ToDo編集確認ページへ移行
  unset($_SESSION['EditErrorFlag']);
  header('Location: ../todo_edit_confirm.html');
  exit;

?>