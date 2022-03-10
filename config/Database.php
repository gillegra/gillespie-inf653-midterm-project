<?php

class Database
{
  //DB Params
  private $host = 'localhost';
  private $dbName = 'mysql';
  private $username = 'root';
  private $password = '';
  private PDO $conn;

  //DB Connect
  public function connect()
  {
    // $this->conn = null;
    $this->config();

    try {
      $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbName}", $this->username, $this->password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo "Connection error: {$e->getMessage()}\n";
    }

    return $this->conn;
  }

  private function config()
  {
    $env = getenv();
    $this->host = $env['DB_HOST'] ?? $this->host;
    $this->dbName = $env['DB_NAME'] ?? $this->dbName;
    $this->username = $env['DB_USER'] ?? $this->username;
    $this->password = $env['DB_PASS'] ?? $this->password;
  }
}
