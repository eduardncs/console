<?php
class Database
{
    private $server;
    private $username;
    private $password;
    private $database;

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
