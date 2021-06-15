<?php
// Include de 2 bestanden die wij nodig hebben
require_once(__DIR__ . "/../_models/AttributeModel.php");
require_once(__DIR__ . "/../_helpers/MysqlHelper.php");

// Maak een class aan voor deze api call
class AttributeDelete {
  public static function run(){
    session_start();
    //start connection
    MysqlHelper::startConnection();

    // check session
    if(!isset($_SESSION['username'])){
      http_response_code(401);
      echo "Session doesn't exist";
      exit;
    }

    //Check input 
    if(!isset($_POST["name"]) || !isset($_POST["id_goal"])){
      header('Content-Type: application/json');
      $response = ["message" => "id_goal and name are required"];
      http_response_code(400);
      echo json_encode($response);
      exit;
    }

    $name = $_POST["name"];
    $goalId = $_POST["id_goal"];

    //Get goal by input
    $attributeModel = AttributeModel::getByGoalIdNameAndUsername($goalId, $name, $_SESSION["username"]);
    
    if(!$attributeModel){
      $response = ["message" => "Attribute not found"];
      http_response_code(404);
      echo json_encode($response);
      exit;
    }
    
    //delete
    $attributeModel->delete();

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
AttributeDelete::run();