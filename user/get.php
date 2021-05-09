<?php
//Include de 2 bestanden die wij nodig hebben
include_once(__DIR__ . "/../_models/Model.php");
include_once(__DIR__ . "/../_models/UserModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");


//Maak een class aan voor deze api call
class UserGet {
  public static function run(){
    session_start();
    if(!isset($_SESSION['email'])) {
      http_response_code(403);
      echo "Session doesn't exist";
      exit;
    }
    
    //Start een connectie
    MysqlHelper::startConnection();

    //Doe dingen
    header('Content-Type: application/json');
    $data = UserModel::getByEmail($_SESSION["email"]);
    //Geef een response
    http_response_code(200); //Zet een http code Heel belangrijk!
    echo json_encode($data); // echo de data array in json formaat voor de frontend

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
UserGet::run();