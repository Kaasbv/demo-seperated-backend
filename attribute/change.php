<?php
include_once(__DIR__ . "/../_models/AttributeModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");

class AttributeChange {
  public static function run(){
    session_start();
    //Open a connection
    MysqlHelper::startConnection();

    // checking
    if(!isset($_SESSION['username'])){
      http_response_code(401);
      echo "Session doesn't exist";
      exit;
    }
    
    if(!isset($_POST["name"]) || !isset($_POST["id_goal"]) || !isset($_POST["content"])){
      $response = ["message" => "id_goal, content and name are required"];
      http_response_code(400);
      echo json_encode($response);
      exit;
    }

    $name = $_POST["name"];
    $goalId = $_POST["id_goal"];
    $content = $_POST["content"];

    //Get attribute by input
    $attributeModel = AttributeModel::getByGoalIdNameAndUsername($goalId, $name, $_SESSION["username"]);
    if(!$attributeModel){
      $response = ["message" => "Attribute not found"];
      http_response_code(404);
      echo json_encode($response);
      exit;
    }

    //Update the content
    $attributeModel->content = $content;
    $attributeModel->update();

    //Close connection
    MysqlHelper::closeConnection();
  }
}

AttributeChange::run();