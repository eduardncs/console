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

$data = new Data();
$editor = new Editor();
/** 
 * Quick and simple security script
 * $_GET gets filtered
 * and returned an exact copy of the array but sanitized
*/
$_GET = Security::var_cleaner($_GET);

if(!isset($_GET['action']))
{
    exit("Action not set!");
}

$user = $data->GetUser($_GET['data']['UserID']);
$project = new Project($_GET['data']['ProjectID']);

if($_GET['action'] === "move")
{
    $_GET['data']['Inverted'] = filter_var($_GET['data']['Inverted'], FILTER_VALIDATE_BOOLEAN);
    $_GET['data']['isFirst'] = filter_var($_GET['data']['isFirst'], FILTER_VALIDATE_BOOLEAN);
    exit(json_encode($editor->move($user, $project, $_GET['data']['Key'], $_GET['data']['To'], $_GET['data']['Neighbour'], $_GET['data']['Inverted'],$_GET['data']['isFirst'])));
}
if($_GET['action'] === "changeIndex")
{
    $_GET['data']['Inverted'] = filter_var($_GET['data']['Inverted'], FILTER_VALIDATE_BOOLEAN);
    exit(json_encode($editor->changeIndex($user, $project, $_GET['data']['Key'], $_GET['data']['Neighbour'], $_GET['data']['Inverted'])));
}
if($_GET['action'] === "addLink")
{
    $dataes = $_GET['data']['Key'];
    if($_GET['data']['isFolder'] == "true")
        exit(json_encode($editor->addLink($user, $project, $dataes , true)));
    else
        exit(json_encode($editor->addLink($user, $project, $dataes, false)));
}
if($_GET['action'] === "removeLink")
{
    if(!isset($_GET['data']['Key']))
        return;
		
    exit(json_encode($editor->removeLink($user, $project, $_GET['data']['Key'])));
}

if($_GET['action'] === "reactGetGlobals"){
    echo json_encode([
        "User" => $user,
        "Project" => $project
    ]);
    return;
}
if($_GET['action'] === "fetchMenuJSON")
{
    $json = $data->getWidgetJSON($user, $project ,'menu');
    exit(json_encode($json));
}

?>