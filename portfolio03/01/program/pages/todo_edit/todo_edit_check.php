<?php
  // phpファイルの読み込み
  require_once('todo_edit_class.php');

  // セッションスタート
  session_start();

  // ToDo編集情報に関するセッション情報を取得
  $editTodo = $_SESSION['EditTodo'];
  
  // Todo編集画面で入力された情報を取得
  $editTodoTitle = $_POST['Title'];
  $editTodoContent = $_POST['Content'];
  $editTodoDeadline = $_POST['Deadline'];
  $editTodoDifficulty = $_POST['Difficulty'];
  $editTodoImportance = $_POST['Importance'];
  $editTodoStatus = $_POST['Status'];

  // Todo編集情報に変更があるか確認
  $changeFlag = $editTodo->changeCheck($editTodoTitle, $editTodoContent, $editTodoDeadline, $editTodoDifficulty, $editTodoImportance, $editTodoStatus); 

  // 変更点がない場合
  if($changeFlag == 0) {
    // エラーフラグをセッションに保存し、ToDo編集画面に戻る
    $_SESSION['EditErrorFlag'] = 1;
    header('Location: ../todo_edit.html');
    exit;    
  }

  // Todo編集情報が正しく入力されているか確認
  $errorFlag = $editTodo->inputDataCheck($editTodoTitle, $editTodoContent, $editTodoDeadline, $editTodoDifficulty, $editTodoImportance, $editTodoStatus);

  // エラーがある場合
  if($errorFlag == 1) {
    // エラーフラグをセッションに保存し、ToDo編集画面に戻る
    $_SESSION['EditErrorFlag'] = 1;
    header('Location: ../todo_edit.html');
    exit;
  // エラーがない場合
  } else if($errorFlag == 0) {
    // 作成したインスタンスをセッションに保存
    $_SESSION['EditTodo'] = $editTodo;
    // 確認合格フラグをセッションに保存
    $_SESSION['EditCheckPassFlag'] = 1;
    // エラーフラグのセッション情報を解放し、確認画面へ遷移
    unset($_SESSION['EditErrorFlag']);
    header('Location: ../todo_edit_confirm.html');
    exit;
  // その他何かしらのエラーが発生した場合  
  } else {
    // エラー処理を実行
    $editTodo->setErrorProcedure();
    // エラーフラグをセッションに保存し、ToDo編集画面に戻る
    $_SESSION['EditErrorFlag'] = 1;
    header('Location: ../todo_edit.html');
    exit;
  }

?>
  