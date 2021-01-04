<?php
  // phpファイルの読み込み
  require_once('../database_connect/03_01_database_connect.php');
  require_once('03_01_user_registeration_class.php');
  
  // セッションスタート
  session_start();

  // ログイン状態の場合、ToDo表示画面へ遷移（ログイン状態のユーザがアクセスしてきた場合を想定）
  if(!empty($_SESSION['LoginUser'])) {
    // ToDo表示画面へ遷移
    header('Location: ../todo_show/03_01_todo_show.php');
    exit;
  }

  // ログイン画面の入力情報で作成したセッション情報を破棄（ログイン画面から遷移してきたことを想定）
  unset($_SESSION['LoginTempUser'], $_SESSION['LoginRedirectFlag'], $_SESSION['LoginErrorFlag']);

  // データベースに接続
  $dbh = DatabaseConnect::getDbh();
  // 部署データを取得
  $sql = 'SELECT * FROM department';
  $departments = $dbh->query($sql);

  // リダイレクトかどうか判断
  $redirectFlag = 0;
  // リダイレクトの場合
  if($_SESSION['RegistrationRedirectFlag'] == 1) {
    // リダイレクトフラグの値を受け取る
    $redirectFlag = $_SESSION['RegistrationRedirectFlag'];
    // 前回の入力値で作成したインスタンスを受け取る
    $temporaryUser = $_SESSION['RegistrationTempUser'];
    // エラーによるリダイレクトかどうかを判断
    $errorFlag = 0;
    // エラーによるリダイレクトの場合
    if($_SESSION['RegistrationErrorFlag'] == 1) {
      // エラーフラグの値を受け取る
      $errorFlag = $_SESSION['RegistrationErrorFlag'];
    }
  // リダイレクトでない場合
  } else {
    // 前回の入力値で作成したインスタンスのセッション情報を破棄
    unset($_SESSION['RegistrationTempUser']);
    // ダミーの入力データでインスタンスを作成
    // ※メソッドの呼び出しでエラーが発生し停止するのを防ぐため
    $temporaryUser = new UserRegistration(null,null,null,null,null,null,null);
  }
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
    <link rel="stylesheet" href="03_01_user_registeration.css">
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
        <?php
          if($errorFlag == 1) {            
            echo '<p>'.$temporaryUser->errorMessageLastName.'</p>';
            echo '<p>'.$temporaryUser->errorMessageFirstName.'</p>';
            echo '<p>'.$temporaryUser->errorMessageMailAddress.'</p>';
            echo '<p>'.$temporaryUser->errorMessagePassword.'</p>';
            echo '<p>'.$temporaryUser->errorMessageGender.'</p>';
            echo '<p>'.$temporaryUser->errorMessageAge.'</p>';
            echo '<p>'.$temporaryUser->errorMessageDepartment.'</p>';
            echo '<p>'.$temporaryUser->errorMessageRegistration.'</p>';
          }
        ?>
      </div>
      <div class="main_title">
        <span>ユーザ登録</span>
      </div>
      <div class="main_contents">
        <div class="main_container">
          <!-- 入力フォーム -->
          <form method="post" action="03_01_user_registeration_check.php">
            <div class="name">
              <!-- 姓を入力 -->
              <div class="input_name">
                <span class="input_item">姓</span>
                <!-- エラーによりリダイレクトされた場合は、前回入力値を表示（次項目がエラーだった場合はクリアされる） -->
                <input class="input_short" name="LastName" value="<?php echo $temporaryUser->getTempUserLastName() ?>">
              </div>
              <!-- 名を入力 -->
              <div class="input_name">
                <span class="input_item">名</span>
                <!-- エラーによりリダイレクトされた場合は、前回入力値を表示（次項目がエラーだった場合はクリアされる） -->
                <input class="input_short" name="FirstName" value="<?php echo $temporaryUser->getTempUserFirstName() ?>">
              </div>
            </div>
            <!-- メールアドレスを入力 -->
            <div class="input_contents">
              <span class="input_item">メールアドレス</span>
                <!-- エラーによりリダイレクトされた場合は、前回入力値を表示（次項目がエラーだった場合はクリアされる） -->
              <input class="input_long" name="MailAddress" value="<?php echo $temporaryUser->getTempUserMailAddress() ?>">
            </div>
            <!-- パスワードを入力 -->
            <div class="input_contents">
              <span class="input_item">パスワード</span>
              <!-- 前回の入力値をクリア -->
              <input class="input_long" name="Password" type="password">
            </div>
            <!-- 性別を選択 -->
            <div class="input_contents">
              <span class="input_item">性別</span>
              <select class="input_short" name="Gender">
                <!-- エラーによりリダイレクトされた場合は、前回選択値を表示（次項目がエラーだった場合はクリアされる） -->
                <option value=""></option>
                <option value="man" <?php if($temporaryUser->getTempUserGender() == '男') { echo 'selected'; } ?>>男</option>
                <option value="woman" <?php if($temporaryUser->getTempUserGender() == '女') { echo 'selected'; } ?>>女</option>
              </select>
            </div>
            <!-- 年齢を選択 -->
            <div class="input_contents">
              <span class="input_item">年齢</span>
              <select class="input_short" name="Age">
                <!-- エラーによりリダイレクトされた場合は、前回選択値を表示（次項目がエラーだった場合はクリアされる） -->
                <option value=""></option>
                <?php
                  for($i = 16; $i <= 70; $i++) {
                    if($temporaryUser->getTempUserAge() == $i) {
                      echo "<option value=".$i." selected> ".$i."歳</option>";  
                    } else {
                      echo "<option value=".$i."> ".$i."歳</option>";
                    }
                  }
                ?>
              </select>
            </div>
            <!-- 部署を選択 -->
            <div class="input_contents">
              <span class="input_item">部署</span>
              <select class="input_short" name="Department">
                <!-- エラーによりリダイレクトされた場合は、前回選択値を表示（次項目がエラーだった場合はクリアされる） -->
                <option value=""></option>
                <?php
                  foreach($departments as $row) {
                    if($temporaryUser->getTempUserDepartment() == $row['department']) {
                      echo '<option value="'.$row['id'].'" selected>'.$row['department'].'</option>';
                    } else {
                      echo '<option value="'.$row['id'].'">'.$row['department'].'</option>';
                    }
                  }
                ?>
              </select>
            </div>
            <!-- 登録ボタン -->
            <div class="register_input">
              <input class="register_button" type="submit" value="登録">
            </div>
          </form>
          <!-- ログイン画面へのリンク -->
          <div class="link_to_login">
            <a href="../user_login/03_01_user_login.php"><span class="link">※既にアカウントをお持ちの場合はこちら</span></a>
          </div>
        </div>
        <!-- スタート画面へのリンク -->
        <div class="link_to_start">
          <a href=""><span class="link">スタート画面へ戻る</span></a>
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


<?php
  // 前回の入力情報で作成したインスタンスのセッション情報を破棄し、ダミーのインスタンスを作成
  // ※メソッドの呼び出しでエラーが発生し停止するのを防ぐため
  $_SESSION['RegistrationTempUser'] = new UserRegistration(null,null,null,null,null,null,null);
  // リダイレクトフラグのセッション情報を破棄
  unset($_SESSION['RegistrationRedirectFlag']);
  // エラーフラグのセッション情報を破棄
  unset($_SESSION['RegistrationErrorFlag']);
  // 確認合格フラグのセッション情報を破棄
  unset($_SESSION['RegistrationCheckPassFlag']);
?>