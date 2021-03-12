<?php 
header('Access-Control-Allow-Origin: http://localhost:3000');
header("Content-type: application/json");
session_start();
require_once("../autoload.php");
use Rosance\Data;
use Rosance\User;
use Rosance\Project;
use Rosance\Security;
use Rosance\Editor;

if(isset($_GET['action']) && $_GET['action'] === "reactGetGlobals"){
    $data = new Data();
    $user = $data->GetUser($_GET['data']['UserID']);
    $project = new Project($_GET['data']['ProjectID']);
    echo json_encode([
        "BusinessName" => $user->Business_Name,
        "ProjectName" => $project->project_name_short
    ]);
    return;
}

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
    return;

//////////////////////////////////////////////////////////////////////////////////
//Secure file upload area start
//////////////////////////////////////////////////////////////////////////////////
if(isset($_FILES['file']) && $_POST['action'] === 'uploadNewFile'){
	echo $editor->uploadFile($user, $project, $_FILES['file']);
}
if($_POST['action'] == "uploadFileFromURL"){
	if(empty($_POST['url']))
		return;
    echo $editor->uploadFileFromURL($user, $project, $_POST['url']);
}

if($_POST['action']){
    if(!isset($_POST['Source']) || !isset($_POST['Source-id']))
        return;
    echo $editor->removeMedia($user, $project, $_POST['Source'], $_POST['Source-id']);
}

if($_POST['action'] === "removeLink")
{
    if(!isset($_POST['data']['Key']))
        return;
    $data = $_POST['data']['Key'];
    echo $editor->removeLink($user, $project, $data);
}
if($_POST['action'] === "addLink")
{
    if(!isset($_POST['data']['Key']))
        return;
    $data = $_POST['data']['Key'];
    if($_POST['data']['isFolder'] == "true")
        echo $editor->addLink($user, $project, $data , true);
    else
        echo $editor->addLink($user, $project, $data, false);
}
if($_POST['action'] === "move")
{
    if(!isset($_POST['data']['Key']))
        return;
    $_POST['data']['Inverted'] = filter_var($_POST['data']['Inverted'], FILTER_VALIDATE_BOOLEAN);
    $_POST['data']['isFirst'] = filter_var($_POST['data']['isFirst'], FILTER_VALIDATE_BOOLEAN);
    echo $editor->move($user, $project, $_POST['data']['Key'], $_POST['data']['To'], $_POST['data']['Neighbour'], $_POST['data']['Inverted'],$_POST['data']['isFirst']);
}
if($_POST['action'] === "editLink")
{
    if(!isset($_POST['data']['Key']))
        return;
    $_POST['data']['isFolder'] =  filter_var($_POST['data']['isFolder'], FILTER_VALIDATE_BOOLEAN);
    echo $editor->editLink($user, $project , $_POST['data']['Key'], $_POST['data']['isFolder'], $_POST['data']['Text'], $_POST['data']['Href'], $_POST['data']['Target']);
}
?>