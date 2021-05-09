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
    if (!isset($user)){
      echo "incorrect";
      return false;
    }
    $response = $user->checkPassword($password);
    if ($response === true){
      echo "Succes!";
      $_SESSION['email'] = $POST['user'];
      http_response_code(200);
    }
    else if ($response === false ){
      http_response_code(400);
      echo "incorrect password";

    }
    //Geef een response
    http_response_code(500); //Zet een http code Heel belangrijk!
    $data = ["error" => "Jemoeder"];
    echo json_encode($data); // echo de data array in json formaat voor de frontend
    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
UserLogin::run();