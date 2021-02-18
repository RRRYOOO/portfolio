<?php 
  // phpファイルの読み込み
  require_once('user_registration_class.php');

  // セッションスタート
  session_start();

  // 前回の入力値で作成したインスタンスを受け取る(入力チェック済み)
  $temporaryUser = $_SESSION['RegistrationTempUser'];

  // 前回の入力値でユーザ登録を実行;
  $executionCompFlag = $temporaryUser->userRegistrationExecute();

  // ユーザ登録の実行が失敗した場合
  if($executionCompFlag == 0){
    // リダイレクトフラグとエラーフラグをセッションに保存し、ユーザ登録画面に戻る
    $_SESSION['RegistrationRedirectFlag'] = 1;
    $_SESSION['RegistrationErrorFlag'] = 1;
    // ユーザ登録画面へ遷移する
    header('Location: ../user_registration.html');
    exit;
  // ユーザ登録の実行が成功した場合
  } else if($executionCompFlag == 1){
    // ユーザ登録に使用したセッション情報を破棄
    $_SESSION = array();
    // phpファイルの読み込み
    require_once('../user_login/present_login_user_class.php');
    // 登録したユーザ情報をログインユーザ情報として設定
    $loginUser = new PresentLoginUser($temporaryUser->getTempUserMailAddress());
    // ログインユーザ情報をセッションに保存
    $_SESSION['LoginUser'] = $loginUser;
    // ToDo表示画面へ遷移
    header('Location: ../todo_show.html');
    exit;
    // その他何かしらのエラーが発生した場合  
  } else {
    // エラー処理を実行
    $temporaryUser->setErrorProcedure();
    // リダイレクトフラグとエラーフラグをセッションに保存し、ユーザ登録画面に戻る
    $_SESSION['RegistrationRedirectFlag'] = 1;
    $_SESSION['RegistrationErrorFlag'] = 1;
    header('Location: ../user_registration.html');
    exit;
  }
  
?>