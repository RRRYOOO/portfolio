<?php 
  // phpファイルの読み込み
  require_once('todo_registration_class.php');
  require_once('../user_login/present_login_user_class.php');

  // セッションスタート
  session_start();

  // ログインユーザに関するセッション情報を取得
  $loginUser = $_SESSION['LoginUser'] ;
  // ユーザIDを取得
  $tempUserID = $loginUser->getUserId();
  // Todo登録画面で入力されたTodo登録情報を取得
  $tempTodoTitle = $_POST['Title'];
  $tempTodoContent = $_POST['Content'];
  $tempTodoDeadline = $_POST['Deadline'];
  $tempTodoDifficulty = $_POST['Difficulty'];
  $tempTodoImportance = $_POST['Importance'];

  // 入力されたToDO登録情報を基にインスタンスを生成
  $temporaryTodo = new TodoRegistration($tempUserID, $tempTodoTitle, $tempTodoContent, $tempTodoDeadline, $tempTodoDifficulty, $tempTodoImportance);

  // 作成したインスタンスをセッションに保存
  $_SESSION['RegistrationTempTodo'] = $temporaryTodo;

  // ToDo登録情報が正しく入力されているか確認
  $errorFlag = $temporaryTodo->inputDataCheck();

  // エラーがある場合
  if($errorFlag == 1) {
    // リダイレクトフラグとエラーフラグをセッションに保存し、ToDo登録画面に戻る
    $_SESSION['RegistrationRedirectFlag'] = 1;
    $_SESSION['RegistrationErrorFlag'] = 1;
    header('Location: ../todo_registration.html');
    exit;
  // エラーがない場合
  } else if($errorFlag == 0) {
    // 確認合格フラグをセッションに保存
    $_SESSION['RegistrationCheckPassFlag'] = 1;
    // エラーフラグのセッション情報を解放し、確認画面へ遷移
    unset($_SESSION['RegistrationErrorFlag']);
    header('Location: ../todo_registration_confirm.html');
    exit;
  // その他何かしらのエラーが発生した場合  
  } else {
    // エラー処理を実行
    $temporaryTodo->setErrorProcedure();
    // リダイレクトフラグとエラーフラグをセッションに保存し、ToDo登録画面に戻る
    $_SESSION['RegistrationRedirectFlag'] = 1;
    $_SESSION['RegistrationErrorFlag'] = 1;
    header('Location: ../todo_registration.html');
    exit;
  }
?>
