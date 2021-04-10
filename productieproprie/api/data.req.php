<?php 
header('Access-Control-Allow-Origin: http://localhost:3000');
header("Content-type: application/json");
require_once("../autoload.php");

use Rosance\Security;
use Rosance\Database;

$_POST = Security::var_cleaner($_POST);

if(!isset($_POST['action']))
    return;

if($_POST['action'] === "getCategories")
{
  $database = new Database();
  exit(json_encode($database->fetchCategories()));
}
if($_POST['action'] === "getSubCategories")
{
  $database = new Database();
  exit(json_encode($database->fetchSubCategories($_POST['data']['category'])));
}

if($_POST['action'] === "getTowns")
{
  $csv = array_map('str_getcsv', file('orase.csv'));
  exit(json_encode($csv));
}

if($_POST['action'] === "addItem")
{  
  exit(json_encode(["success"=>"da"]));
}
?>