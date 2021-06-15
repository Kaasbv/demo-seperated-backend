<?PHP
require_once(__DIR__ . "/../_models/GoalModel.php");
require_once(__DIR__ . "/../_helpers/MysqlHelper.php");

//Maak een class aan voor deze api call
class GoalUpdate {
  //Maak onderscheid tussn vereiste en optionele attributen.
  static array $requiredKeys = [];
  static array $nonRequiredKeys = ["goal_id", "username", "name", "type", "end_date", "kudos" ,"parent_goal_id", "start_data"];


  public static function run()
  {
    //Connect met sessie
    session_start();

    //Start een connectie
    MysqlHelper::startConnection();

    //Bestaad de Sessie?
    if(!isset($_SESSION['username']))
    {
      http_response_code(403);
      echo "Session doesn't exist";
      exit;
    }

    //check if a key is not allowed
    foreach($_POST as $key => $value){
      if(!in_array($key, $allKeys)){
        $response = ["message" => "This key is not allowed: {$key}"];
        http_response_code(400);
        echo json_encode($response);
        exit;
      }
    }

    //Model ophalen
    $goal_edit = GoalModel::getByGoalID($_POST['goal_id']);

    //Checken of het opgehaalde model valide is
    if(!$goal_edit){
      http_response_code(404);
      exit;
    }

    //Aangepaste waarde editen in het opgehaalde model
    $goal_edit->edit()

    //Goal updaten
    $goal_edit->update();
  
    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
GoalUpdate::run();
