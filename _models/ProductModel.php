<?php
//Include de bestande de wij nodig hebben
require_once(__DIR__ . "/../_helpers/MysqlHelper.php");

class ProductModel {
  public string $productCode;
  public string $name;
  public string $amount;


  function __construct($productCode, $name, $amount){//Constructor die ProductModel aanmaakt
    $this->productCode = $productCode;
    $this->name = $name;
    $this->amount = $amount;
  }

  public static function list(){
    //Run de query via de mysqlhelper
    $query = "SELECT * FROM Products";
    $data = MysqlHelper::runQuery($query);

    //Verander de array van objecten in een array van ProductModels door de constructor aan te roepen met de data
    $objectArray = [];
    foreach ($data as $row) {//Loop door de data verkregen van de database
      //Maak het Productmodel aan
      $object = new ProductModel(
        $row["product_code"],
        $row["name"],
        $row["amount"]
      );
      //Voeg het toe aan de array
      $objectArray[] = $object; 
  }
    //return de array
    return $objectArray;
  }
}