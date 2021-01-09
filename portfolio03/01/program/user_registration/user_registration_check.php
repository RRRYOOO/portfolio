<?php 
  // phpファイルの読み込み
  require_once('user_registration_class.php');
  // セッションスタート
  session_start();

  // ユーザ登録画面で入力されたユーザ登録情報を取得
  $tempUserLastName = $_POST['LastName'];
  $tempUserFirstName = $_POST['FirstName'];
  $tempUserMailAddress = $_POST['MailAddress'];
  $tempUserPassword = $_POST['Password'];
  $tempUserGender = $_POST['Gender'];
  $tempUserAge = $_POST['Age'];
  $tempUserDepartment = $_POST['Department'];
 
  // 入力されたユーザ登録情報を基にインスタンスを生成
  $temporaryUser = new UserRegistration($tempUserLastName, $tempUserFirstName, $tempUserMailAddress, $tempUserPassword, $tempUserGender,$tempUserAge, $tempUserDepartment);
  
  // 作成したインスタンスをセッションに保存
  $_SESSION['RegistrationTempUser'] = $temporaryUser;

  // ユーザ登録情報が正しく入力されているか確認
  $errorFlag = $temporaryUser->inputDataCheck();
  // エラーがある場合
  if($errorFlag == 1) {
    // リダイレクトフラグとエラーフラグをセッションに保存し、ユーザ登録画面に戻る
    $_SESSION['RegistrationRedirectFlag'] = 1;
    $_SESSION['RegistrationErrorFlag'] = 1;
    header('Location: ../pages/user_registration.html');
    exit;
  // エラーがない場合
  } else if($errorFlag == 0) {
    // 確認合格フラグをセッションに保存
    $_SESSION['RegistrationCheckPassFlag'] = 1;
    // エラーフラグのセッション情報を解放し、確認画面へ遷移
    unset($_SESSION['RegistrationErrorFlag']);
    header('Location: ../pages/user_registration_confirm.html');
    exit;
  // その他何かしらのエラーが発生した場合  
  } else {
    // エラー処理を実行
    $temporaryUser->setErrorProcedure();
    // リダイレクトフラグとエラーフラグをセッションに保存し、ユーザ登録画面に戻る
    $_SESSION['RegistrationRedirectFlag'] = 1;
    $_SESSION['RegistrationErrorFlag'] = 1;
    header('Location: ../pages/user_registration.html');
    exit;
  }
?>
