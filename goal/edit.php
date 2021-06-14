<?PHP
require_once(__DIR__ . "/../_models/GoalModel.php");
require_once(__DIR__ . "/../_helpers/MysqlHelper.php");

//Maak een class aan voor deze api call
class UserUpdate {
  public static function run(){
    session_start();
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

  $possibleKeys = ["email", "firstname", "middlename", "lastname", "birthdate", "password"];

  foreach($possibleKeys as $key){
    if(isset($_POST[$key])){
      //Als het een wachtwoord is
      if($key === "password"){
        //Hash het wachtwoord en spreek het model aan
        $usermodel->{$key} = password_hash($_POST[$key],PASSWORD_DEFAULT);
      }
      //spreek het model aan
      else $usermodel->{$key} = $_POST[$key];
    }
  } 

  $usermodel->update();
  
  //Sluit de connectie
  MysqlHelper::closeConnection();
  }
}

//Run de run functie hiervan
UserUpdate::run();

?>