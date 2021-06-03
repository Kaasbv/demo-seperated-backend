<?php
//Include de bestanden de wij nodig hebben
include_once(__DIR__ . "/../_models/Model.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");

class GoalModel extends Model {
  
  public function __construct(
    public string $username,
    public string $name,
    public string $type
  ){}

  public int $ID_goal;
  public int $parent_goal_id = 1; //Default oppergoal
  public string $kudos;
  public string $status = "todo";
  public string $start_date;
  public string $end_date;
  public string $date_created;
  public string $date_updated;

  public static function getByGoalId($ID_goal){
    $query = "
      SELECT * FROM Goal
      WHERE ID_goal = ?
    ";

    $response = MysqlHelper::runPreparedQuery($query, [$ID_goal], ["s"]);
    if(empty($response)) return false;
    [$data] = $response;

    $object = new GoalModel($data["username"], $data["name"], $data["type"] ?? "");
    self::fillObject($object, $data);

    return $object;
  }

  public static function listByUsername($username, $filters) {
    $query = "
        SELECT * FROM Goal
    ";

    $queryValues = [$username];
    $queryTypes = ["s"];

    $whereStatements = ["username = ?"];

    //Check for filters and if so add them
    if(isset($filters["parent_id"])){
      $whereStatements[] = "parent_goal_id = ?";
      $queryValues[] = $filters["parent_id"];
      $queryTypes[] = "i";
    }

    //Check if where is filled and if so create sql for it
    if(!empty($whereStatements)){
      $whereString = implode(" AND ", $whereStatements);
      $query .= "WHERE {$whereString}";
    }

    //Add order
    $query .= " ORDER BY parent_goal_id asc";

    $response = MysqlHelper::runPreparedQuery($query, $queryValues, $queryTypes);
    if(empty($response)) return false;

    //Create object array from data array
    $objectArray = [];
    foreach  ($response as $row) {
      $object = new GoalModel($row["username"], $row["name"], $row["type"] ?? "");
      self::fillObject($object, $row);

      $objectArray[] = $object;
    }

    return $objectArray;
  }
}

?>
