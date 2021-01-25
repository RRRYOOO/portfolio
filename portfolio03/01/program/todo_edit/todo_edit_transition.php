<?php
  // phpファイルの読み込み
  require_once('../database_connect/registered_todo_data_class.php');
  require_once('../user_login/present_login_user_class.php');
  require_once('todo_edit_check_class.php');

  // セッションスタート
  session_start();

  // Todo表示画面で選択されたTodoのIDを取得
  $editTodoID = $_POST['EditTodoID'];
  // ログインユーザに関するセッション情報を取得
  $loginUser = $_SESSION['LoginUser'] ;
  // ユーザIDを取得
  $editUserID = $loginUser->getUserId();

  // 選択されたToDOのIDとユーザID登録情報を基にToDo編集情報のインスタンスを生成
  $editTodo = new ToDoEditCheck($editTodoID, $editUserID);
  // 作成したインスタンスをセッションに保存
  $_SESSION['EditTodo'] = $editTodo;

  header('Location: ../pages/todo_edit.html');
  exit;

?>