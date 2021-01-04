<?php
  // セッションスタート
  session_start();

  // セッション情報を破棄
  $_SESSION = array();

  // ログイン画面に遷移
  header('Location: ../user_login/03_01_user_login.php');
  exit;

?>