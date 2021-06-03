<?php
//Include de bestanden de wij nodig hebben
include_once(__DIR__ . "/../_models/Model.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");

class AttributeModel extends Model {
  public function __construct(
    public string $name,
    public int $ID_goal
    ){}
    public string $content = "";
    public string $date_created;
    public string $date_updated;

  public function create(){
    $query = "
      INSERT INTO Attributes
      (name, ID_Goal, content)
      VALUES(?, ?, ?);
    ";

    MysqlHelper::runPreparedQuery($query, [
      $this->name,
      $this->ID_goal,
      $this->content,
    ], ["s", "s", "s"]);
  }

  public function update(){
    $query = "
      UPDATE Attributes
      SET content = ?
      WHERE name = ? AND ID_Goal = ?
    ";

    MysqlHelper::runPreparedQuery($query, [
      $this->content,
      $this->name,
      $this->ID_goal,
    ], ["s", "s", "s"]);
  }

  public function delete(){
    $query = "
      DELETE FROM Attributes
      WHERE name = ? AND ID_Goal = ?
    ";
    MysqlHelper::runPreparedQuery($query, [
      $this->name,
      $this->ID_goal,
    ], ["s", "i"]);
  }

  public static function listByUsername(string $username, int $goalId){
    $query = "
      SELECT a.* FROM Attributes a
      INNER JOIN Goal g on g.ID_goal = a.ID_goal
      WHERE g.username = ? and g.ID_goal = ?
    ";

    $response = MysqlHelper::runPreparedQuery($query, [$username, $goalId], ["s", "i"]);
    
    $data = [];

    foreach($response as $row){
      $object = new AttributeModel($row["name"], $row["ID_goal"]);
      self::fillObject($object, $row);

      $data[] = $object;
    }

    return $data;
  }

  public static function getByGoalIdNameAndUsername(int $ID_goal, string $name, string $username){
    $query = "
      SELECT a.* FROM Attributes a
      INNER JOIN Goal g on g.ID_goal = a.ID_goal
      WHERE a.ID_goal = ? AND a.name = ? AND g.username = ?
    ";

    $response = MysqlHelper::runPreparedQuery($query, [
      $ID_goal,
      $name,
      $username
    ], ["i", "s", "s"]);
    if(empty($response)) return false;

    [$data] = $response;

    $object = new AttributeModel($data["name"], $data["ID_goal"]);
    self::fillObject($object, $data);

    return $object;
  }
}

?>
