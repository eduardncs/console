<?php
session_start();
require_once("../autoload.php");
use Rosance\Data;
use Rosance\User;
use Rosance\Project;
use Rosance\Security;
use Rosance\Editor;

$data = new Data();
$user = $data->GetUser($_SESSION['loggedIN']);
$project = new Project($_COOKIE['NCS_PROJECT']);
$editor = new Editor();
/** 
 * Quick and simple security script
 * $_POST and $_GET gets filtered
 * and returned an exact copy of the array but sanitized
*/
$_POST = Security::var_cleaner($_POST);
$_GET = Security::var_cleaner($_GET);

if(!isset($_POST['action']))
    die();

if($_POST['action'] === "account-settings"){
	if(empty($_POST['data']['first_name']) or empty($_POST['data']['last_name']))
		return;

    echo $user->update($_POST['data']['profile_pic'],$_POST['data']['first_name'],$_POST['data']['last_name']);
}
if($_POST['action'] === "general-settings"){
    $global_vars = $data->GetGlobals();
    $data = [
        "Title"=> $_POST['data']['website_name'],
        "Logo"=> $_POST['data']['logo'],
        "Favicon" => $_POST['data']['favicon'],
        "Curency" => $_POST['data']['website_curency'],
        "Meta"=>[
                "Author"=> $global_vars['COMPANY']
                ],
        "Maps"=> $_POST['data']['google-maps']
            ];
    echo $editor->ChangeInfoJson($user, $project,$data);
}
if($_POST['action'] === "legal-settings")
{
    $datatosend = [
			0=>$_POST['data']['GDPR'],
            1=>$_POST['data']['TOS'],
            2=>$_POST['data']['DSCL']];
    echo $data->UpdateLegalDocuments($project, $user , $datatosend);
}
?>