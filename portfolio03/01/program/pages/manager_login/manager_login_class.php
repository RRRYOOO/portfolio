<?php
  // phpファイルの読み込み
  require_once('../database_connect/registered_manager_data_class.php');

  // 入力された管理者ログイン情報のチェックを行うクラスを定義
  class ManagerLogin {
    // 管理者登録情報に関する変数
    private $tempManagerID;
    private $tempManagerPassword;
    private $tempManagerPasswordHashed;
    // 接続するデータベースの情報
    private $dbh;
    // エラーメッセージに関する変数
    public $errorMessageManagerID;
    public $errorMessagePassword;
    public $errorMessageManagerIDPasswordMatch;
    public $errorMessageLogin;

    
  //****************************************
  // コンストラクタ
  //****************************************

    public function __construct($tempManagerID, $tempManagerPassword){
      // 登録画面で入力されたデータを取得
      $this->tempManagerID = $tempManagerID;
      $this->tempManagerPassword = $tempManagerPassword;
    }    

    
  //****************************************
  // 入力された管理者登録情報を返す
  //****************************************

    // 管理者IDを返す
    public function getTempManagerID() {
      return $this->tempManagerID;
    }


  //****************************************
  // 入力情報の確認
  //**************************************** 

    // 管理者ログイン情報が正しく入力されているか確認
    public function loginCheck() {
      // エラーフラグの初期化
      $errorFlag = 0;
      // 管理者IDが正しく入力されているか確認
      if(!empty($this->managerIDCheck())) {
        $errorFlag = 1;
      }
      // パスワードが正しく入力されているか確認
      if(!empty($this->managerPasswordCheck())) {
        $errorFlag = 1;
      }
      // 管理者IDとパスワードの入力が正しい場合
      if($errorFlag == 0) {
        // 管理者IDとパスワードがデータベースの登録情報と一致するか確認
        if(!empty($this->managerIDPasswordMatchCheck())) {
          $errorFlag = 1;
        }
      }
      return $errorFlag;
    }

    // 管理者IDが正しく入力されているか確認
    public function managerIDCheck() {
      // エラーメッセージの初期化
      $this->errorMessageManagerID = null;
      // 管理者IDの入力が空でないか確認
      if(empty($this->tempManagerID)) {
        $this->errorMessageManagerID = '管理者IDが入力されていません。';
        // 管理者IDが登録されているか確認
      } else if(empty(RegisteredManagerData::getManagerID($this->tempManagerID))) {
        $this->errorMessageManagerID = '入力された管理者IDの登録データがありません。';
        $this->tempManagerID = null;
      }
      // エラーメッセージを返す
      return $this->errorMessageManagerID;
    }

    // パスワードが正しく入力されているか確認
    public function managerPasswordCheck() {
      // エラーメッセージの初期化
      $this->errorMessagePassword = null;
      // パスワードの入力が空でないか確認
      if (empty($this->tempManagerPassword)) {
        $this->errorMessagePassword = 'パスワードが入力されていません。';
      }
      // エラーメッセージを返す
      return $this->errorMessagePassword;
    }

    // 管理者IDとパスワードがデータベースの登録情報と一致するか確認
    public function managerIDPasswordMatchCheck() {
      // エラーメッセージの初期化
      $this->errorMessageManagerIDPasswordMatch = null;
      // 入力された管理者IDで登録されている管理者のパスワード(暗号化)を取り出す
      $this->tempManagerPasswordHashed = RegisteredManagerData::getManagerPasswordHashed($this->tempManagerID);
      // 取り出したパスワード(暗号化)と入力されたパスワードを暗号化したものが一致するか確認する
      if(password_verify($this->tempManagerPassword, $this->tempManagerPasswordHashed) != 1) {
        $this->errorMessageManagerIDPasswordMatch = 'パスワードが間違っています。';
        $this->tempManagerPassword = null;
      }
      // エラーメッセージを返す
      return $this->errorMessageManagerIDPasswordMatch;
    }

    // エラー発生時の処理を実行
    public function setErrorProcedure() {
      // エラーメッセージを設定
      $this->errorMessageRegistration = "エラーが発生しました。もう一度ログインしてください。";
      // 管理者ログイン情報を破棄
      $this->tempManagerID = null;
      $this->tempManagerPassword = null;
      $this->tempManagerPasswordHashed = null;
      // エラーメッセージを破棄
      $this->errorMessageManagerID = null;
      $this->errorMessagePassword = null;
      $this->errorMessageManagerIDPasswordMatch = null;
    }

  }
?>
