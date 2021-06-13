<?php
//Maak een class aan
class UserUpload {
  public static function run(){
    session_start();
    if(isset($_SESSION["username"])){

      //Haal uit de session de gebruikersnaam van de ingelogde gebruiker
      $user = $_SESSION["username"];

      //Maak willekeurige string
      $randomstring = bin2hex(random_bytes(30));

      //Check het type afbeelding dat geupload is
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
        echo 'Uploaded';
      }

    //Als de user session niet bestaat, response code 400
    } else {
      http_response_code(400);
    }

  }
}

//Run de run functie hiervan
UserUpload::run();