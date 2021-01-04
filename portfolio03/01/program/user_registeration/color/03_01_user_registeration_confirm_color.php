<?php
  //phpファイルの読み込み
  require_once('03_01_user_registeration_check_class.php');

  //セッションスタート
  session_start();


  if($_POST['RedirectFlag'] == 1){
    $_SESSION['RedirectFlag'] = 1;
    header('Location: ./03_01_user_registeration.php');
  }

  //前回の入力値で作成したインスタンスを受け取る(入力チェック済み)
 // $temporaryUser = $_SESSION['TemporaryUser'];
  
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- ①文字コード -->
    <meta charset="utf-8">
    <!-- ②ページタイトル -->
    <title>ToDoList</title>
    <!-- ③スタイルシートの読み込み -->
    <link rel="stylesheet" href="../common/03_01_stylesheet_common.css">
    <link rel="stylesheet" href="03_01_user_registeration_confirm_color.css">
    <script src="https://kit.fontawesome.com/284bd436b5.js" crossorigin="anonymous"></script>
</head>
<body>
  <header>
    <div class="header_wrapper">
      <div class="header_left">
        <span class="header_contents"></span>
      </div>
      <div class="header_center">
        <span class="site_title">ToDoList</span>
      </div>
      <div class="header_right">
        <span class="header_contents"></span>
      </div>
    </div>    
  </header>
  <div class="main">
    <div class="main_wrapper">
      <div class="main_message">
      </div>
      <div class="main_title">
        <span>ユーザ登録</span>
      </div>
      <div class="main_contents">
        <div class="main_container">
          <div class="input_message">
            <p class="input_long">下記の内容で登録してよろしいでしょうか</p>
          </div>   
          <!-- 入力値の確認表示 -->
          <div class="name">
            <!-- 姓の入力値を表示 -->
            <div class="input_name">
              <span class="input_item">姓</span>
              <span class="input_short">XXX</span>
            </div>
            <!-- 名の入力値を表示 -->
            <div class="input_name">
              <span class="input_item">名</span>
              <span class="input_short">XXX</span>
            </div>
          </div>
          <!-- メールアドレスの入力値を表示 -->
          <div class="input_contents">
            <span class="input_item">メールアドレス</span>
            <span class="input_long">XXX@XXX.XXX</span>
          </div>
          <!-- パスワードの入力値を表示-->
          <div class="input_contents">
            <span class="input_item">パスワード</span>
            <span class="input_long">X*********</span>
          </div>
          <!-- 性別の選択値を表示 -->
          <div class="input_contents">
            <span class="input_item">性別</span>
            <span class="input_long">X</span>
          </div>
          <!-- 年齢の選択値を表示 -->
          <div class="input_contents">
            <span class="input_item">年齢</span>
            <span class="input_long">XX歳</span>
          </div>
          <!-- 部署の選択値を表示 -->
          <div class="input_contents">
            <span class="input_item">部署</span>
            <span class="input_long">XXX</span>
          </div>
          <!-- ボタン -->
          <div class="register_input">
            <div class="input_button">
            <!-- 修正ボタン（押すとリダイレクトフラグがポストされ、本プログラムのリダイレクト時に上部のPHP内で受信し、セッションに保存後に登録画面にリダイレクトする） -->
            <form method="post" action="03_01_user_registeration_confirm_color.php">
              <input type="hidden" name="RedirectFlag" value="1">
              <input class="redirect_button"  type="submit" value="修正">
              </form>
            </div>
            <div class="input_button">
              <!-- 確定ボタン (押すとユーザ登録が実行される) -->
              <button class="register_button" onclick="location.href='03_01_user_registeration_execute.php'">確定</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <footer>
    <div class="footer_wrapper">
      <div class="footer_left">
        <span class="footer_contents"></span>
      </div>
      <div class="footer_center">
        <span class="site_inc">© Portfolio .inc</span>
      </div>
      <div class="footer_right">
        <span class="footer_contents"></span>
      </div>
    </div>
  </footer>
</body>
</html>
