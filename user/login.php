<?php
//Include de 2 bestanden die wij nodig hebben
// include_once(__DIR__ . "/../_models/ProductModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");


//Maak een class aan voor deze api call
class UserLogin {
  public static function run(){
    session_start();
    //Start een connectie
    MysqlHelper::startConnection();

    echo $_POST["username"]; // echo de data array in json formaat voor de frontend

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
UserLogin::run();