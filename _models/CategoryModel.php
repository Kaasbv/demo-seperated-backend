<?php
//Include de bestanden de wij nodig hebben
include_once(__DIR__ . "/../_models/Model.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");

class CategoryModel extends Model {
  
  public function __construct(
    public string $name,
    public string $username
  ){}
  public string $date_created;
  public string $date_updated;


  public static function listByUsername($username){
    //SQL query om usernames van Category table te halen
    $category_query = "SELECT * FROM `Category` WHERE `username` = ?";

    $response = MysqlHelper::runPreparedQuery($category_query, [$username], ["s"]);
    if(empty($response)) return false;

    foreach($response as $data){
      
      $object = $data;
    }
    

    $object = new CategoryModel($data["username"], $data["name"]);
    self::fillObject($object, $data);
    /*foreach($response as $row){
      $object = new CategoryModel($row['username'], $row['name']);
      $data[] = $object;
    }*/

    return $object;
  }
}

?>
