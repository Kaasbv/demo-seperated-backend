<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

//Include classes
require_once(__DIR__ . "/../_models/UserModel.php");
require_once(__DIR__ . "/../_helpers/MysqlHelper.php");


class ModelTestCase extends TestCase
{
  public function setUp(): void
  {
    parent::setUp();
    MysqlHelper::startConnection();
  }

  public function tearDown(): void
  {
    MysqlHelper::closeConnection();
  }
}
