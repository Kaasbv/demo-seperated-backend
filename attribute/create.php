<?php
include_once(__DIR__ . "/../_models/AttributeModel.php");
include_once(__DIR__ . "/../_models/GoalModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");

class AttributeCreate {
  public static function run(){
    session_start();
    //Start a connection
    MysqlHelper::startConnection();

    // check of session
    if(!isset($_SESSION['username'])){
      self::returnError("Session doesn't exist", 401);
    }

    //Check 
    if(!isset($_POST["name"]) || !isset($_POST["id_goal"])){
      self::returnError("id_goal and name are required", 400);
    }

    $name = $_POST["name"];
    $goalId = $_POST["id_goal"];

    //Check if goal is from user
    $goal = GoalModel::getByGoalId($goalId);

    if(!$goal || $goal->username !== $_SESSION["username"]){
      self::returnError("Goal not found!", 404);
    }

    //Create attribute
    $attributeModel = new AttributeModel($name, $goalId);

    try{
      $attributeModel->create();
    }catch(Exception $error){
      self::returnError($error->getMessage(), 500);
    }

    //Close connection
    MysqlHelper::closeConnection();
  }

  static function returnError($message, $code){
    $response = ["message" => $message];
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
  }
}

//Run de run functie hiervan
AttributeCreate::run();