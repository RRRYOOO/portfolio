<?php 
  // phpファイルの読み込み
  require_once('manager_login_class.php');

  // セッションスタート
  session_start();

  // 管理者ログイン画面で入力されたユーザ登録情報を取得
  $tempManagerID = $_POST['ManagerID'];
  $tempManagerPassword = $_POST['Password'];
 
  // 入力された管理者ログイン情報を基にインスタンスを生成
  $temporaryManager = new ManagerLogin($tempManagerID, $tempManagerPassword);
  
  // 管理者ログイン情報が正しく入力されているか確認
  $errorFlag = $temporaryManager->loginCheck();

  // エラーがある場合
  if($errorFlag == 1) {
    // 作成したインスタンスとリダイレクトフラグとエラーフラグをセッションに保存し、管理者ログイン画面に戻る
    $_SESSION['LoginTempManager'] = $temporaryManager;
    $_SESSION['ManagerLoginRedirectFlag'] = 1;
    $_SESSION['ManagerLoginErrorFlag'] = 1;
    header('Location: ../pages/manager_login.html');
    exit;
  // エラーがない場合
  } else if($errorFlag == 0) {
    // ログインに使用したセッション情報を破棄
    $_SESSION = array();
    // phpファイルの読み込み
    require_once('present_login_manager_class.php');
    // 確認した管理者情報をログイン管理者情報として設定
    $loginManager = new PresentLoginManager($temporaryManager->getTempManagerID());
    // ログイン管理者情報をセッションに保存
    $_SESSION['LoginManager'] = $loginManager;
    // ToDo全表示画面へ遷移
    header('Location: ../pages/todo_show_all.html');
    exit;
  // その他何かしらのエラーが発生した場合
  } else {
    // エラー処理を実行
    $temporaryUser->setErrorProcedure();
    // リダイレクトフラグとエラーフラグをセッションに保存し、ユーザ登録画面に戻る
    $_SESSION['ManagerLoginRedirectFlag'] = 1;
    $_SESSION['ManagerLoginErrorFlag'] = 1;
    header('Location: ../pages/manager_login.html');
    exit;
  }
?>
