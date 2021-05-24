<?php
//Include de 2 bestanden die wij nodig hebben
include_once(__DIR__ . "/../_models/CategoryModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");
include_once(__DIR__ . "/../_models/UserModel.php");

//Maak een class aan voor deze api call
class CategoryList {
  public static function run(){
    session_start();
    //Start een connectie
    MysqlHelper::startConnection();

    //Checken of sessie (niet)bestaat
    if(!isset($_SESSION['username'])){
      http_response_code(403);
      echo "Session doesn't exist";
      exit;
    }

    header('Content-Type: application/json');
    $user = UserModel::getByUsername($_SESSION['username']);
    $categories = $user->listCategory(); 

    foreach($categories as $category){
      $names[] = $category->name;
    }

    echo json_encode($names);

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
CategoryList::run();