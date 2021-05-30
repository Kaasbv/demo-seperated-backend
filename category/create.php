<?php
//Include de 2 bestanden die wij nodig hebben
include_once(__DIR__ . "/../_models/CategoryModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");

//Maak een class aan voor deze api call
class CategoryCreate {
  public static function run(){
    session_start();
    //Start een connectie
    MysqlHelper::startConnection();

    //check of user (niet) is ingelogd
    if(!isset($_SESSION['username'])) {
      http_response_code(403);
      echo "Session doesn't exist";
      exit;
    }

    //maak een nieuwe instantie aan van de class (middels de __construct method)
    $object = new CategoryModel($_POST['name'], $_SESSION['username']);
    //spreek de create functie aan in deze class
    $object->create();

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
CategoryCreate::run();