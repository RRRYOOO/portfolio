<?php
  // phpファイルの読み込み
  require_once('todo_edit_class.php');
  require_once('../user_login/present_login_user_class.php');

  // セッションスタート
  session_start();

  // ToDo編集情報に関するセッション情報を取得(チェック済み)
  $editTodo = $_SESSION['EditTodo'];
  // Todo編集を実行
  $executionCompFlag = $editTodo->todoEdit();

    // Todo編集の実行が失敗した場合
  if($executionCompFlag == 0){
    // ログインユーザに関するセッション情報を取得
    $loginUser = $_SESSION['LoginUser'] ;
    // ユーザIDを取得
    $editUserID = $loginUser->getUserId();
    // 選択されたToDOのIDとユーザID登録情報を基にToDo編集情報のインスタンスを再生成
    // （編集の過程でインスタンス内のToDo編集情報に関する変数が書き換えられていることを想定）
    $editTodo = new ToDoEditCheck($editTodoID, $editUserID);
    // 作成したインスタンスをセッションに保存
    $_SESSION['EditTodo'] = $editTodo;
    // エラーフラグをセッションに保存し、Todo編集画面に戻る
    $_SESSION['EditErrorFlag'] = 1;
    // ToDo編集画面へ遷移する
    header('Location: ../todo_edit.html');
    exit;
  // Todo編集の実行が成功した場合
  } else if($executionCompFlag == 1){
    // Todo編集に使用したセッション情報を破棄
    unset($_SESSION['EditTodo'], $_SESSION['EditErrorFlag'], $_SESSION['DeleteFlag'], $_SESSION['EditCheckPassFlag']);
    // ToDo表示画面へ遷移
    $_SESSION['EditCompMessage'] = "編集が完了しました。";
    header('Location: ../todo_show.html');
    exit;
    // その他何かしらのエラーが発生した場合  
  } else {
    // エラー処理を実行
    $editTodo->setErrorProcedure();
    // エラーフラグをセッションに保存し、ToDo登録画面に戻る
    $_SESSION['EditErrorFlag'] = 1;
    header('Location: ../todo_edit.html');
    exit;
  }

?>