<?php
//Include de bestanden de wij nodig hebben
include_once(__DIR__ . "/../_models/Model.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");

class UserModel extends Model {
  public string $email;
  public string $firstname;
  public string $middlename;
  public string $lastname;
  public string $birtdate;
  public string $password;
  public string $date_created;
  public string $date_updated;

  public static function getByEmail($email) {
    $query = "
        SELECT * FROM User vm
        WHERE email = ?
    ";

    [$data] = MysqlHelper::runPreparedQuery($query, [$email], ["s"]);
    
    $object = new UserModel($data["firstname"], $data["middlename"], $data["lastname"]);

    self::fillObject($object, $data);

    return $object;
  }

  public function __construct($firstname, $middlename, $lastname) {
    $this->firstname = $firstname;
    if (isset($middlename)) {
      $this->middlename = $middlename;
    }
    $this->lastname = $lastname;
  }

  public function checkPassword($password) {//Controlleren of $password het wachtwoord van de gebruiker is.
    $hash = $this->password;
    if (password_verify($password, $hash)) {
      return true;
    }
    else {
      return false;
    }
  }

  public function changePassword($password){
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $query = "
      UPDATE User
      SET password = ?
      WHERE email = ?
    ";

    MysqlHelper::runPreparedQuery($query, [$hash, $this->email], ["s", "s"]);
  }
}

?>
