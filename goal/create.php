<?php
//Include de 2 bestanden die wij nodig hebben
require_once(__DIR__ . "/../_models/GoalModel.php");
require_once(__DIR__ . "/../_helpers/MysqlHelper.php");

//Maak een class aan voor deze api call
class GoalCreate {
  static array $requiredKeys = ["name", "type", "end_date"];
  static array $nonRequiredKeys = ["parent_goal_id", "kudos", "start_data"];

  public static function run(){
    session_start();
    if(!isset($_SESSION['username'])) {
      http_response_code(401);
      echo "Session doesn't exist";
      exit;
    }
    
    //Start een connectie
    MysqlHelper::startConnection();

    //Header om aan te geven dat de response json is
    header('Content-Type: application/json');
    
    //check input for required keys
    foreach(self::$requiredKeys as $requiredKey){
      if(!isset($_POST[$requiredKey])){
        $response = ["message" => "Missing a required key of " . implode(", ", self::$requiredKeys)];
        http_response_code(400);
        echo json_encode($response);
        exit;
      }
    }

    //check if a key is not allowed
    $allKeys = array_merge(self::$requiredKeys, self::$nonRequiredKeys);
    foreach($_POST as $key => $value){
      if(!in_array($key, $allKeys)){
        $response = ["message" => "Key is not allowed bro: {$key}"];
        http_response_code(400);
        echo json_encode($response);
        exit;
      }
    }
    
    $goal = new GoalModel($_SESSION["username"], $_POST["name"], $_POST["type"] ?? "todo");
    GoalModel::fillObject($goal, $_POST);
    $goal->create();
    

    echo json_encode($goal);

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
GoalCreate::run();