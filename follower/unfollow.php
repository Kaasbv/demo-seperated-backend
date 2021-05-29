<?php
//Include de 2 bestanden die wij nodig hebben
include_once(__DIR__ . "/../_models/FollowerModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");

//Maak een class aan voor deze api call
class FollowerUnfollow {
  public static function run(){
    //Connect met sessie
    session_start();

    //Start een connectie
    MysqlHelper::startConnection();
    
    //Bestaad de Sessie?
    if(!isset($_POST['userYou'])) {/////////////////om te testen met postman, wordt $_SESSION['username']
      http_response_code(403);
      echo "Session doesn't exist";
      exit;
    }
    
    //Model ophalen
    $MyExFriend = FollowerModel::getByUsernames($_POST['userYou'], $_POST['username']);/////////////////om te testen met postman, wordt $_SESSION['username']
    
    //Checken of het opgehaalde model valide is
    if($MyExFriend === false){
      http_response_code(404);
      echo "I don't tink so, that's not your friend! \n This record does not exist in the FollowerModel of the GoalrDB.";
    }

    var_dump($MyExFriend);

    //verwijder de instantie die overeenkomt met het opgehaalde model

    //Sluit de connectie
    MysqlHelper::closeConnection();
  }
}


//Run de run functie hiervan
FollowerUnfollow::run();


    // $username = $_POST['userYou']; //om te testen met postman, wordt $_SESSION['username']
    // $username_following = $_POST['username'];
    
    //     //De query voor het ontvolgen.
    // $sql = "DELETE FROM goalr.Followers WHERE username = ':UN' and username_following = ':UNF';";
    
    //     //Query voorbereiden om sql-injection te voorkomen.
    // $query = MysqlHelper::runQuery($sql);
    // $query->bind_param(':UN', $username);
    // $query->bind_param(':UNF', $username_following);
    
    //     //een melding naar de gebruiker sturen dat er is ontvolgt.
    // echo "$username_following has been unfollowed.";
    
    //    //De actie daadwerkelijk doorvoeren.
    // $query->execute();