<?php
//Include de 2 bestanden die wij nodig hebben
include_once(__DIR__ . "/../_models/FollowerModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");
include_once(__DIR__ . "/../_models/UserModel.php");

//Maak een class aan voor deze api call
class FollowerList {
  public static function run(){
    session_start();
    if(!isset($_SESSION['username'])) {
      http_response_code(403);
      echo "Session doesn't exist";
      exit;
    }
    //Start een connectie
    MysqlHelper::startConnection();

    //Zet hier je code neer
    header('Content-Type: application/json'); //Header om aan te geven dat de response json is
    $user = UserModel::getByUsername($_SESSION["username"]);
    $followers = $user->listFollowers();
    echo json_encode($followers);
    

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
FollowerList::run();