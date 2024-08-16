<?php

class DB
{
  public $servername = "localhost";
  public $username = "u574242705_greenland_rest";
  public $password = 'RuT>p0TB1kS/';
  public $dbname = "u574242705_greenland_rest";
  public $conn;

  function __construct()
  {
    //  echo "dttd";
    $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    if ($this->conn->connect_error) {
      die("Connection failed: " . $this->conn->connect_error);
    }
    $this->conn->set_charset('utf8');
  }
}
$db = null;
function getDB(): DB
{
    if ($GLOBALS["db"] == null) {
        $GLOBALS["db"] = new DB();
    }
    return $GLOBALS["db"];
}
