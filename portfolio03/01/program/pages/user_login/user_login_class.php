<?php
  // phpファイルの読み込み
  require_once('../database_connect/registered_user_data_class.php');

  // 入力されたユーザログイン情報のチェックを行うクラスを定義
  class UserLogin {
    // ユーザ登録情報に関する変数
    private $tempUserMailAddress;
    private $tempUserPassword;
    private $tempUserPasswordHashed;
    // 接続するデータベースの情報
    private $dbh;
    // エラーメッセージに関する変数
    public $errorMessageMailAddress;
    public $errorMessagePassword;
    public $errorMessageMailAddressPasswordMatch;
    public $errorMessageLogin;
    // メールアドレスに適合可能な文字の種類
    private $adaptableMailaAddressLetters = '/\A[a-zA-Z0-9@._-]+\z/';
    // メールアドレスに適合可能な文字列パターン
    private $adaptableMailaAddressPattern = '/\A([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+\z/';
    // パスワードに適合可能な文字の種類
    private $adaptablePasswordLetters = '/\A[a-zA-Z0-9!"#$%&()*+,.:;<=>?@[\]^_`{|}~-]+\z/';
    // パスワードに適合可能な文字列パターン
    private $adaptablePasswordPattern = '/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])[a-zA-Z0-9!"#$%&()*+,.:;<=>?@[\]^_`{|}~-]+\z/';
    

  //****************************************
  // コンストラクタ
  //****************************************

    public function __construct($tempUserMailAddress, $tempUserPassword){
      // 登録画面で入力されたデータを取得
      $this->tempUserMailAddress = $tempUserMailAddress;
      $this->tempUserPassword = $tempUserPassword;
    }    

    
  //****************************************
  // 入力されたユーザ登録情報を返す
  //****************************************

    // メールアドレスを返す
    public function getTempUserMailAddress() {
      return $this->tempUserMailAddress;
    }
    
  //****************************************
  // 入力情報の確認
  //**************************************** 

    // ログイン情報が正しく入力されているか確認
    public function loginCheck() {
      // エラーフラグの初期化
      $errorFlag = 0;
      // メールアドレスが正しく入力されているか確認
      if(!empty($this->userMailAddressCheck())) {
        $errorFlag = 1;
      }
      // パスワードが正しく入力されているか確認
      if(!empty($this->userPasswordCheck())) {
        $errorFlag = 1;
      }
      // メールアドレスとパスワードの入力が正しい場合
      if($errorFlag == 0) {
        // メールアドレスとパスワードがデータベースの登録情報と一致するか確認
        if(!empty($this->userMailAddressPasswordMatchCheck())) {
          $errorFlag = 1;
        }
      }
      return $errorFlag;
    }

    // メールアドレスが正しく入力されているか確認
    public function  userMailAddressCheck() {
      // エラーメッセージの初期化
      $this->errorMessageMailAddress = null;
      // メールアドレスの入力が空でないか確認
      if(empty($this->tempUserMailAddress)) {
        $this->errorMessageMailAddress = 'メールアドレスが入力されていません。';
      // メールアドレスの入力が20文字より長くないか確認
      } else if(mb_strlen($this->tempUserMailAddress, 'UTF-8') > 100) {
        $this->errorMessageMailAddress = 'メールアドレスは100文字以下にしてください。';
        $this->tempUserMailAddress = null;
      // メールアドレスに使用されている文字が正しいか確認
      } else if(!preg_match($this->adaptableMailaAddressLetters, $this->tempUserMailAddress)){
        $this->errorMessageMailAddress = '入力されたメールアドレスに使用できない文字が含まれています。';
        $this->tempUserMailAddress = null;
      // メールアドレスの形式が正しいか確認
      } else if(!preg_match($this->adaptableMailaAddressPattern, $this->tempUserMailAddress)){
        $this->errorMessageMailAddress = '入力されたメールアドレスの形式が正しくありません。';
        $this->tempUserMailAddress = null;
        // メールアドレスが登録されているか確認
      } else if(empty(RegisteredUserData::getUserMailAddress($this->tempUserMailAddress))) {
        $this->errorMessageMailAddress = '入力されたメールアドレスの登録データがありません。';
        $this->tempUserMailAddress = null;
      }
      // エラーメッセージを返す
      return $this->errorMessageMailAddress;
    }

    // パスワードが正しく入力されているか確認
    public function userPasswordCheck() {
      // エラーメッセージの初期化
      $this->errorMessagePassword = null;
      // パスワードの入力が空でないか確認
      if(empty($this->tempUserPassword)) {
        $this->errorMessagePassword = 'パスワードが入力されていません。';
      // パスワードの入力が8文字より短くないか確認
      } else if(mb_strlen($this->tempUserPassword, 'UTF-8') < 8) {
        $this->errorMessagePassword = 'パスワードは8文字以上にしてください。';
        $this->tempUserPassword = null;
      // パスワードの入力が20文字より長くないか確認
      } else if(mb_strlen($this->tempUserPassword, 'UTF-8') > 20) {
        $this->errorMessagePassword = 'パスワードは20文字以下にしてください。';
        $this->tempUserPassword = null;
      // パスワードに使用されている文字が正しいか確認
      } else if(!preg_match($this->adaptablePasswordLetters, $this->tempUserPassword)){
        $this->errorMessagePassword = '入力されたパスワードに使用できない文字が含まれています。';
        $this->tempUserPassword = null;
      // パスワードの形式が正しいか確認
      } else if(!preg_match($this->adaptablePasswordPattern, $this->tempUserPassword)){
        $this->errorMessagePassword = 'パスワードは数字と英字の大文字と英字の小文字をそれぞれ1種類以上含むようにしてください。';
        $this->tempUserPassword = null;
      }
      // エラーメッセージを返す
      return $this->errorMessagePassword;
    }

    // メールアドレスとパスワードがデータベースの登録情報と一致するか確認
    public function userMailAddressPasswordMatchCheck() {
      // エラーメッセージの初期化
      $this->errorMessageMailAddressPasswordMatch = null;
      // 入力されたメールアドレスで登録されているユーザのパスワード(暗号化)を取り出す
      $this->tempUserPasswordHashed = RegisteredUserData::getUserPasswordHashed($this->tempUserMailAddress);
      // 取り出したパスワード(暗号化)と入力されたパスワードを暗号化したものが一致するか確認する
      if(password_verify($this->tempUserPassword, $this->tempUserPasswordHashed) != 1) {
        $this->errorMessageMailAddressPasswordMatch = 'パスワードが間違っています。';
        $this->tempUserPassword = null;
      }
      // エラーメッセージを返す
      return $this->errorMessageMailAddressPasswordMatch;
    }

    // エラー発生時の処理を実行
    public function setErrorProcedure() {
      // エラーメッセージを設定
      $this->errorMessageRegistration = "エラーが発生しました。もう一度ログインしてください。";
      // ユーザログイン情報を破棄
      $this->tempUserMailAddress = null;
      $this->tempUserPassword = null;
      $this->tempUserPasswordHashed = null;
      // エラーメッセージを破棄
      $this->errorMessageMailAddress = null;
      $this->errorMessagePassword = null;
      $this->errorMessageMailAddressPasswordMatch = null;
    }

  }
?>
