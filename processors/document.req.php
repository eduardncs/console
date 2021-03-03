<?php 
session_start();
require_once("../autoload.php");
use Rosance\Data;
use Rosance\User;
use Rosance\Project;
use Rosance\Security;
use Rosance\Element;

$data = new Data();
$user = $data->GetUser($_SESSION['loggedIN']);
$project = new Project($_COOKIE['NCS_PROJECT']);
/** 
 * Quick and simple security script
 * $_POST and $_GET gets filtered
 * and returned an exact copy of the array but sanitized
*/
$_POST = Security::var_cleaner($_POST);
$_GET = Security::var_cleaner($_GET);

if(!isset($_POST['action']))
	die();

if($_POST['action'] === "setCSS")
{
	if(!isset($_POST['page']) or !isset($_POST['id']))
		return; 

	$array_of_styles = [];

	for ($i=0; $i < count($_POST['data']['props']) ; $i++) { 
		$prop = $_POST['data']['props'][$i];
		$value = $_POST['data']['values'][$i];
		$array_of_styles[$prop] = $value;
	}
	$dom = new Element($user ,$project ,$_POST['id'],$_POST['page']);
	echo $dom->changeStyles($array_of_styles);
}

if($_POST['action'] === "setAttribute")
{
	if(!isset($_POST['page']) or !isset($_POST['id']))
		return; 

	$dom = new Element($user ,$project ,$_POST['id'],$_POST['page']);
	echo $dom->changeAttribute($_POST['data']['attribute'],$_POST['data']['value']);
}
if($_POST['action'] === "setAttributes")
{
	if(!isset($_POST['page']) or !isset($_POST['id']))
		return; 
		
	$dom = new Element($user ,$project ,$_POST['id'],$_POST['page']);
	$array_of_attributes;
	for($i = 0; $i < count($_POST['data']['attributes']); $i++)
	{
		$array_of_attributes[$_POST['data']['attributes'][$i]] = $_POST['data']['values'][$i];
	}
	echo $dom->changeAttributes($array_of_attributes);
}
if($_POST['action'] === "FinishDragging")
{
	if(!isset($_POST['page']) or !isset($_POST['id']) or !isset($_POST['css']))
		return;
	$dom = new Element($user, $project, $_POST['id'], $_POST['page']);
	echo $dom->changeStyles($_POST['css']);
}
if($_POST['action'] === "addClass")
{
	if(!isset($_POST['page']) or !isset($_POST['id']))
		return;

	$dom = new Element($user ,$project, $_POST['id'], $_POST['page']);
	if(empty($_POST['data']['classesToRemoveBefore']) or $_POST['data']['classesToRemoveBefore'] == '')
		echo $dom->addClasses($_POST['data']['class']);
	else
		echo $dom->addClasses(
			$_POST['data']['class'],[
									"removeClasses",
									[
										$_POST['data']['classesToRemoveBefore'],
										false
									]]);
}
if($_POST['action'] === "removeClass")
{
	if(!isset($_POST['page']) or !isset($_POST['id']))
		return;
	
	$dom = new Element($user ,$project, $_POST['id'], $_POST['page']);
	echo $dom->removeClasses($_POST['data']['class']);
}
if($_POST['action'] === "addColumn")
{
	if(!isset($_POST['page']) or !isset($_POST['id']))
		return;
	$dom = new Element($user ,$project, $_POST['id'], $_POST['page']);
	echo $dom->updateColumns(
		$_POST['data']['rowClass'],
		$_POST['data']['Class'],[
									"append",
								[
									htmlspecialchars_decode($_POST['data']['rawItem']),
									false,
									".".$_POST['data']['rowClass']
								]]);
}
if($_POST['action'] === "removeColumn")
{
	if(!isset($_POST['page']) or !isset($_POST['id']))
		return;

		$dom = new Element($user ,$project, $_POST['id'], $_POST['page']);
		if($_POST['data']['newClass'] === 0)
		{
			echo $dom->updateColumns(
				$_POST['data']['rowClass'],
				$_POST['data']['newClass'],[
											"removeSpecified",
										[
											".".$_POST['data']['columnClass'],
											true
										]]); 
		}else {
		 echo $dom->updateColumns(
			$_POST['data']['rowClass'],
			$_POST['data']['newClass'],[
										"removeSpecified",
									[
										".".$_POST['data']['columnClass'],
										false
									]]); 
		}
}
if($_POST['action'] === "removeElement")
{
	if(!isset($_POST['page']) or !isset($_POST['id']))
	return;

	$dom = new Element($user ,$project, $_POST['id'], $_POST['page']);
	echo $dom->remove(true);
}
if($_POST['action'] === "changeText")
{
	if(!isset($_POST['page']) or !isset($_POST['id']))
	return;

	$dom = new Element($user ,$project, $_POST['id'], $_POST['page']);
	echo $dom->changeText($_POST['data']['html']);
}
if($_POST['action'] === "moveAfter")
{
	if(!isset($_POST['page']) or !isset($_POST['id']))
	return;

	$dom = new Element($user ,$project, $_POST['id'], $_POST['page']);
	echo $dom->moveAfter($_POST['data']['that']);
}
if($_POST['action'] === "moveBefore")
{
	if(!isset($_POST['page']) or !isset($_POST['id']))
	return;

	$dom = new Element($user ,$project, $_POST['id'], $_POST['page']);
	echo $dom->moveBefore($_POST['data']['that']);
}
if($_POST['action'] === "addAfter")
{
	if(!isset($_POST['page']) or !isset($_POST['id']))
		return;

	$dom = new Element($user ,$project, $_POST['id'], $_POST['page']);
	echo $dom->addAfter(htmlspecialchars_decode($_POST['data']['element']));
}
if($_POST['action'] === "addBefore")
{
	if(!isset($_POST['page']) or !isset($_POST['id']))
		return;

	$dom = new Element($user ,$project, $_POST['id'], $_POST['page']);
	echo $dom->addBefore(htmlspecialchars_decode($_POST['data']['element']));
}
if($_POST['action'] === "addChield"){
	if(!isset($_POST['page']) or !isset($_POST['id']))
	return;

	$dom = new Element($user ,$project, $_POST['id'], $_POST['page']);
	echo $dom->addChield(htmlspecialchars_decode($_POST['data']['element']));
}
if($_POST['action'] === "setSizes"){
	if(!isset($_POST['page']) or !isset($_POST['id']))
		return;

	$dom = new Element($user ,$project, $_POST['id'], $_POST['page']);
	$array_of_styles = [];
	for ($i=0; $i < count($_POST['data']['attr']); $i++) { 
		$array_of_styles[$_POST['data']['attr'][$i]] = $_POST['data']['value'][$i].$_POST['data']['unit'][$i];
	}
	echo $dom->changeStyles($array_of_styles);
}
?>