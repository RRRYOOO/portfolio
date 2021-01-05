<?php 
  // phpファイルの読み込み
  require_once('user_login_class.php');
  // セッションスタート
  session_start();

  // ユーザログイン画面で入力されたユーザ登録情報を取得
  $tempUserMailAddress = $_POST['MailAddress'];
  $tempUserPassword = $_POST['Password'];
 
  // 入力されたユーザログイン情報を基にインスタンスを生成
  $temporaryUser = new UserLogin($tempUserMailAddress, $tempUserPassword);
  
  // ユーザログイン情報が正しく入力されているか確認
  $errorFlag = $temporaryUser->loginCheck();
  // エラーがある場合
  if($errorFlag == 1) {
    // 作成したインスタンスとリダイレクトフラグとエラーフラグをセッションに保存し、ユーザログイン画面に戻る
    $_SESSION['LoginTempUser'] = $temporaryUser;
    $_SESSION['LoginRedirectFlag'] = 1;
    $_SESSION['LoginErrorFlag'] = 1;
    header('Location: ./user_login.html');
    exit;
  // エラーがない場合
  } else if($errorFlag == 0) {
    // ログインに使用したセッション情報を破棄
    $_SESSION = array();
    // phpファイルの読み込み
    require_once('present_login_user_class.php');
    // 確認したユーザ情報をログインユーザ情報として設定
    $loginUser = new PresentLoginUser($temporaryUser->getTempUserMailAddress());
    // ログインユーザ情報をセッションに保存
    $_SESSION['LoginUser'] = $loginUser;
    // ToDo表示画面へ遷移
    header('Location: ../todo_show/todo_show.html');
    exit;
  // その他何かしらのエラーが発生した場合
  } else {
    // エラー処理を実行
    $temporaryUser->setErrorProcedure();
    // リダイレクトフラグとエラーフラグをセッションに保存し、ユーザ登録画面に戻る
    $_SESSION['LoginRedirectFlag'] = 1;
    $_SESSION['LoginErrorFlag'] = 1;
    header('Location: ./user_login.html');
    exit;
  }
?>
