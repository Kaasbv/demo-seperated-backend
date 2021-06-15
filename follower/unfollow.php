<?php
//Include de 2 bestanden die wij nodig hebben
require_once(__DIR__ . "/../_models/FollowerModel.php");
require_once(__DIR__ . "/../_helpers/MysqlHelper.php");

//Maak een class aan voor deze api call
class FollowerUnfollow {
  public static function run(){
    //Connect met sessie
    session_start();

    //Start een connectie
    MysqlHelper::startConnection();
    
    //Bestaad de Sessie?
    if(!isset($_SESSION['username'])) {
      http_response_code(403);
      echo "Session doesn't exist";
      exit;
    }
    
    //Model ophalen
    $myExFriend = FollowerModel::getByUsernames($_SESSION['username'], $_POST['username']);
    
    //Checken of het opgehaalde model valide is
    if(!$myExFriend){
      http_response_code(404);
      exit;
    }

    //verwijder de instantie die overeenkomt met het opgehaalde model en geef een melding.
    $myExFriend->delete();

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
FollowerUnfollow::run();