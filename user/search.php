<?php
//Include de 2 bestanden die wij nodig hebben
require_once(__DIR__ . "/../_models/UserModel.php");
require_once(__DIR__ . "/../_helpers/MysqlHelper.php");


//Maak een class aan voor deze api call
class UserList {
  public static function run(){
    //Start een connectie
    MysqlHelper::startConnection();

    $search = $_GET['search'];

    $users = UserModel::search($search);

    $usernames = [];
    foreach($users as $user){
      $uname = $user->username;

      array_push($usernames, $uname);
    }

    echo json_encode($usernames);

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
UserList::run();