<?php
//Include de 2 bestanden die wij nodig hebben
require_once(__DIR__ . "/../_models/FollowerModel.php");
require_once(__DIR__ . "/../_helpers/MysqlHelper.php");

//Maak een class aan voor deze api call
class FollowerFollow {
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
    $follow = new FollowerModel($_SESSION["username"], $_POST["username"]);

    try {
      $follow->create();
    }

    catch (Exception $e) {
      http_response_code(500);
      echo $e->getMessage();
      exit;
    }
  
    http_response_code(204);

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
FollowerFollow::run();