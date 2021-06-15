<?php
//Maak de benodigde includes
require_once(__DIR__ . "/../_models/UserModel.php");
require_once(__DIR__ . "/../_helpers/MysqlHelper.php");

//Maak een class aan
class UserUpload {
  public static function run(){
    session_start();
    //Als de user session niet bestaat, response code 401
    if(!isset($_SESSION["username"])){
      http_response_code(401);
      exit;
    }
    elseif(!isset($_FILES["fileToUpload"])){
      http_response_code(406);
      exit;
    }

    //Haal uit de session de gebruikersnaam van de ingelogde gebruiker
    $user = $_SESSION["username"];

    //Variabele voor grootte afbeelding
    $imagesize = $_FILES["fileToUpload"]['size'];
    
    //First check size
    if ($imagesize > 5042880 || $imagesize === 0) {
      http_response_code(400);
      echo "file too large"; 
      exit;
    } 
    
    //Bestandstype afbeeldingen bepalen en checken
    switch(exif_imagetype($_FILES['fileToUpload']['tmp_name'])){
      case IMAGETYPE_GIF:
        $imagetype = 'gif';
        break;
      case IMAGETYPE_JPEG:
        $imagetype = 'jpeg';
        break;
      case IMAGETYPE_PNG:
        $imagetype = 'png';
        break;
      case IMAGETYPE_WEBP:
        $imagetype = 'webp';
        break;
      default:
        http_response_code(400);
        echo "invalid extension";
        exit;
    }
    
    //Maak locatie voor de file aan met willekeurige string
    $randomstring = bin2hex(random_bytes(30));
    $target_dir = "uploads/images/";
    $target_file = $target_dir . $randomstring . "_profile.$imagetype";
    
    //Schrijf de image weg naar schijf
    $image = $_FILES["fileToUpload"]["tmp_name"];
    move_uploaded_file($image, $target_file);

    //Start een connectie en zet het pad van de afbeelding in de database
    MysqlHelper::startConnection();
    $usermodel = UserModel::getByUserName($user);
    $usermodel->uploadImage($target_file);
    MysqlHelper::closeConnection();
    echo 'Uploaded';
  }
}

//Run de run functie hiervan
UserUpload::run();