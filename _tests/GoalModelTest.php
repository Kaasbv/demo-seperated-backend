<?php
//Include classes
include_once(__DIR__ . "/../_models/GoalModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");
include_once(__DIR__ . "/../_modules/ModelTestCase.php");


final class GoalModelTest extends ModelTestCase
{
  public $username = "testuser_";
  public $userdata = 
  [
    "firstname" => "Marco",
    "middlename" => "",
    "lastname" => "Tester",
    "email" => "marcotest@gmail.com",
    "birthdate" => "1988-01-01",
    "password" => "b"
  ];

  public $goaldata =
  [
    "ID_goal" => "",
    "parent_goal_id" => "1",
    "kudos" => "0",
    "status" => "todo",
    "start_date" => "1970-01-01 00:00:00",
    "end_date" => "1970-01-01 00:00:00"
  ];

  public function setUp(): void
  {
    parent::setUp();
    //Create user
    $this->username .= bin2hex(random_bytes(20));
    $username = $this->username;

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

  public function testCreate()
  {
    //Create goal
    $data = $this->goaldata;
    $username = $this->username;
    
    $goal = new GoalModel($username, "code testen","week");
    $goal->parent_goal_id = $data["parent_goal_id"];
    $goal->kudos = $data["kudos"];
    $goal->status = $data["status"];
    $goal->start_date = $data["start_date"];
    $goal->end_date = $data["end_date"];
    $goal->create();
    //Get records from database
    $query = "SELECT * FROM Goal WHERE username = ?";
    $records = MysqlHelper::runPreparedQuery($query, [$username], ["s"]);
    //run tests/assertions
    $this->assertNotEmpty($records);
    $record = $records[0];
    $this->assertEquals($record["username"], $username);
    $this->assertEquals($record["parent_goal_id"], $data["parent_goal_id"]);
    $this->assertEquals($record["kudos"], $data["kudos"]);
    $this->assertEquals($record["status"], $data["status"]);
    $this->assertEquals($record["start_date"], $data["start_date"]);
    $this->assertEquals($record["end_date"], $data["end_date"]);
  }

  public function tearDown(): void
  {
    $query = "DELETE FROM User WHERE username = ?";
    MysqlHelper::runPreparedQuery($query, [$this->username], ["s"]);
  }
}
