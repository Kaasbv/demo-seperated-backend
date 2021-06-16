<?php
//Include de 2 bestanden die wij nodig hebben
require_once(__DIR__ . "/../_models/GoalModel.php");
require_once(__DIR__ . "/../_helpers/MysqlHelper.php");


//Maak een class aan voor deze api call
class GoalSearch {
  public static function run(){
    //Start een connectie
    MysqlHelper::startConnection();
    session_start();
    
    if(!isset($_SESSION['username'])){
      http_response_code(401);
      echo "Session doesn't exist";
      exit;
    }

    $search = $_GET['search'];

    $goals = GoalModel::search($search, $_SESSION['username']);

    header('Content-Type: application/json');
    echo json_encode($goals);

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
GoalSearch::run();