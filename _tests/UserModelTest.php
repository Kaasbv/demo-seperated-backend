<?php
//Include classes
include_once(__DIR__ . "/../_models/UserModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");
include_once(__DIR__ . "/../_modules/ModelTestCase.php");


final class UserModelTest extends ModelTestCase
{
  public $prefix = "testuser_";
  public $userdata = [
    "firstname" => "Henk",
    "middlename" => "test",
    "lastname" => "Gertjes",
    "email" => "henktest@gmail.com",
    "birthdate" => "2030-01-01",
    "password" => "a"
  ];

  public function setUp(): void
  {
    parent::setUp();
    //Create users
    $this->prefix .= bin2hex(random_bytes(20)) . "_";
    $this->userdata["email"] = bin2hex(random_bytes(20)) . "@gmail.com";

    $users = ["get", "update", "exists"];

    foreach($users as $user){
      $username = $this->prefix . $user;
      $query = "
        INSERT INTO User
        (username, email, firstname, middlename, lastname, birthdate, password)
        VALUES(?, ?, ?, ?, ?, ?, ?);
      ";

      MysqlHelper::runPreparedQuery($query, [
        $username,
        $this->userdata["email"],
        $this->userdata["firstname"],
        $this->userdata["middlename"],
        $this->userdata["lastname"],
        $this->userdata["birthdate"],
        $this->userdata["password"]
      ], ["s", "s", "s", "s", "s", "s", "s"]);
    }

  }

  public function testCreate(){
    $data = $this->userdata;
    $username = $this->prefix . "create";
    //Create user
    $user = new UserModel($data["firstname"], $data["middlename"], $data["lastname"]);
    $user->username = $username;
    $user->email = $data["email"];
    $user->birthdate = $data["birthdate"];
    $user->password = $data["password"];
    $user->create();
    //Get records from database
    $query = "SELECT * FROM User WHERE username = ?";
    $records = MysqlHelper::runPreparedQuery($query, [$username], ["s"]);
    //Run tests
    $this->assertNotEmpty($records);
    $record = $records[0];
    $this->assertEquals($record["username"], $username);
    $this->assertEquals($record["email"], $data["email"]);
    $this->assertEquals($record["firstname"], $data["firstname"]);
    $this->assertEquals($record["lastname"], $data["lastname"]);
    $this->assertEquals($record["birthdate"], $data["birthdate"]);
    $this->assertEquals($record["password"], $data["password"]);
  }

  public function testGetByUsername(){
    //Get user
    $user = UserModel::getByUsername($this->prefix . "get");
    //Run tests
    $this->assertNotFalse($user);
    $this->assertIsObject($user);
    $this->assertInstanceOf("UserModel", $user);

    $data = $this->userdata;
    $this->assertEquals($user->email, $data["email"]);
    $this->assertEquals($user->firstname, $data["firstname"]);
    $this->assertEquals($user->lastname, $data["lastname"]);
    $this->assertEquals($user->birthdate, $data["birthdate"]);
    $this->assertEquals($user->password, $data["password"]);
  }

  public function testUpdate(){
    $data = $this->userdata;
    $username = $this->prefix . "update";
    //Create test model
    $user = new UserModel($data["firstname"], $data["middlename"], $data["lastname"]);
    $user->username = $username;
    $user->birthdate = $data["birthdate"];
    $user->password = $data["password"];
    //With one different value
    $user->email = "test@gmail.com";
    $user->update();

    //Get from the database
    $query = "SELECT * FROM User WHERE username = ?";
    $records = MysqlHelper::runPreparedQuery($query, [$username], ["s"]);
    //Run tests
    $this->assertNotEmpty($records);
    $record = $records[0];
    $this->assertNotEquals($record["email"], $data["email"]);
  }

  public function testCheckIfMailExists(){
    //Check with existing user
    $userExists = UserModel::checkIfMailExists($this->userdata["email"]);
    $this->assertTrue($userExists);

    //Check without existing user
    $userDoesntExists = UserModel::checkIfMailExists($this->userdata["email"] . "#");
    $this->assertFalse($userDoesntExists);
  }

  public function testCheckIfUserExists(){
    $username = $this->prefix . "exists";
    //Check with existing user
    $userExists = UserModel::checkIfUserExists($username);
    $this->assertTrue($userExists);

    //Check without existing user
    $userDoesntExists = UserModel::checkIfUserExists($username . "#");
    $this->assertFalse($userDoesntExists);
  }

  public function tearDown(): void
  {
    $search = $this->prefix . "%";
    $query = "DELETE FROM User WHERE username like ?";
    MysqlHelper::runPreparedQuery($query, [$search], ["s"]);
  }
}
