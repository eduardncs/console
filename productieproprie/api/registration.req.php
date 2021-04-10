<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header("Content-type: application/json");
require_once("../autoload.php");

use Rosance\Security;
use Rosance\Database;

$_POST = Security::var_cleaner($_POST);
$_GET = Security::var_cleaner($_GET);

if(!isset($_POST['action']))
    return;

    if($_POST['action'] === "getTowns")
    {
      $csv = array_map('str_getcsv', file('orase.csv'));
      exit(json_encode($csv));
    }
    
if($_POST['action'] === "loginDefault")
{
  $user = $_POST['data']['User'];
  $pass = $_POST['data']['Pass'];
  $database = new Database();
  exit(json_encode($database->loginUser($user,$pass)));
}
if($_POST['action'] === "registerDefault")
{
  $user = $_POST['data']['User'];
  $pass = $_POST['data']['Pass'];
  $pass2 = $_POST['data']['Pass2'];
  $fname = $_POST['data']['FName'];
  $lname = $_POST['data']['LName'];
  $database = new Database();
  exit(json_encode($database->registerUser($fname, $lname ,$pass, $pass2,$user)));
}
if($_POST['action'] === "activateAccount")
{
  $uid = $_POST['data']['UID'];
  $token = $_POST['data']['TOKEN'];
  $database = new Database();
  exit(json_encode($database->ActivateAccount($uid,$token)));
}
?>