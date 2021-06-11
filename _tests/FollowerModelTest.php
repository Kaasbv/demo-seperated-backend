<?php
//Include classes
include_once(__DIR__ . "/../_models/UserModel.php");
include_once(__DIR__ . "/../_helpers/MysqlHelper.php");
include_once(__DIR__ . "/../_modules/ModelTestCase.php");


final class FollowerModelTest extends ModelTestCase
{
  public $prefix = "testuser_";

  public function setUp(): void
  {
    parent::setUp();
    $this->prefix .= bin2hex(random_bytes(20)) . "_";
    $this->setUpUsers();
    $this->setUpFollowers();
  }

  public function setUpFollowers(){
    $followerMappings = [
      [1, 2], //1 follows 2
      [1, 3],
      [2, 1],
      [3, 1]
    ];

    foreach($followerMappings as [$userId, $userIdFollowing]){
      $query = "
        INSERT INTO Followers
        (username, username_following)
        VALUES (?, ?);
      ";
  
      MysqlHelper::runPreparedQuery($query, [
        $this->prefix . $userId,
        $this->prefix . $userIdFollowing,
      ], ["s", "s"]);
    }
  }

  public function setUpUsers(){
    $data = [
      "firstname" => "Henk",
      "middlename" => "test",
      "lastname" => "Gertjes",
      "email" => "henktest@gmail.com",
      "birthdate" => "2030-01-01",
      "password" => "a"
    ];

    for($i = 1; $i < 4; $i++){
      $username = $this->prefix . $i;
      $query = "
        INSERT INTO User
        (username, email, firstname, middlename, lastname, birthdate, password)
        VALUES(?, ?, ?, ?, ?, ?, ?);
      ";

      MysqlHelper::runPreparedQuery($query, [
        $username,
        $data["email"],
        $data["firstname"],
        $data["middlename"],
        $data["lastname"],
        $data["birthdate"],
        $data["password"]
      ], ["s", "s", "s", "s", "s", "s", "s"]);
    }
  }

  public function testCreate(){
    //Create user
    $follower = new FollowerModel($this->prefix . 2, $this->prefix . 3);
    $follower->create();
    //Get records from database
    $query = "SELECT * FROM Followers WHERE username = ? AND username_following = ?";
    $records = MysqlHelper::runPreparedQuery($query, [$this->prefix . 2, $this->prefix . 3], ["s", "s"]);
    //Run tests
    $this->assertNotEmpty($records);
  }

  public function testGetByUsernames(){
    //Get user
    $follower = FollowerModel::getByUsernames($this->prefix . 2, $this->prefix .  1);
    //Run tests
    $this->assertNotFalse($follower);
    $this->assertIsObject($follower);
    $this->assertInstanceOf("FollowerModel", $follower);
  }

  public function testDelete(){
    //Create object en delete
    $follower = new FollowerModel($this->prefix . 3, $this->prefix . 1);
    $follower->delete();
    //Get records from database
    $query = "SELECT * FROM Followers WHERE username = ? AND username_following = ?";
    $records = MysqlHelper::runPreparedQuery($query, [$this->prefix . 3, $this->prefix . 1], ["s", "s"]);
    //Run tests
    $this->assertEmpty($records);
  }

  public function testList(){
    //Create object en delete
    $username = $this->prefix . 1;
    $followers = FollowerModel::listByUsername($username);
    //Run tests
    $this->assertIsArray($followers);
    $this->assertEmpty($followers);
    $this->assertEquals(count($followers), 2);
    //Check per record
    $foundUsernames = [];
    $usernamesFollowings = [$this->prefix . 2, $this->prefix . 3];
    foreach($followers as $follower){
      $this->assertInstanceOf("FollowerModel", $follower);
      $this->assertEquals($username, $follower->username);
      $this->assertContains($follower->username_following, $usernamesFollowings);
      $foundUsernames[] = $follower->username_following;
    }
    $this->assertEqualsCanonicalizing($usernamesFollowings, $foundUsernames);
  }

  public function tearDown(): void
  {
    $search = $this->prefix . "%";
    $query = "DELETE FROM User WHERE username like ?";
    MysqlHelper::runPreparedQuery($query, [$search], ["s"]);
  }
}
