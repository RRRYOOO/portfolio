<?php
  // phpファイルの読み込み
  require_once('../user_login/03_01_present_login_user_class.php');
  
  // セッションスタート
  session_start();

  // ログアウト状態の場合、ログイン画面へ遷移（ログアウト状態のユーザがアクセスしてきた場合を想定）
  if(empty($_SESSION['LoginUser'])) {
    // ログイン画面へ遷移
    header('Location: ../user_login/03_01_user_login.php');
    exit;
  }

  // ログインユーザに関するセッション情報を取得
  $loginUser = $_SESSION['LoginUser'] ;

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
    <script src="https://kit.fontawesome.com/284bd436b5.js" crossorigin="anonymous"></script>
</head>
<body>
  <header>
    <div class="header_wrapper">
      <div class="header_left">
        <a href="../user_login/03_01_user_logout.php"><span class="header_contents">Logout</span></a>
      </div>
      <div class="header_center">
        <span class="site_title">ToDoList</span>
      </div>
      <div class="header_right">
        <span class="header_contents">header_right</span>
      </div>
    </div>    
  </header>
  <div class="main">
    <div class="main_wrapper">
      <div class="main_message">
        <span>main_message</span>
      </div>
      <div class="main_title">
        <span>ToDo一覧</span>
      </div>
      <div class="main_contents">
        <?php
          echo '<p>ユーザID：'.$loginUser->getUserId().'</p>';
          echo '<p>登録日：'.$loginUser->getRegistrationDate().'</p>';
          echo '<p>姓：'.$loginUser->getUserLastName().'</p>';
          echo '<p>名：'.$loginUser->getUserFirstName().'</p>';
          echo '<p>メールアドレス：'.$loginUser->getUserMailAddress().'</p>';
          echo '<p>性別(英語)：'.$loginUser->getUserGender().'</p>';
          echo '<p>性別(日本語)：'.$loginUser->getUserGenderJapanese().'</p>';
          echo '<p>年齢：'.$loginUser->getUserAge().'</p>';
          echo '<p>部署ID：'.$loginUser->getUserDepartmentID().'</p>';
          echo '<p>部署：'.$loginUser->getUserDepartment().'</p>';
        ?>
      </div>
    </div>
  </div>
  <footer>
    <div class="footer_wrapper">
      <div class="footer_left">
        <span class="footer_contents">footer_left</span>
      </div>
      <div class="footer_center">
        <span class="site_inc">© Portfolio .inc</span>
      </div>
      <div class="footer_right">
        <span class="footer_contents">footer_right</span>
      </div>
    </div>
  </footer>
</body>
</html>