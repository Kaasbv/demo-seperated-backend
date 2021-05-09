<?php
//Include de 2 bestanden die wij nodig hebben
include_once(__DIR__ . "/../_models/FollowerModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");

//Maak een class aan voor deze api call
class FollowerList {
  public static function run(){
    session_start();
    //Start een connectie
    MysqlHelper::startConnection();

    //Zet hier je code neer
    header('Content-Type: application/json'); //Header om aan te geven dat de response json is

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
FollowerList::run();