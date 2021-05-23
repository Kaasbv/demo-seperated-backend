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
}

?>
