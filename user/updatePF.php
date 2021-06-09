<?php
//Maak een class aan
class UserUpload {
  public static function run(){
    session_start();

    //Haal uit de session de gebruikersnaam van de ingelogde gebruiker
    $user = $_SESSION["username"];

    //Maak willekeurige string
    $randomstring = bin2hex(random_bytes(30));

    //Maak locatie voor de file aan
    $target_dir = "uploads/images/";
    $target_file = $target_dir . $randomstring . "_profile.gif";

    //Check het type afbeelding dat geupload is
    $imagefiletype = $_FILES["fileToUpload"]['type'];

    //Check de grootte van de afbeelding
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
      echo "Sorry, your 'file' is too large ;)";
      $uploadOk = 0;
    } elseif($imagefiletype !== "gif" || $imagefiletype !== "png" || $imagefiletype !== "jpeg") {
      echo "Sorry, you used a trash image format <br/><br/>Please upload a gif, png or jpeg instead :)";
      $uploadOk = 0;
    } else {
      $uploadOk = 1;
    }
    
    //Schrijf de image weg naar schijf
    if($uploadOk === 1){
      $image = $_FILES["fileToUpload"]["tmp_name"];
      move_uploaded_file($image, $target_file);
      echo 'Uploaded';
    }

  }
}

//Run de run functie hiervan
UserUpload::run();


