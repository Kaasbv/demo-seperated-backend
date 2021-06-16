<?php
//Include de 2 bestanden die wij nodig hebben
require_once(__DIR__ . "/../_models/UserModel.php");
require_once(__DIR__ . "/../_helpers/MysqlHelper.php");

//Maak een class aan voor deze api call
class UserGet {
  public static function run(){
    session_start();
    if(!isset($_SESSION['username'])) {
      http_response_code(401);
      echo "Session doesn't exist";
      exit;
    }
    
    //Start een connectie
    MysqlHelper::startConnection();

    //Doe dingen
    header('Content-Type: application/json');
    $data = UserModel::getByUsername($_SESSION["username"]);
    //Geef een response
    http_response_code(200); //Zet een http code Heel belangrijk!
    header('Content-Type: application/json'); //Header om aan te geven dat de response json is
    unset($data->password);
    echo json_encode(get_object_vars($data)); // echo de data array in json formaat voor de frontend

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
UserGet::run();