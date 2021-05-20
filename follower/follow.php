<?php
//Include de 2 bestanden die wij nodig hebben
include_once(__DIR__ . "/../_models/FollowerModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");

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
    header('Content-Type: application/json');
    $data = UserModel::getByUsername($_SESSION["username"]);
    //$username = $_POST[$_SESSION['username']];
    //$username_following = $_POST[$_SESSION['username_following']];
    //$sql = "INSERT INTO goalr.Followers (username, username_following, date_created) VALUES ("$username","$username_following","$date_created")";
    //$query = $db->prepare($sql);

    http_response_code(204);
    echo "username_following is now followed."

    //$query->execute();

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
FollowerFollow::run();