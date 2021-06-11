<?php
//Include de 2 bestanden die wij nodig hebben
include_once(__DIR__ . "/../_models/AttributeModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");

//Maak een class aan voor deze api call
class AttributeList {
  public static function run(){
    session_start();
    //Start een connectie
    MysqlHelper::startConnection();

    // Checking session
    if(!isset($_SESSION['username'])){
      http_response_code(401);
      echo "Session doesn't exist";
      exit;
    }
    if(!isset($_GET["id_goal"])){
      header('Content-Type: application/json');
      $response = ["message" => "id_goal is required"];
      http_response_code(400);
      echo json_encode($response);
      exit;
    }

    $goalId = $_GET["id_goal"];

    $attributes = AttributeModel::listByUsername($_SESSION['username'], $goalId);
    header('Content-Type: application/json');
    echo json_encode($attributes);

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
AttributeList::run();