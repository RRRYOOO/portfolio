<?php
  // セッションスタート
  session_start();

  // セッション情報を破棄
  $_SESSION = array();

  // スタート画面に遷移
  header('Location: ../start_page/index.html');
  exit;

?>