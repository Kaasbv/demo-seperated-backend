<?php
//Include de 2 bestanden die wij nodig hebben
include_once(__DIR__ . "/../_models/UserModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");

//Maak een class aan voor deze api call
class UserRegister {

  public static function run(){
    session_start();
        
    MysqlHelper::startConnection();
    
    $email = $_POST['email'];
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $birthdate = ''.$_POST['birthdate'].'';
    $password = $_POST['password'];
    if(!isset($POST['middlename'])){
      $middlename = "";
    }
    else{
      $middlename = $_POST['middlename'];
    }
    var_dump($email);
    $mailexists = userModel::checkIfMailExists($email);
    $userexists = userModel::checkIfUserExists($username);
    if($mailexists === true || $userexists === true){
      MysqlHelper::closeconnection();
    }
    if($mailexists === true && $userexists === true){
      $data = ["msg" => 'failure', "username" => $username, "email" => $email, "firstname" => $firstname, "lastname" => $lastname, 'execution' => 'User and mail already exist'];
      echo json_encode($data);
    }
    elseif($mailexists === true){
      $data = ["msg" => 'failure', "username" => $username, "email" => $email, "firstname" => $firstname, "lastname" => $lastname, 'execution' => 'Mail already exists'];
      echo json_encode($data);
    }
    elseif($userexists === true){
      $data = ["msg" => 'failure', "username" => $username, "email" => $email, "firstname" => $firstname, "lastname" => $lastname, 'execution' => 'User already exists'];
      echo json_encode($data);
    }
    else{
    $password = password_hash($password, PASSWORD_DEFAULT);

    //Doe dingen
    $user = new UserModel($firstname, $middlename, $lastname);
    $user->email = $email;
    $user->birthdate = $birthdate;
    $user->password = $password;
    $user->username = $username;

    $user->create();
    $data = ["msg" => 'success', "username" => $username, "email" => $email, "firstname" => $firstname, "lastname" => $lastname, 'execution' => 'New user created'];
    //Geef een response

    http_response_code(200); //Zet een http code Heel belangrijk!
    echo json_encode($data); // echo de data array in json formaat voor de frontend
    
    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
  }
}

//Run de run functie hiervan
UserRegister::run();