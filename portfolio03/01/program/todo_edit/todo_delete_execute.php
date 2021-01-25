<?php
  // phpファイルの読み込み
  require_once('todo_edit_class.php');

  // セッションスタート
  session_start();

  // ToDo編集情報に関するセッション情報を取得(チェック済み)
  $editTodo = $_SESSION['EditTodo'];
  // Todo削除を実行
  $executionCompFlag = $editTodo->todoDelete();

    // Todo削除の実行が失敗した場合
  if($executionCompFlag == 0){
    // エラーフラグをセッションに保存し、Todo編集画面に戻る
    $_SESSION['EditErrorFlag'] = 1;
    // ToDo編集画面へ遷移する
    header('Location: ../pages/todo_edit.html');
    exit;
  // Todo削除の実行が成功した場合
  } else if($executionCompFlag == 1){
    // Todo編集に使用したセッション情報を破棄
    unset($_SESSION['EditTodo'], $_SESSION['EditErrorFlag'], $_SESSION['DeleteFlag'], $_SESSION['EditCheckPassFlag']);
    // ToDo表示画面へ遷移
    $_SESSION['DeleteCompMessage'] = "削除が完了しました。";
    header('Location: ../pages/todo_show.html');
    exit;
    // その他何かしらのエラーが発生した場合  
  } else {
    // エラー処理を実行
    $editTodo->setErrorProcedure();
    // エラーフラグをセッションに保存し、ToDo登録画面に戻る
    $_SESSION['EditErrorFlag'] = 1;
    header('Location: ../pages/todo_edit.html');
    exit;
  }

?>