<?php 
  // phpファイルの読み込み
  require_once('manager_registration_class.php');

  // 管理者情報
  $managerID = 'manager01';
  $managerPassword = 'Manager01';

  // 管理者のインスタンスを作成
  $manager = new ManagerRegistration($managerID, $managerPassword);

  // 管理者の登録を実行
  $manager->managerRegistrationExecute();
?>