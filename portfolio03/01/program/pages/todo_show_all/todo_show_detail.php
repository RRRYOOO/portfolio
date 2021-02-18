<?php
  // phpファイルの読み込み
  require_once('todo_detail_show_class.php');
  // セッションスタート
  session_start();

  // ToDo全表示画面で選択されたToDoのIDを取得
  $todoID = $_POST['TodoID'];

  // 選択されたToDoのToDoIDを基にインスタンスを生成
  $todo = new TodoDetailShow($todoID);

  // 作成したインスタンスをセッションに保存
  $_SESSION['ToDoDetailShow'] = $todo;

  // ToDo詳細表示画面へ遷移
  header('Location: ../todo_detail_show.html');
  exit;
?>
