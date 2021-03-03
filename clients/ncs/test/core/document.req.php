<?php 
session_start();
require_once("security.class.php");
/** 
 * Quick and simple security script
 * $_POST and $_GET gets filtered
 * and returned an exact copy of the array but sanitized
*/
use Rosance\Security;
$_POST = Security::var_cleaner($_POST);
$_GET = Security::var_cleaner($_GET);

if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message']) && isset($_POST['postid'])){
    require_once("main.class.php");
    $blog = new Blog();
    echo $blog->postComment($_POST['name'],$_POST['email'],$_POST['message'],$_POST['postid']);
}
if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['subject'])){
    require_once("main.class.php");
	$main = new Main();
	$data = [
		"date" => date("Y-m-d H:i"),
 		"name" => strip_tags($_POST['name']),
		"email" => strip_tags($_POST['email']),
		"subject" => strip_tags($_POST['subject']),
		"message" => strip_tags($_POST['message']),
		"ip" => $main->GetIP()
	];
	echo $main->SendMessage($data);
}
?>