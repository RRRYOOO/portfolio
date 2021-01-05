<?php
  // セッションスタート
  session_start();

  // セッション情報を破棄
  $_SESSION = array();

  // ログイン画面に遷移
  header('Location: ../user_login/user_login.html');
  exit;

?>