<?php
class Database
{
    public $server;
    public $username;
    public $password;
    public $database;

    public function __construct($credentials)
    {
      $this->server = $credentials['server'];
      $this->username = $credentials['username'];
      $this->password = $credentials['password'];
      $this->database = $credentials['database'];
    }

    public function dbconnectmaster()
    {
      try
      {
        return mysqli_connect($this->server ,$this->username,$this->password, $this->database);
      }
      catch(Exception $ex)
      {
        return $ex;
      }
    }

    public function dbclosemaster($connection)
    {
      mysqli_close($connection);
    }
}
?>
