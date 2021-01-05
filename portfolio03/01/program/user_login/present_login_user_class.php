<?php
  // phpファイルの読み込み
  require_once('../database_connect/registered_user_data_class.php');

  // 現在ログイン中のユーザの情報を管理するクラスを定義
  class PresentLoginUser
  {
    // ユーザ情報に関する変数
    private $UserID;
    private $RegistrationDate;
    private $UserLastName;
    private $UserFirstName;
    private $UserMailAddress;
    private $UserGender;
    private $UserGenderJapanese;
    private $UserAge;
    private $UserDepartmentID;
    private $UserDepartment;

  //****************************************
  // コンストラクタ
  //****************************************

    public function __construct($mailAddress){
      // 渡されたメールアドレスで登録されているユーザの情報を現在ログイン中のユーザの情報として設定する(パスワードの情報は設定しない)
      $this->UserID = RegisteredUserData::getUserID($mailAddress);
      $this->RegistrationDate = RegisteredUserData::getRegistrationDate($mailAddress);
      $this->UserLastName = RegisteredUserData::getUserLastName($mailAddress);
      $this->UserFirstName = RegisteredUserData::getUserFirstName($mailAddress);
      $this->UserMailAddress = RegisteredUserData::getUserMailAddress($mailAddress);
      $this->UserGender = RegisteredUserData::getUserGender($mailAddress);
      $this->UserGenderJapanese = RegisteredUserData::getUserGenderJapanese($mailAddress);
      $this->UserAge = RegisteredUserData::getUserAge($mailAddress);
      $this->UserDepartmentID = RegisteredUserData::getUserDepartmentID($mailAddress);
      $this->UserDepartment = RegisteredUserData::getUserDepartment($mailAddress);
    }


  //****************************************
  // 現在ログイン中のユーザの情報を返す
  //****************************************

    // ユーザIDを返す
    public function getUserID() {
      return $this->UserID;
    }

    // 登録日を返す
    public function getRegistrationDate() {
      return $this->RegistrationDate;
    }

    // 姓を返す
    public function getUserLastName() {
      return $this->UserLastName;
    }

    // 名を返す
    public function getUserFirstName() {
      return $this->UserFirstName;
    }

    // メールアドレスを返す
    public function getUserMailAddress() {
      return $this->UserMailAddress;
    }

    // 性別('man'or'woman')を返す
    public function getUserGender() {
      return $this->UserGender;
    }

    // 性別('男'or'女')を返す
    public function getUserGenderJapanese() {
      return $this->UserGenderJapanese;
    }
    
    // 年齢を返す
    public function getUserAge() {
      return $this->UserAge;
    }

    // 部署IDを返す
    public function getUserDepartmentID() {
      return $this->UserDepartmentID;
    }

    // 部署を返す
    public function getUserDepartment() {
      return $this->UserDepartment;
    }

  }
?>