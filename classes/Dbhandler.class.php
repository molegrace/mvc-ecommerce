<?php
 
class Dbhandler
{
  private $host;
  private $user;
  private $pass;
  private $db;
  public $conn;

  public function __construct()
  {
    $this->connect();
  }

  private function connect()
  {
    // default XAMPP credentials - change to your real user/pass if you created one
    $this->host = "127.0.0.1";
    $this->user = "root";                // <- change to 'admin' if you created that user
    $this->pass = "";                    // <- change to the password you set
    $this->db = "ogtech";

    // connect to db
    $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);

    // proper error check
    if ($this->conn->connect_error) {
      throw new \RuntimeException("Connection failed: " . $this->conn->connect_error);
    }

    return $this->conn;
  }

  public function conn()
  {
    // Return existing live connection or (re)connect
    if ($this->conn && $this->conn->ping()) {
      return $this->conn;
    }

    return $this->connect();
  }
}