<?php echo null /*
  // phpファイルの読み込み
  require_once('../database_connect/03_01_database_connect.php');
  require_once('03_01_user_registeration_class.php');
  
  // セッションスタート
  session_start();

  // データベースに接続
  $dbh = DatabaseConnect::getDbh();
  // 部署データを取得
  $sql = 'SELECT * FROM department';
  $departments = $dbh->query($sql);

  // リダイレクトかどうか判断
  $redirectFlag = 0;
  // リダイレクトの場合
  if($_SESSION['RedirectFlag'] == 1) {
    // リダイレクトフラグの値を受け取る
    $redirectFlag = $_SESSION['RedirectFlag'];
    // 前回の入力値で作成したインスタンスを受け取る
    $temporaryUser = $_SESSION['TemporaryUser'];
    // エラーによるリダイレクトかどうかを判断
    $errorFlag = 0;
    // エラーによるリダイレクトの場合
    if($_SESSION['ErrorFlag'] == 1) {
      // エラーフラグの値を受け取る
      $errorFlag = $_SESSION['ErrorFlag'];
    }
  // リダイレクトでない場合
  } else {
    // 前回の入力値で作成したインスタンスのセッション情報を破棄
    unset($_SESSION['TemporaryUser']);
    // ダミーの入力データでインスタンスを作成
    $temporaryUser = new UserRegistration(null,null,null,null,null,null,null);
  }*/
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
    <link rel="stylesheet" href="03_01_user_login_color.css">
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
        <!-- エラーによりリダイレクトされた場合は、エラーメッセージを表示 -->
        <?php /*
          if($errorFlag == 1) {
            echo '<p>'.$temporaryUser->errorMessageRegistration.'</p>';
            echo '<p>'.$temporaryUser->errorMessageLastName.'</p>';
            echo '<p>'.$temporaryUser->errorMessageFirstName.'</p>';
            echo '<p>'.$temporaryUser->errorMessageMailAddress.'</p>';
            echo '<p>'.$temporaryUser->errorMessagePassword.'</p>';
            echo '<p>'.$temporaryUser->errorMessageGender.'</p>';
            echo '<p>'.$temporaryUser->errorMessageAge.'</p>';
            echo '<p>'.$temporaryUser->errorMessageDepartment.'</p>';
          }*/
        ?>
      </div>
      <div class="main_title">
        <span>ログイン</span>
      </div>
      <div class="main_contents">
        <div class="main_container">
          <!-- 入力フォーム -->
          <form method="post" action="">
            <!-- メールアドレスを入力 -->
            <div class="input_contents">
              <span class="input_item">メールアドレス</span>
                <!-- エラーによりリダイレクトされた場合は、前回入力値を表示（次項目がエラーだった場合はクリアされる） -->
              <input class="input_area" name="MailAddress" value="<?php echo null/*$temporaryUser->getTempUserMailAddress() */?>">
            </div>
            <!-- パスワードを入力 -->
            <div class="input_contents">
              <span class="input_item">パスワード</span>
              <!-- リダイレクト時は前回の入力値をクリア -->
              <input class="input_area" name="Password" type="password">
            </div>
            <!-- ログインボタン -->
            <div class="login_input">
              <input class="login_button" type="submit" value="ログイン">
            </div>
          </form>
          <!-- ログイン画面へのリンク -->
          <div class="link_to_login">
          <a href=""><span class="link">※アカウントをお持ちでない場合はこちら</span></a>
          </div>
        </div>
      </div>
      <!-- スタート画面へのリンク -->
      <div class="link_to_start">
      <a href=""><span class="link">スタート画面へ戻る</span></a>
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


<?php/*
  // リダイレクトフラグのセッション情報を破棄
  unset($_SESSION['RedirectFlag']);
  // エラーフラグのセッション情報を破棄
  unset($_SESSION['ErrorFlag']);
  // 前回の入力情報で作成したインスタンスに関するセッション情報を破棄(ダミーのインスタンスを作成)
  $_SESSION['TemporaryUser'] = new UserRegistration(null,null,null,null,null,null,null);
  // 確認合格フラグのセッション情報を破棄
  unset($_SESSION['CheckPassFlag']);*/
?>