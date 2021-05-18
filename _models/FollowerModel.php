<?php
//Include de bestanden de wij nodig hebben
include_once(__DIR__ . "/../_models/Model.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");

class FollowerModel extends Model {
  
  public function __construct(
    public string $username,
    public string $username_following
  ){}
  public string $date_created;
  

  public static function getByUsernames($username, $username_following) {
    $query = "
        SELECT * FROM Followers
        WHERE username = ?
        AND username_following = ?
    ";

    $response = MysqlHelper::runPreparedQuery($query, [$username, $username_following], ["s", "s"]);
    if(empty($response)) return false;
    [$data] = $response;
    
    $object = new FollowerModel($data["username"], $data["username_following"]);
    $object->date_created = $data["date_created"];

    return $object;
  }
  public static function listByUsername($username) {
    $query = "
        SELECT * FROM Followers
        WHERE username = ?
    ";

    $response = MysqlHelper::runPreparedQuery($query, [$username], ["s"]);
    if(empty($response)) return false;

    $objectArray = [];
    foreach  ($response as $row) {
      $object = new FollowerModel($row["username"], $row["username_following"]);
      $object->date_created = $row["date_created"];

      $objectArray[] = $object;


    }

    return $objectArray;


  }
}

?>
