<?php
//Include de 2 bestanden die wij nodig hebben
include_once(__DIR__ . "/../_models/GoalModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");

//Maak een class aan voor deze api call
class GoalList {
  public static function run(){
    session_start();
    if(!isset($_SESSION['username'])) {
      http_response_code(403);
      echo "Session doesn't exist";
      exit;
    }
    
    //Start een connectie
    MysqlHelper::startConnection();
    
    $goals = GoalModel::listByUsername($_SESSION["username"], $_GET);
    if(!$goals) $goals = [];
    $tree = self::generateTree($goals);

    header('Content-Type: application/json'); //Header om aan te geven dat de response json is
    echo json_encode($tree);

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }

  static function generateTree(array $goals){
    /*
      Because a tree exists of multiple branchings and because
      its easy to determine them (just look at the corresponding parent)
      We start bij grouping the goals by parent id in the branchPieces array.
      This we we have all the children of a certain parent already grouped.
    */
    $branchPieces = [];
    
    foreach($goals as $goal){//Loop through all goals to start grouping them by parent id
      $parentId = $goal->parent_goal_id; //Save parent id 
      if(!isset($branchPieces[$parentId])) $branchPieces[$parentId] = []; //If parent id key doesnt exist yet in the branchpieces array create it
      $branchPieces[$parentId][] = $goal; //Push the goal to the array
    }
    
    return self::buildBranch($branchPieces, 1); //Start building the branches from the top branch which is 1
  }

  static function buildBranch($branchPieces, $parentId){
    $branch = []; //Intialize an empty (sub)branche
    $branchGoals = $branchPieces[$parentId] ?? []; //Grab a branch based on the given parent Id 
    
    /*
      Loop through all the goals and check if they
      have children. This way we know if we need to 
      create a new branche. If so we just call the same function
      but with a different parent_id. This is called *recursion*.
    */

    foreach($branchGoals as $goal){
      if(isset($branchPieces[$goal->ID_goal])){//Check if has children or as we can also call it if a branchpiece exists
        $goal->items = self::buildBranch($branchPieces, $goal->ID_goal); //If so build a (sub)branche based on the goal id
      }
      $branch[] = $goal; //Add the goal to the (sub)branche array
    }

    return $branch; //return the (sub)branche
  }
}

//Run de run functie hiervan
GoalList::run();