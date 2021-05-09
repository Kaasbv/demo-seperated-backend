<?php
//Include de 2 bestanden die wij nodig hebben
include_once(__DIR__ . "/../_models/UserModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");

//Maak een class aan voor deze api call
class UserUpdate {
  public static function run(){
    //Start een connectie
    MysqlHelper::startConnection();

    /* Doe dingen */

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
UserUpdate::run();