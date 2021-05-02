<?php
//Include de 2 bestanden die wij nodig hebben
include_once(__DIR__ . "/../_models/ProductModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");


//Maak een class aan voor deze api call
class ProductList {
  public static function run(){
    //Start een connectie
    MysqlHelper::startConnection();

    //Haal de models op via de statische functie van de model, verander het in json en echo het
    header('Content-Type: application/json');
    $products = ProductModel::list();
    echo json_encode($products);

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
ProductList::run();