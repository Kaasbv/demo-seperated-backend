<?php
//Include de bestanden de wij nodig hebben
require_once(__DIR__ . "/../_models/Model.php");
require_once(__DIR__ . "/../_helpers/MysqlHelper.php");

class GoalModel extends Model {
  
  public function __construct(
    public string $username,
    public string $name,
    public string $type
  ){}

  public int $ID_goal;
  public int $parent_goal_id = 1; //Default oppergoal
  public int $kudos = 10;
  public string $status = "todo";
  public string $start_date = "1970-1-1";
  public string $end_date;
  public string $date_created;
  public string $date_updated;
  public string $date_finished;

  public function create(){
    $query = "
      INSERT INTO Goal
      (username, parent_goal_id, name, kudos, `type`, status, start_date, end_date)
      VALUES(?, ?, ?, ?, ?, ?, ?, ?);    
    ";

    MysqlHelper::runPreparedQuery($query, [
      $this->username,
      $this->parent_goal_id,
      $this->name,
      $this->kudos,
      $this->type,
      $this->status,
      $this->start_date,
      $this->end_date
    ], ["s", "i", "s", "i", "s", "s", "s", "s"]);
  }

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

  //Een variatie van getByGoalId om te voorkomen dat niet iemand anders zijn goals worden bewerkt.
  public static function getByGoalIdByUsername($ID_goal, $username){
    $query = "
      SELECT * FROM Goal
      WHERE ID_goal = ? AND username = ?
    ";

    $response = MysqlHelper::runPreparedQuery($query, [$ID_goal, $username], ["s", "s"]);
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
      $queryValues[] = intval($filters["parent_id"]);
      $queryTypes[] = "i";
    }
    
    if(isset($filters["min_end_date"])){
      $whereStatements[] = "end_date >= ?";
      $queryValues[] = $filters["min_end_date"];
      $queryTypes[] = "s";
    }

    if(isset($filters["max_end_date"])){
      $whereStatements[] = "end_date <= ?";
      $queryValues[] = $filters["max_end_date"];
      $queryTypes[] = "s";
    }

    if(isset($filters["min_date_finished"])){
      $whereStatements[] = "date_finished >= ?";
      $queryValues[] = $filters["min_date_finished"];
      $queryTypes[] = "s";
    }

    if(isset($filters["max_date_finished"])){
      $whereStatements[] = "date_finished <= ?";
      $queryValues[] = $filters["max_date_finished"];
      $queryTypes[] = "s";
    }

    if(isset($filters["status"])){
      $whereStatements[] = "status = ?";
      $queryValues[] = $filters["status"];
      $queryTypes[] = "s";
    }

    if(isset($filters["type"])){
      $whereStatements[] = "type = ?";
      $queryValues[] = $filters["type"];
      $queryTypes[] = "s";
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


  public static function search($search, $username){
    $search = "%" . $search . "%";

    $query = "
      SELECT * FROM Goal
      WHERE name LIKE ? AND username = ? 
    ";

    $results = MysqlHelper::runPreparedQuery($query, [$search, $username], ["s", "s"]);

    $models = [];

    foreach($results as $row){
      $model = new GoalModel($row["username"], $row["name"], $row["type"] ?? "");
      self::fillObject($model, $row);

      array_push($models, $model);
    }

    return $models;
  }

  //Update een goal
  public function update(){
    $query = "
      UPDATE Goal 
      SET `name` = ?, `kudos` = ?, `type` = ?, `end_date` = ?, `status` = ?
      WHERE `ID_goal` = ?;  
    ";

    MysqlHelper::runPreparedQuery($query, [
      $this->name,
      $this->kudos,
      $this->type,
      $this->end_date,
      $this->status,
      $this->ID_goal
    ], ["s", "i", "s", "s", "s", "i"]);
  }
}

?>
