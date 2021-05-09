<?php
//Include de 2 bestanden die wij nodig hebben
// include_once(__DIR__ . "/../_models/ProductModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");
include_once(__DIR__ . "/../_models/UserModel.php");

//Maak een class aan voor deze api call
class UserLogin {
  public static function run(){
    session_start();
    //Start een connectie
    MysqlHelper::startConnection();

    //Doe dingen
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = UserModel::getByEmail($email);
    if (!$user){
      http_response_code(400);
      echo "incorrect";
      return false;
    }
    $response = $user->checkPassword($password);
    if ($response){
      http_response_code(200);
      echo "Succes!";
      $_SESSION['username'] = $user->username;
    }
    else {
      http_response_code(400);
      echo "incorrect";
    }

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
UserLogin::run();