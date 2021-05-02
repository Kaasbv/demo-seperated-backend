<?php
//Include de 2 bestanden die wij nodig hebben
// include_once(__DIR__ . "/../_models/ProductModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");


//Maak een class aan voor deze api call
class UserGet {
  public static function run(){
    session_start();
    //Start een connectie
    MysqlHelper::startConnection();

    //Doe dingen
    $data = ["error" => "Jemoeder"];
    //Geef een response
    http_response_code(500); //Zet een http code Heel belangrijk!
    echo json_encode($data); // echo de data array in json formaat voor de frontend

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
UserGet::run();