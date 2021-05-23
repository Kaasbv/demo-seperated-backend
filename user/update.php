<?php
//Include de 2 bestanden die wij nodig hebben
include_once(__DIR__ . "/../_models/UserModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");

//Maak een class aan voor deze api call
class UserUpdate {
  public static function run(){

    //Zet gebruikersnaam ingelogde gebruiker in variabele
    $user = $_SESSION['username'];
    //Check of de sessie bestaat
    if(!isset($user)) {
      http_response_code(403);
      echo "Session doesn't exist";
      exit;
    }

    //Start een connectie
    MysqlHelper::startConnection();

    $usermodel = UserModel::getByUserName($user);

  $possibleKeys = ["email", "username", "firstname", "middlename", "lastname", "birthdate", "password"];
  foreach($possibleKeys as $key){
    //Als het een wachtwoord is
    if($key === "password"){
      //Hash het wachtwoord en spreek het model aan
      $model->{$key} = password_hash($_POST[$key],PASSWORD_DEFAULT);
    }
    //spreek het model aan
    elseif(isset($_POST[$key])) $model->{$key} = $_POST[$key];
  } 

  $model->update();
  
  //Sluit de connectie
  MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
UserUpdate::run();