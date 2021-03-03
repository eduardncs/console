<?php
session_start();
require_once("../autoload.php");
use Rosance\Security;
use Rosance\Database;
use Rosance\Data;
/** 
 * Quick and simple security script
 * @_POST and @_GET gets filtered
 * and returned an exact copy of the array but clean
*/
$_GET = Security::var_cleaner($_GET);
$_POST = Security::var_cleaner($_POST);


//Register user area start
if(isset($_POST["NCS_FNAME"]) && isset($_POST["NCS_LNAME"]) && isset($_POST["NCS_PASS"]) && isset($_POST["NCS_PASS2"]) && isset($_POST["NCS_EMAIL"]) && isset($_POST['NCS_BUSINESS']) && isset($_POST['NCS_ACTION']) && ($_POST['NCS_ACTION'] == "REGISTER"))
{
	$database = new Database();
	echo $database->registerUser($_POST["NCS_FNAME"],$_POST["NCS_LNAME"], $_POST["NCS_PASS"], $_POST["NCS_PASS2"], $_POST["NCS_EMAIL"], $_POST['NCS_BUSINESS']);
}
//Login user area start
if(isset($_POST["NCS_EMAIL"]) && isset($_POST["NCS_PASS"]) && isset($_POST['NCS_ACTION']) && ($_POST['NCS_ACTION'] == "LOGIN"))
{
	$database = new Database();
	echo $database->loginUser($_POST["NCS_EMAIL"], $_POST["NCS_PASS"], true);
}
//Login with google area start
if(isset($_POST['NCS_ACTION']) && $_POST['NCS_ACTION'] == "loginWithGoogle" && isset($_POST['NCS_TOKEN']))
{
	$database = new Database();
	echo $database->loginUserWithGoogle($_POST['NCS_TOKEN']);
}
if(isset($_POST['NCS_ACTION']) && $_POST['NCS_ACTION'] == "validateBusinessName" && isset($_POST['NCS_ID']) && isset($_POST['NCS_BUSINESS']))
{
	$database = new Database();
	echo $database->finishRegisterUser($_POST['NCS_ID'], $_POST['NCS_BUSINESS']);
}
if(isset($_POST['NCS_ACTION']) && $_POST['NCS_ACTION'] == "loginWithFacebook" && isset($_POST['NCS_TOKEN']))
{
	$database = new Database();
	echo $database->loginUserWithFacebook($_POST['NCS_TOKEN']);
}
//Login user area end
//Reset password area start
if(isset($_POST['old_pass']) && isset($_POST['new_pass1']) && isset($_POST['new_pass2']))
{
	$database = new Database();
	$user = $data->GetUser($_SESSION['loggedIN']);
	echo $database->resetPassword($user , $_POST['old_pass'],$_POST['new_pass1'], $_POST['new_pass2']);
}
if(isset($_POST['action']) && $_POST['action'] === "deleteAccount")
{
	$userID = $_SESSION['loggedIN'];
	$database = new Database();
	echo $database->deleteUser($userID);
}
if(isset($_POST['NCS_ACTION']) && $_POST['NCS_ACTION'] == "RESENDCONFIRMATIONEMAIL")
{
	$database = new Database();
	echo $database->ResendConfirmationEmail($_POST['NCS_EMAIL']);
}
?>