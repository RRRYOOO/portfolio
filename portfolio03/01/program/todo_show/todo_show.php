<?php
  // phpファイルの読み込み
  require_once('../user_login/present_login_user_class.php');
  require_once('todo_show_class.php');
  // セッションスタート
  session_start();
  
  // ログインユーザに関するセッション情報を取得
  $loginUser = $_SESSION['LoginUser'];
  // ログイン中のユーザのユーザIDを取得
  $loginUserID = $loginUser->getUserID();
  
  // ログイン中のユーザの登録しているToDoを取得
  $todoShow = new TodoShow($loginUserID);
  
  // ToDo表示画面で押された表示指定を取得
  if(!empty($_POST['Order'])) {
    $order = $_POST['Order'];
  // 表示指定がない場合、期限の昇順を指定
  } else {
    $order = 1;
  } 
  // 表示順序を設定
  $todoShow->setTodoOrder($order);

  // 表示順序を設定したインスタンスをセッションに保存
  $_SESSION['TodoShow'] = $todoShow; 
  // Todo表示画面に戻る
  header('Location: ../pages/todo_show.html');
?>