<?PHP
require_once(__DIR__ . "/../goal/calculator.php");
require_once(__DIR__ . "/../_models/GoalModel.php");
require_once(__DIR__ . "/../_helpers/MysqlHelper.php");

//Maak een class aan voor deze api call
class GoalUpdate extends Calculator{
  //Maak onderscheid tussn vereiste en optionele attributen.
  static array $requiredKeys = ["ID_goal"];
  static array $nonRequiredKeys = ["name", "type", "status", "end_date"];


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

    //Checken of er een goal_id is
    if(!isset($_POST["ID_goal"]))
    {
      http_response_code(400);
      echo "ID_goal is required, no ID_goal given!";
      exit;
    }

    //check if a key is not allowed
    $allKeys = array_merge(self::$requiredKeys, self::$nonRequiredKeys);
    foreach($_POST as $key => $value){
      if(!in_array($key, $allKeys)){
        $response = ["message" => "This key is not allowed: {$key}"];
        http_response_code(400);
        echo json_encode($response);
        exit;
      }
    }

    //Model ophalen en checken of ID_goal bestaad
    $goal_edit = GoalModel::getByGoalIDByUsername($_POST['ID_goal'], $_SESSION['username']);

    //Checken of het opgehaalde model valide is
    if(!$goal_edit){
      http_response_code(404);
      echo 'hiero';
      exit;
    }

    //checken of een post-variabele leeg is, zo ja verwijderen uit array
    foreach($_POST as $key => $value)
    {
      if(is_null($value) || empty($value)) unset($_POST[$key]);
    }
    
    //Bij het voltooien van een goal de kudos bereken
    if($_POST["status"] === "done" && $goal_edit->status === "todo")
    {
      $quantityParents = $goal_edit->getTotalParents();
      $quantityChilds = $goal_edit->getTotalChilderen();

      //kudos bereken
      $kudos = self::calculateParentPosition($quantityParents) * self::calculateChildCluster($quantityChilds);

      if($kudos === 0) $kudos = 10; 

      //kudos zetten
      $goal_edit->kudos = $kudos;

      //date finished zetten
      $goal_edit->date_finished = date("Y-m-d H:i:s"); 
    }

    //Aangepaste waarde editen in het opgehaalde model
    GoalModel::fillObject($goal_edit, $_POST);


    //Goal updaten
    $goal_edit->update();
  
    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
GoalUpdate::run();
