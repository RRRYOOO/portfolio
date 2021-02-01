<?php
  // phpファイルの読み込み
  require_once('../database_connect/registered_manager_data_class.php');

  // 現在ログイン中の管理者の情報を管理するクラスを定義
  class PresentLoginManager
  {
    // ユーザ情報に関する変数
    private $managerID;
    private $registrationDate;

    
  //****************************************
  // コンストラクタ
  //****************************************

    public function __construct($managerID){
      // 渡された管理者IDで登録されている管理者の情報を現在ログイン中の管理者の情報として設定する(パスワードの情報は設定しない)
      $this->managerID = RegisteredManagerData::getManagerID($managerID);
      $this->registrationDate = RegisteredManagerData::getRegistrationDate($managerID);
    }


  //****************************************
  // 現在ログイン中のユーザの情報を返す
  //****************************************

    // 管理者IDを返す
    public function getManagerID() {
      return $this->managerID;
    }

    // 登録日を返す
    public function getRegistrationDate() {
      return $this->registrationDate;
    }

  }
?>