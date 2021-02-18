<?php
  // phpファイルの読み込み
  require_once('../database_connect/registered_user_data_class.php');

  // 現在ログイン中のユーザの情報を管理するクラスを定義
  class PresentLoginUser
  {
    // ユーザ情報に関する変数
    private $userID;
    private $registrationDate;
    private $userLastName;
    private $userFirstName;
    private $userMailAddress;
    private $userGender;
    private $userGenderJapanese;
    private $userAge;
    private $userDepartmentID;
    private $userDepartment;

  //****************************************
  // コンストラクタ
  //****************************************

    public function __construct($mailAddress){
      // 渡されたメールアドレスで登録されているユーザの情報を現在ログイン中のユーザの情報として設定する(パスワードの情報は設定しない)
      $this->userID = RegisteredUserData::getUserID($mailAddress);
      $this->registrationDate = RegisteredUserData::getRegistrationDate($mailAddress);
      $this->userLastName = RegisteredUserData::getUserLastName($mailAddress);
      $this->userFirstName = RegisteredUserData::getUserFirstName($mailAddress);
      $this->userMailAddress = RegisteredUserData::getUserMailAddress($mailAddress);
      $this->userGender = RegisteredUserData::getUserGender($mailAddress);
      $this->userGenderJapanese = RegisteredUserData::getUserGenderJapanese($mailAddress);
      $this->userAge = RegisteredUserData::getUserAge($mailAddress);
      $this->userDepartmentID = RegisteredUserData::getUserDepartmentID($mailAddress);
      $this->userDepartment = RegisteredUserData::getUserDepartment($mailAddress);
    }


  //****************************************
  // 現在ログイン中のユーザの情報を返す
  //****************************************

    // ユーザIDを返す
    public function getUserID() {
      return $this->userID;
    }

    // 登録日を返す
    public function getRegistrationDate() {
      return $this->registrationDate;
    }

    // 姓を返す
    public function getUserLastName() {
      return $this->userLastName;
    }

    // 名を返す
    public function getUserFirstName() {
      return $this->userFirstName;
    }

    // メールアドレスを返す
    public function getUserMailAddress() {
      return $this->userMailAddress;
    }

    // 性別('man'or'woman')を返す
    public function getUserGender() {
      return $this->userGender;
    }

    // 性別('男'or'女')を返す
    public function getUserGenderJapanese() {
      return $this->userGenderJapanese;
    }
    
    // 年齢を返す
    public function getUserAge() {
      return $this->userAge;
    }

    // 部署IDを返す
    public function getUserDepartmentID() {
      return $this->userDepartmentID;
    }

    // 部署を返す
    public function getUserDepartment() {
      return $this->userDepartment;
    }

  }
?>