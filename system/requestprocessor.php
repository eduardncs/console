<?php
session_start();
require_once("../autoload.php");
use Rosance\Database;
use Rosance\Callback;
use Rosance\Security;
use Rosance\Data;
use Rosance\Editor;
use Rosance\User;
use Rosance\Project;
use Rosance\Blog;
$data = new Data();
$callback = new Callback();
$blog = new Blog();
$editor = new Editor();
$global_vars = $data->GetGlobals();
$_POST = Security::var_cleaner($_POST);
$_GET = Security::var_cleaner($_GET);

//////////////////////////////////////////////////////////////////////////////////
//Create project area start

if(isset($_POST['Info']) && isset($_POST['Template']) && isset($_POST['Type']))
{
	echo $data->CreateProject($_SESSION['loggedIN'],$_POST['Info'][0],$_POST['Template'],$_POST['Type']);
}

//Create project area end
//////////////////////////////////////////////////////////////////////////////////
//Social media area start
//Add social media to json file
//////////////////////////////////////////////////////////////////////////////////
if(isset($_POST['key']) && isset($_POST['socialicon']) && isset($_POST['sociallink']) && isset($_POST['action']) && ($_POST['action']) == "savechanges"){
	try{
		$project = new Project($_COOKIE['NCS_PROJECT']);
		$user = $data->GetUser($_SESSION['loggedIN']);
		$datas["Key"] = trim($_POST['key']);
		$datas["Icon"] =	trim($_POST['socialicon']);
		$datas["Link"] =	trim($_POST['sociallink']);

		$file = "../../clients/".$user->Business_Name."/".$project->project_name_short."/core/widgets/social.json";
		$contents = file_get_contents($file);
		$jsoncontents = json_decode($contents,true);

		if(empty($datas['Key']))
		{
			$uniqueid = uniqid (rand (),false);
			$arrt['Key'] = $uniqueid;
			$arrt['Icon'] = $datas['Icon'];
			$arrt['Link'] = $datas['Link'];
			array_push($jsoncontents['SocialMedia'],$arrt);
		}else{
			for($i = 0; $i < count($jsoncontents['SocialMedia']); $i++)
			{
				if($jsoncontents['SocialMedia'][$i]["Key"] == $datas['Key'])
				{
					$jsoncontents['SocialMedia'][$i]['Icon'] = $datas['Icon'];
					$jsoncontents['SocialMedia'][$i]['Link'] = $datas['Link'];
					break;
				}
			}
		}
		file_put_contents($file, json_encode($jsoncontents));
		echo $callback->SendSuccessToast("Success!");
	}
	catch(\Exception $ex){echo $callback->SendErrorToast("Error, please try again later, if problem persist please contact us");}
}elseif(isset($_POST['key']) && isset($_POST['action']) && ($_POST['action']) == "removelink"){
//////////////////////////////////////////////////////////////////////////////////
//Remove social media from json file by key
//////////////////////////////////////////////////////////////////////////////////
	try{
		$project = new Project($_COOKIE['NCS_PROJECT']);
		$user = $data->GetUser($_SESSION['loggedIN']);
		$key = trim($_POST['key']);

		$file = "../../clients/".$user->Business_Name."/".$project->project_name_short."/core/widgets/social.json";
		$contents = file_get_contents($file);
		$jsoncontents = json_decode($contents,true);

		$result = [];

		foreach($jsoncontents['SocialMedia'] as $keyz) {
				if($keyz["Key"] != $key) {
						$result[] = $keyz;
				}
		}
		file_put_contents($file, json_encode(["SocialMedia" => $result]));
		echo $callback->SendSuccessToast("Deleted!");
	}
	catch(\Exception $ex){echo $callback->SendErrorToast("Error, please try again later, if problem persist please contact us");}
}
//////////////////////////////////////////////////////////////////////////////////
//Social media area end
//////////////////////////////////////////////////////////////////////////////////

// Remove media area end
//////////////////////////////////////////////////////////////////////////////////
// Categories area start

// Categories area end
//////////////////////////////////////////////////////////////////////////////////
// Legal documents area start

// Legal documents area end
//////////////////////////////////////////////////////////////////////////////////
// General settings area start

// General settings area end
//////////////////////////////////////////////////////////////////////////////////
// Account settings area start

// Account settings area end
//////////////////////////////////////////////////////////////////////////////////
// Author settings area start

// Author settings area end
///////////////////////////////////////////////////////////////////////////////////
// Blog posting area start
///////////////////////////////////////////////////////////////////////////////////

// Blog posting area end
/////////////////////////////////////////////////////////////////////////////////
//Portofolio area start
//
if(isset($_POST['proj_key']) && isset($_POST['proj_cover']) && isset($_POST['proj_title']) && isset($_POST['proj_descr']) && isset($_POST['proj_demo']) && isset($_POST['proj_src']) && isset($_POST['action']) && $_POST['action']=="save"){
	$project = new Project($_COOKIE['NCS_PROJECT']);
	$user = $data->GetUser($_SESSION['loggedIN']);
	$data = ["Key"=>$_POST['proj_key'],"Cover"=>$_POST['proj_cover'],"Title"=>$_POST['proj_title'],"Description"=>$_POST['proj_descr'],"Demo"=>$_POST['proj_demo'],"Source"=>$_POST['proj_src']];
	echo $editor->EditPortofolio($user,$project, $data);
}
if(isset($_POST['proj_key']) && isset($_POST['proj_cover']) && isset($_POST['proj_title']) && isset($_POST['proj_descr']) && isset($_POST['proj_demo']) && isset($_POST['proj_src']) && isset($_POST['action']) && $_POST['action']=="remove"){
	$project = new Project($_COOKIE['NCS_PROJECT']);
	$user = $data->GetUser($_SESSION['loggedIN']);
	$data = ["Key"=>$_POST['proj_key']];
	echo $editor->RemoveFromPortofolio($user,$project, $data);
}
?>