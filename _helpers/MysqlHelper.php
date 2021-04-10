<?php

class MysqlHelper {
  public static $credentialPath = __DIR__ . "/../credentials.json";
  public static $connection;

  public static function startConnection(){
    //Verkrijg de credentials met de functie
    $credentials = self::grabCredentials();
    //Maak een mysqli connectie aan
    self::$connection = new mysqli(
      $credentials->databaseHost,
      $credentials->databaseUsername,
      $credentials->databasePassword,
      $credentials->databaseName
    );
    //Check voor een connectie error
    if(self::$connection->connect_errno){
      throw new Exception("Failed to connect to database", 500);
    }
  }

  public static function closeConnection(){
    //Sluit de connectie die eerder is aangemaakt
    self::$connection->close();
  }
  
  public static function runQuery($query){
    //Run query
    $result = self::$connection->query($query);
    //Haal response op
    $response = $result->fetch_all(MYSQLI_ASSOC);
    //Geef response terug enzo niet dan een lege array
    return $response ?? [];
  }

  private static function grabCredentials(){
    //Check of de credentialfile is aangemaakt
    if(!is_file(self::$credentialPath)){
      throw new Exception("Credential file missing", 500);
    }
    //Lees het bestand uit
    $content = file_get_contents(self::$credentialPath);
    //Verander de json in een php object
    $parsedContent = json_decode($content);

    //Check of parsen fout ging
    if($parsedContent === null){
      throw new Exception("Credential file invalid json", 500);
    }

    //Geef het terug
    return $parsedContent;
  }
}