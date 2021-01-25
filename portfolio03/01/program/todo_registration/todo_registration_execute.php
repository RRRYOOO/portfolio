<?php 
  // phpファイルの読み込み
  require_once('todo_registration_class.php');

  // セッションスタート
  session_start();

  // 前回の入力値で作成したインスタンスを受け取る(入力チェック済み)
  $temporaryTodo = $_SESSION['RegistrationTempTodo'];

  // 前回の入力値でTodo登録を実行;
  $executionCompFlag = $temporaryTodo->todoRegistrationExecute();

  // Todo登録の実行が失敗した場合
  if($executionCompFlag == 0){
    // リダイレクトフラグとエラーフラグをセッションに保存し、Todo登録画面に戻る
    $_SESSION['RegistrationRedirectFlag'] = 1;
    $_SESSION['RegistrationErrorFlag'] = 1;
    // ToDo登録画面へ遷移する
    header('Location: ../pages/todo_registration.html');
    exit;
  // Todo登録の実行が成功した場合
  } else if($executionCompFlag == 1){
    // Todo登録に使用したセッション情報を破棄
    unset($_SESSION['RegistrationTempTodo'], $_SESSION['RegistrationRedirectFlag'], $_SESSION['RegistrationErrorFlag'], $_SESSION['RegistrationCheckPassFlag']);
    // ToDo表示画面へ遷移
    $_SESSION['RegisterationCompMessage'] = "登録が完了しました。";
    header('Location: ../pages/todo_show.html');
    exit;
    // その他何かしらのエラーが発生した場合  
  } else {
    // エラー処理を実行
    $temporaryUser->setErrorProcedure();
    // リダイレクトフラグとエラーフラグをセッションに保存し、ToDo登録画面に戻る
    $_SESSION['RegistrationRedirectFlag'] = 1;
    $_SESSION['RegistrationErrorFlag'] = 1;
    header('Location: ../pages/todo_registration.html');
    exit;
  }
  
?>