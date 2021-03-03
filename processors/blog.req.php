<?php
session_start();
require_once("../autoload.php");
use Rosance\Data;
use Rosance\User;
use Rosance\Project;
use Rosance\Security;
use Rosance\Blog;

$data = new Data();
$user = $data->GetUser($_SESSION['loggedIN']);
$project = new Project($_COOKIE['NCS_PROJECT']);
$blog = new Blog();
/** 
 * Quick and simple security script
 * $_POST and $_GET gets filtered
 * and returned an exact copy of the array but sanitized
*/
$_POST = Security::var_cleaner($_POST);
$_GET = Security::var_cleaner($_GET);

if(!isset($_POST['action']))
    die();

if($_POST['action'] === "author-update"){

	$fname = $_POST['data']['author_first_name'];
	$lname = $_POST['data']['author_last_name'];
	$descr = $_POST['data']['author_description'];
	$profile_pic = $_POST['data']['profile_pic'];
	$email = $_POST['data']['author_email'];
	
	echo $blog->SaveSettings($project, $user->id ,$fname,$lname, $descr, $profile_pic, $email);
}

if($_POST['action'] === "post-new"){
    $author = $blog->GetAuthor($project, $user->id);

    $data = [
      "Title"=>htmlspecialchars($_POST['data']['post-title']),
      "Author"=> $author['UID'],
      "Content"=>htmlspecialchars($_POST['data']['post-content']),
      "Thumbnail"=>$_POST['data']['post_image_id'],
      "Categories"=> $_POST['data']['categories'],
      "Comments"=>$_POST['data']['allow_comments'],
      "Featured"=>$_POST['data']['allow_featured'],
      "SEO-Title"=>htmlspecialchars($_POST['data']['seo-title']),
      "SEO-Slug"=>htmlspecialchars($_POST['data']['seo-slug']),
      "SEO-Description"=>htmlspecialchars($_POST['data']['seo-description'])
    ];
  
    echo $blog->Post($user, $project, $data);
}

if($_POST['action'] === "edit-post"){
	$author = $blog->GetAuthor($project, $user->id);

	$data = [
		"PostID"=>htmlspecialchars($_POST['data']['post_id']),
		"Title"=>htmlspecialchars($_POST['data']['post-title']),
		"Author"=>htmlspecialchars($author['UID']),
		"ContentID"=>htmlspecialchars($_POST['data']['contentID']),
		"Content"=>htmlspecialchars($_POST['data']['post-content']),
		"Thumbnail"=>$_POST['data']['post_image_id'],
		"Categories"=> $_POST['data']['categories'],
		"Comments"=>$_POST['data']['allow_comments'],
		"Featured"=>$_POST['data']['allow_featured'],
		"SEO-Title"=>htmlspecialchars($_POST['data']['seo-title']),
		"SEO-Slug"=>htmlspecialchars($_POST['data']['seo-slug']),
		"SEO-Description"=>htmlspecialchars($_POST['data']['seo-description'])
	];
	echo $blog->Edit($user,$project, $data);
}

if($_POST['action'] === "createCategory")
{
	echo $blog->AddCategory($project,$_POST['data']['categoryName']);
}

if($_POST['action'] === "removeCategory")
{
	echo $blog->RemoveCategory($project,$_POST['data']['categoryToRemove']);
}

?>