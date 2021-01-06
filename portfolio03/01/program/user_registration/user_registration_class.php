<?php
  // phpファイルの読み込み
  require_once('../database_connect/database_connect.php');
  require_once('../database_connect/department_data_class.php');
  require_once('../database_connect/registered_user_data_class.php');

  // 入力されたユーザ登録情報のチェックとユーザ登録を行うクラスを定義
  class UserRegistration {
    // ユーザ登録情報に関する変数
    private $tempUserLastName;
    private $tempUserFirstName;
    private $tempUserMailAddress;
    private $tempUserPassword;
    private $tempUserGender;
    private $tempUserAge;
    private $tempUserDepartment;
    // 接続するデータベースの情報
    private $dbh;
    // エラーメッセージに関する変数
    public $errorMessageLastName;
    public $errorMessageFirstName;
    public $errorMessageMailAddress;
    public $errorMessagePassword;
    public $errorMessageGender;
    public $errorMessageAge;
    public $errorMessageDepartment;
    public $errorMessageRegistration;
    // メールアドレスに適合可能な文字の種類
    private $adaptableMailaAddressLetters = '/\A[a-zA-Z0-9@._-]+\z/';
    // メールアドレスに適合可能な文字列パターン
    private $adaptableMailaAddressPattern = '/\A([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+\z/';
    // パスワードに適合可能な文字の種類
    private $adaptablePasswordLetters = '/\A[a-zA-Z0-9!"#$%&()*+,.:;<=>?@[\]^_`{|}~-]+\z/';
    // パスワードに適合可能な文字列パターン
    private $adaptablePasswordPattern = '/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])[a-zA-Z0-9!"#$%&()*+,.:;<=>?@[\]^_`{|}~-]+\z/';
    // ユーザ登録に使用する変数
    private $tempUserPasswordHashed;
    private $userRegistrationExecuteCompFlag;
    

  //****************************************
  // コンストラクタ
  //****************************************

    public function __construct($tempUserLastName, $tempUserFirstName, $tempUserMailAddress, $tempUserPassword, $tempUserGender, $tempUserAge, $tempUserDepartment){
      // 登録画面で入力されたデータを取得
      $this->tempUserLastName = $tempUserLastName;
      $this->tempUserFirstName = $tempUserFirstName;
      $this->tempUserMailAddress = $tempUserMailAddress;
      $this->tempUserPassword = $tempUserPassword;
      $this->tempUserGender = $tempUserGender;
      $this->tempUserAge = $tempUserAge;
      $this->tempUserDepartment = $tempUserDepartment;
    }    

  //****************************************
  // データベースへの接続
  //**************************************** 

    private function connectToDatabase() {
    // データベースへの接続開始
    try {
      $this->dbh = DatabaseConnect::getDbh();
    } catch (PDOException $e) {
      print('接続失敗:' . $e->getMessage());
      die();
    }
    return;
  }

  //****************************************
  // 入力されたユーザ登録情報を返す
  //****************************************

    // 姓を返す
    public function getTempUserLastName() {
      return $this->tempUserLastName;
    }

    // 名を返す
    public function getTempUserFirstName() {
      return $this->tempUserFirstName;
    }

    // メールアドレスを返す
    public function getTempUserMailAddress() {
      return $this->tempUserMailAddress;
    }

    // パスワードを返す（頭文字だけ表示して残りは'*'に変換する）
    public function getTempUserPassword() {
      // パスワードの頭文字を取得
      $passwordCovered = substr($this->tempUserPassword, 0, 1);
      // パスワードの長さを取得
      $passwordLength = strlen($this->tempUserPassword);
      // 頭文字+'*'(パスワードの長さ-1個)にする
      for($i = 1; $i < $passwordLength; $i++) {
        $passwordCovered .= '*';
      }
      return $passwordCovered;
    }
    
    // 性別を返す(男or女で返す)
    public function getTempUserGender() {
      if($this->tempUserGender == 'man') {
        return '男';
      } else if($this->tempUserGender == 'woman') {
        return '女';
      }
    } 

    // 年齢を返す
    public function getTempUserAge() {
      return $this->tempUserAge;
    }

    // 部署を返す(部署名で返す)
    public function getTempUserDepartment() {
      return DepartmentData::exchangeIDToDepartment($this->tempUserDepartment);
    }    


  //****************************************
  // 入力情報の確認
  //**************************************** 

    // 登録情報が正しく入力されているか確認
    public function inputDataCheck() {
      // エラーフラグの初期化
      $errorFlag = 0;
      // 姓の確認
      if(!empty($this->userLastNameCheck())) {
        $errorFlag = 1;
      }
      // 名の確認
      if(!empty($this->userFirstNameCheck())) {
        $errorFlag = 1;
      }
      // メールアドレスの確認
      if(!empty($this->userMailAddressCheck())) {
        $errorFlag = 1;
      }
      // パスワードの確認
      if(!empty($this->userPasswordCheck())) {
        $errorFlag = 1;
      }
      // 性別の確認
      if(!empty($this->userGenderCheck())) {
        $errorFlag = 1;
      }
      // 年齢の確認
      if(!empty($this->userAgeCheck())) {
        $errorFlag = 1;
      }
      // 部署の確認
      if(!empty($this->userDepartmentCheck())) {
        $errorFlag = 1;
      }
      // エラーフラグを返す
      return $errorFlag;
    }

    // 姓が正しく入力されているか確認
    public function userLastNameCheck() {
      // エラーメッセージの初期化
      $this->errorMessageLastName = null;
      // 姓の入力が空でないか確認
      if(empty($this->tempUserLastName)) {
        $this->errorMessageLastName = '姓が入力されていません。';
      // 姓の入力が20文字より長くないか確認
      } else if(mb_strlen($this->tempUserLastName, 'UTF-8') > 20) {
        $this->errorMessageLastName = '姓は20文字以下にしてください。';
        $this->tempUserLastName = null;
      }
      // エラーメッセージを返す
      return $this->errorMessageLastName;
    }

    // 名が正しく入力されているか確認
    public function userFirstNameCheck() {
      // エラーメッセージの初期化
      $this->errorMessageFirstName = null;
      // 名の入力が空でないか確認
      if(empty($this->tempUserFirstName)) {
        $this->errorMessageFirstName = '名が入力されていません。';
      // 名の入力が20文字より長くないか確認
      } else if(mb_strlen($this->tempUserFirstName, 'UTF-8') > 20) {
        $this->errorMessageFirstName = '名は20文字以下にしてください。';
        $this->tempUserFirstName = null;
      }
      // エラーメッセージを返す
      return $this->errorMessageFirstName;
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
      // メールアドレスが既に登録されていないか確認
      } else if(!empty(RegisteredUserData::getUserMailAddress($this->tempUserMailAddress))) {
        $this->errorMessageMailAddress = '入力されたメールアドレスは既に使われています。';
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
      // メールアドレスの形式が正しいか確認
      } else if(!preg_match($this->adaptablePasswordPattern, $this->tempUserPassword)){
        $this->errorMessagePassword = 'パスワードは数字と英字の大文字と英字の小文字をそれぞれ1種類以上含むようにしてください。';
        $this->tempUserPassword = null;
      }
      // エラーメッセージを返す
      return $this->errorMessagePassword;
    }

    // 性別が正しく選択されているか確認
    public function userGenderCheck() {
      // エラーメッセージの初期化
      $this->errorMessageGender = null;      
      // 性別の選択が空でないか確認
      if(empty($this->tempUserGender)) {
        $this->errorMessageGender = '性別が選択されていません。';
      // 性別の選択が男または女かどうか確認
      } else if(!(($this->tempUserGender == "man") || ($this->tempUserGender == "woman"))) {
        $this->errorMessageGender = '性別の選択が有効ではありません。';
        $this->tempUserGender = null;
      }
      // エラーメッセージを返す
      return $this->errorMessageGender;
    }  

    // 年齢が正しく選択されているか確認
    public function userAgeCheck() {
      // エラーメッセージの初期化
      $this->errorMessageAge = null;      
      // 年齢の選択が空でないか確認
      if(empty($this->tempUserAge)) {
        $this->errorMessageAge = '年齢が選択されていません。';
      // 年齢の選択が有効かどうか確認
      } else if(!(($this->tempUserAge >= 16) && ($this->tempUserAge <= 70))) {
        $this->errorMessageAge = '年齢の選択が有効ではありません。';
        $this->tempUserAge = null;
      }
      // エラーメッセージを返す
      return $this->errorMessageAge;
    }  

    // 部署が正しく選択されているか確認
    public function userDepartmentCheck() {
      // エラーメッセージの初期化
      $this->errorMessageDepartment = null;
      // 部署の選択が空でないか確認
      if(empty($this->tempUserDepartment)) {
        $this->errorMessageDepartment = '部署が選択されていません。';
      // 部署の選択が有効かどうか確認
      } else if(!(($this->tempUserDepartment < DepartmentData::getMinDepartmentID())||($this->tempUserDepartment > DepartmentData::getMaxDepartmentID()))) {
        $this->errorMessageDepartment = '部署の選択が有効ではありません。';
        $this->tempUserDepartment = null;
      }
      // エラーメッセージを返す
      return $this->errorMessageDepartment;
    }
  
    
  //****************************************
  // ユーザ登録
  //**************************************** 

    // パスワードを暗号化する
    private function passwordHashing() {
      // パスワードを暗号化
      $tempUserPasswordHashed = password_hash($this->tempUserPassword, PASSWORD_DEFAULT);
      // 暗号化したパスワードを返す
      return $tempUserPasswordHashed;
    }

    // エラー発生時の処理を実行
    public function setErrorProcedure() {
      // エラーメッセージを設定
      $this->errorMessageRegistration = "エラーが発生しました。もう一度登録してください。";
      // ユーザ登録情報を破棄
      $this->tempUserLastName = null;
      $this->tempUserFirstName = null;
      $this->tempUserMailAddress = null;
      $this->tempUserPassword = null;
      $this->tempUserPasswordHashed = null;
      $this->tempUserGender = null;
      $this->tempUserAge = null;
      $this->tempUserDepartment = null;
      // エラーメッセージに関する変数
      $this->errorMessageLastName = null;
      $this->errorMessageFirstName = null;
      $this->errorMessageMailAddress = null;
      $this->errorMessagePassword = null;
      $this->errorMessageGender = null;
      $this->errorMessageAge = null;
      $this->errorMessageDepartment = null;
    }

    // ユーザ登録を実行する
    public function userRegistrationExecute() {
      // 登録完了フラグの初期化
      $this->userRegistrationExecuteCompFlag = null;
      // 念のために再度入力情報の確認を実施
      if($this->inputDataCheck() == 0) {
        // データベースに接続
        $this->connectToDatabase();
        // パスワードを暗号化
        $this->tempUserPasswordHashed = $this->passwordHashing();
        // 登録日をセット（日本時間）
        date_default_timezone_set('Asia/Tokyo');
        $registrationDate = date('Y-m-d H:i:s');
        // ユーザ登録を実行
        $sql = 'INSERT INTO users (registration_date, user_lastname, user_firstname, user_mailaddress, user_password, user_gender, user_age, user_department_id) 
        VALUES (:registration_date, :user_lastname, :user_firstname, :user_mailaddress, :user_password, :user_gender, :user_age, :user_department_id);';
        $sth = $this->dbh->prepare($sql);
        $sth->bindParam(':registration_date', $registrationDate, PDO::PARAM_STR);
        $sth->bindParam(':user_lastname', $this->tempUserLastName, PDO::PARAM_STR);
        $sth->bindParam(':user_firstname', $this->tempUserFirstName, PDO::PARAM_STR);
        $sth->bindParam(':user_mailaddress', $this->tempUserMailAddress, PDO::PARAM_STR);
        $sth->bindParam(':user_password', $this->tempUserPasswordHashed, PDO::PARAM_STR);
        $sth->bindParam(':user_gender', $this->tempUserGender, PDO::PARAM_STR);
        $sth->bindParam(':user_age', $this->tempUserAge, PDO::PARAM_INT);
        $sth->bindParam(':user_department_id', $this->tempUserDepartment, PDO::PARAM_INT);
        $sth->execute();
        unset($this->dbh);
        // 登録が完了したか確認（登録したメールアドレスを読み出すことができるか確認）
        if(!empty(RegisteredUserData::getUserMailAddress($this->tempUserMailAddress))) {
          $this->userRegistrationExecuteCompFlag = 1;
        }
      }
      // ユーザ登録の実行が失敗した場合
      if(is_null($this->userRegistrationExecuteCompFlag)) {
        // エラー処理
        $this->setErrorProcedure();
      }
      // 登録完了フラグを返す
      return $this->userRegistrationExecuteCompFlag;
    }
    
  }
?>
