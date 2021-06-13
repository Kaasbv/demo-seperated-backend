<?php
//Maak de benodigde includes
include_once(__DIR__ . "/../_models/UserModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");

//Maak een class aan
class UserUpload {
  public static function run(){
    session_start();
    //Als de user session niet bestaat, response code 401
    if(!isset($_SESSION["username"])){
      http_response_code(401);
      exit;
    }

    //Haal uit de session de gebruikersnaam van de ingelogde gebruiker
    $user = $_SESSION["username"];

    //Maak willekeurige string
    $randomstring = bin2hex(random_bytes(30));

    //Variabelen voor afbeelding type en grootte
    $imagefiletype = $_FILES["fileToUpload"]['type'];
    $imagesize = $_FILES["fileToUpload"]['size'];

    //Check de grootte van de afbeelding
    if ($imagesize > 5000000) {
      http_response_code(400);
      echo "Sorry, your 'file' is too large ;)";

    //Check het bestandstype van de afbeelding
    } elseif($imagefiletype !== "gif" || $imagefiletype !== "png" || $imagefiletype !== "jpeg") {
      http_response_code(400);
      echo "Sorry, you used a trash image format <br/><br/>Please upload a gif, png or jpeg instead :)";

    } else {
    //Maak locatie voor de file aan
    $target_dir = "uploads/images/";
    $target_file = $target_dir . $randomstring . "_profile.$imagefiletype";
    
    //Schrijf de image weg naar schijf
      $image = $_FILES["fileToUpload"]["tmp_name"];
      move_uploaded_file($image, $target_file);

      //Start een connectie
      MysqlHelper::startConnection();
      $usermodel = UserModel::getByUserName($user);
      $usermodel->uploadImage($target_file);
      MysqlHelper::closeConnection();
      echo 'Uploaded';
    }

  }
}

//Run de run functie hiervan
UserUpload::run();