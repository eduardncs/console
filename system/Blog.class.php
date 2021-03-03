<?php
namespace Rosance
{
  require_once("rosance_autoload.php");
  use Rosance\Callback;
  class Blog extends Data
  {
    private $globals;
    function __construct()
    {
      $this->globals = $this->GetGlobals();
    }
    public function GetPost(User $user, Project $project , $id)
    {
      try {
        if(empty($project) or empty($id) or empty($user))
          throw new \Exception("Fields cannot be empty!");
        $slave = $this->globals['PREFIX']."_".$project->project_id;
        $connection = $this->dbconnectslave($slave);
        $id = mysqli_real_escape_string($connection,$id);
        $query = "SELECT * FROM Posts WHERE ID='".$id."'";
        $result = mysqli_query($connection,$query);
        $this->dbcloseslave($connection);
        if($result)
        {
          while($row = mysqli_fetch_assoc($result))
          {
            return new Post($user->Business_Name,$project->project_name_short,$row['ID'],$row['Title'],$row['Thumbnail'],$row['Author'],$row['Categories'],$row['Content'],$row['Date'],$row['allow_comments'],$row['allow_featured'],$row['seo_slug'], $row['seo_title'],$row['seo_description']);
          }
        }
        else
          throw new \Exception("We've encountered an error with this post!");
      } catch (\Exception $ex) {
        return $ex->getMessage();
      }
    }


    public function GetPosts(Project $project)
    {
      try{
        $slave = $this->globals['PREFIX']."_".$project->project_id;
        $connection = $this->dbconnectslave($slave);
        $query = "SELECT * FROM Posts ORDER BY Date DESC";
        $result = mysqli_query($connection,$query);
        $this->dbcloseslave($connection);
        return $result;
      } catch (\Exception $ex)
      {
      return $ex->getMessage();
      }
    }

    public function GetCategories(Project $project){
      try{
        $slave = $this->globals['PREFIX']."_".$project->project_id;
        $connection = $this->dbconnectslave($slave);
        $query = "SELECT * FROM Categories";
        $result = mysqli_query($connection,$query);
        $this->dbcloseslave($connection);
        return $result;
      }catch(\Exception $ex)
      {
        return $ex->getMessage();
      }
    }

    public function AddCategory(Project $project,string $data)
    {
      try{
        $callback = new Callback();
        if(empty($project) || empty($data))
        {
          return $callback->SendErrorOnMainPage("Something went wrong -> Fields cannot be empty !");
        }
        $slave = $this->globals['PREFIX']."_".$project->project_id;
        $connection = $this->dbconnectslave($slave);
        $data = mysqli_real_escape_string($connection,$data);
        $query = "SELECT * FROM Categories WHERE Category_Name='".$data."'";
        $result = mysqli_query($connection,$query);      if($result->num_rows > 0)
        {
          $this->dbcloseslave($connection);
          return $callback->SendErrorOnMainPage("Something went wrong -> Category allready exists!");
        }
        $query1 = "INSERT INTO Categories(Category_Name) VALUES ('".$data."')";
        $result2 = mysqli_query($connection,$query1);
        if($result2)
        {
          $this->dbcloseslave($connection);
          return $callback->SendSuccessOnMainPage("Category successfully added!");
        }
        else{
          $this->dbcloseslave($connection);
          return $callback->SendErrorOnMainPage("Something went wrong");
        }
      }catch(\Exception $ex){
        return $callback->SendErrorOnMainPage("Something went wrong -> ".$ex->getMessage);
      }
    }

    public function RemoveCategory(Project $project,string $data)
    {
      try{
        $callback = new Callback();
        if(empty($project) || empty($data))
        {
          return $callback->SendErrorOnMainPage("Something went wrong -> Fields cannot be empty !");
        }
        $slave = $this->globals['PREFIX']."_".$project->project_id;
        $connection = $this->dbconnectslave($slave);
        $data = mysqli_real_escape_string($connection,$data);
        $query = "DELETE FROM Categories WHERE Category_name='".$data."'";
        $result = mysqli_query($connection, $query);
        if($result)
          {$this->dbcloseslave($connection);return $callback->SendSuccessToast("Category removed");}
          else
          {$this->dbcloseslave($connection);return $callback->SendErrorOnMainPage("Something went wrong!".var_dump($data));}
      }catch(\Exception $ex){
        return $callback->SendErrorOnMainPage("Something went wrong -> ".$ex->getMessage);
      }
    }

    public function Post(User $user, Project $project, $data)
    {
      try{
        $callback = new Callback();
        if(empty($project) || empty($data) || empty($user))
          throw new \Exception("Fields cannot be empty!");
        
          if(empty($data['SEO-Title']) or empty($data['SEO-Slug']))
            throw new \Exception("Please check your SEO settings");
          if(empty($data['Author']))
            throw new \Exception("Please review your author info! -> Go to Overview->Edit Settings");
        $slave = $this->globals['PREFIX']."_".$project->project_id;
        $connection = $this->dbconnectslave($slave);

        $title = mysqli_real_escape_string($connection, $data['Title']);
        $contentid = uniqid(rand(), false);
        $content = $data['Content'];
        $thumbnail = mysqli_real_escape_string($connection, $data['Thumbnail']);
        $author = mysqli_real_escape_string($connection, $data['Author']);
        $comments = mysqli_real_escape_string($connection,$data['Comments']);
        $featured = mysqli_real_escape_string($connection, $data['Featured']);
        $seo_title = mysqli_real_escape_string($connection, $data['SEO-Title']);
        $seo_slug = mysqli_real_escape_string($connection, $data['SEO-Slug']);
        $seo_description = mysqli_real_escape_string($connection,$data['SEO-Description']);
        $categories = mysqli_real_escape_string($connection, $data['Categories']);
        $date = date("Y-m-d H:i");
        //Check if seo lug is not allready registered
        
        $query0 = "SELECT * FROM Posts where seo_slug='".$seo_slug."'";
        $result0 = mysqli_query($connection,$query0);
        if(!$result0)
          throw new \Exception("Database problem!");
        if($result0->num_rows > 0)
          throw new \Exception("Seo slug allready registered, please choose another one");

        $query = "INSERT INTO Posts(Title,Thumbnail,Author,Categories,Content,Date,allow_comments,allow_featured,seo_slug,seo_title,seo_description) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')";
        $query = sprintf($query,$title,$thumbnail,$author,$categories,$contentid,$date,$comments,$featured,$seo_slug,$seo_title,$seo_description);
        $result = mysqli_query($connection,$query);
        $file = fopen("../clients/".$user->Business_Name."/".$project->project_name_short."/blog/".$contentid.".txt","w");
        fwrite($file,$content);
        fclose($file);
        $this->dbcloseslave($connection);

        if($result){
          return $callback->SendSuccessOnMainPage("Article posted successfully!");
        }
        else
          throw new \Exception("Database problem");
      }
      catch(\Exception $ex)
      {
        return $callback->SendErrorToast("Something went wrong -> ".$ex->getMessage());
      }
    }

    public function RemovePost(User $user, Project $project , $ID){
      try{
        $callback = new Callback();
        if(empty($project) or empty($ID) or empty($user))
          throw new \Exception("Fields cannot be empty!");
        $slave = $this->globals['PREFIX']."_".$project->project_id;
        $connection = $this->dbconnectslave($slave);

        $ID = mysqli_real_escape_string($connection,$ID);
        $query0 = "SELECT * FROM Posts where ID='".$ID."'";
        $result0 = mysqli_query($connection,$query0);
        $row = mysqli_fetch_assoc($result0);
        $query = "DELETE FROM Posts where ID='".$ID."'";
        $result = mysqli_query($connection,$query);
        $file = "../../clients/".$user->Business_Name."/".$project->project_name_short."/blog/".$row['Content'].".txt";
        unlink($file);
        // Then remove all comments for that posts so we can keep a clean database
        if($result)
        {
          $query1 = "DELETE FROM Comments WHERE PostId='".$ID."'";
          $result1 = mysqli_query($connection , $query1);
          if($result1)
          {
            $this->dbcloseslave($connection);
            return $callback->SendSuccessToast("Article removed successfully!");
          }
          else
          {
            throw new \Exception("Database problem! #2");
          }
        }
        else
          throw new \Exception("Database problem! #1");
        
      }catch(\Exception $ex){
        $this->dbcloseslave($connection);
        return $callback->SendErrorOnMainPage("Something went wrong -> ".$ex->getMessage());
      }
    }

    public function Edit(User $user, Project $project, $data)
    {
      try{
        $callback = new Callback();
        if(empty($project) || empty($data))
          throw new \Exception("Fields cannot be empty!");
          if(empty($data['SEO-Title']) or empty($data['SEO-Slug']) or empty($data['SEO-Description']))
          throw new \Exception("Please check your SEO settings");
          if(empty($data['Author']))
            throw new \Exception("Please review your author info! -> Go to Overview->Edit Settings");
        $slave = $this->globals['PREFIX']."_".$project->project_id;
        $connection = $this->dbconnectslave($slave);
        $id = mysqli_real_escape_string($connection,$data['PostID']);
        $title = mysqli_real_escape_string($connection, $data['Title']);
        $contentid = mysqli_real_escape_string($connection, $data['ContentID']);
        $author = mysqli_real_escape_string($connection, $data['Author']);
        $content = $data['Content'];
        $thumbnail = mysqli_real_escape_string($connection, $data['Thumbnail']);
        $comments = mysqli_real_escape_string($connection,$data['Comments']);
        $featured = mysqli_real_escape_string($connection, $data['Featured']);
        $seo_title = mysqli_real_escape_string($connection, $data['SEO-Title']);
        $seo_slug = mysqli_real_escape_string($connection, $data['SEO-Slug']);
        $seo_description = mysqli_real_escape_string($connection,$data['SEO-Description']);
        $categories = mysqli_real_escape_string($connection, $data['Categories']);

        $query = "UPDATE Posts SET Title='%s', Author='%s' ,Thumbnail='%s',Categories='%s', allow_comments='%s', allow_featured='%s', seo_slug='%s', seo_title='%s', seo_description='%s' WHERE ID='".$id."'";
        $query = sprintf($query,$title,$author,$thumbnail,$categories,$comments,$featured,$seo_slug,$seo_title,$seo_description);
        $result = mysqli_query($connection,$query);
        $file = fopen("../clients/".$user->Business_Name."/".$project->project_name_short."/blog/".$contentid.".txt","w");
        fwrite($file,$content);
        fclose($file);
        $this->dbcloseslave($connection);

        if($result){
          return $callback->SendSuccessOnMainPageWithRedirect("Article edited successfully!","blog/overview");
        }
        else
          throw new \Exception("Mixed values");
      }
      catch(\Exception $ex)
      {
        return $callback->SendErrorOnMainPage("Something went wrong -> ".$ex->getMessage());
      }
    }

    public function GetAuthor(Project $project, $uid)
    {
      if(empty($project) or empty($uid))
        return null;

      $slave = $this->globals['PREFIX']."_".$project->project_id;
      $connection = $this->dbconnectslave($slave);

      $uid = mysqli_real_escape_string($connection, $uid);

      $query = "SELECT * FROM Authors WHERE UID='".$uid."'";
      $result = mysqli_query($connection, $query);
      if($result)
      {
        $author;
        if($result->num_rows == 0)
        {
          $author = ["ID"=>"", "UID"=> "", "profile_pic"=> "images/default.png", "First_Name"=>"", "Last_Name"=> "","Email"=>"", "Optional"=>""];
        }else
        {
          $author = mysqli_fetch_assoc($result);
        }
        $this->dbcloseslave($connection);
        return $author;
      }
      $this->dbcloseslave($connection);
      return null;
    }

    public function SaveSettings(Project $project , $id , $fname, $lname , $description, $profile_pic, $email)
    {
      try
      {
        if(empty($project) or empty($fname) or empty($lname)  or (empty($email)))
        {
          $callback = new Callback();
          return $callback->SendErrorToast("Fields cannot be empty!");
        }
        //Get database name
        $slave = $this->globals['PREFIX']."_".$project->project_id;
        $connection = $this->dbconnectslave($slave);
        //Sanitize inputs
        $id = mysqli_real_escape_string($connection,$id);
        $fname = mysqli_real_escape_string($connection, $fname);
        $lname = mysqli_real_escape_string($connection, $lname);
        $optional = mysqli_real_escape_string($connection, $description);
        $email = mysqli_real_escape_string($connection,$email);
        $profile_pic = mysqli_real_escape_string($connection , $profile_pic);
        //
        // Check if this user is on the db allready
        //
        $query0 = "SELECT * FROM Authors WHERE UID='".$id."'";
        $result0 = mysqli_query($connection,$query0);
        if($result0)
        {
          if($result0->num_rows == 0)
          {
            // Insert
            $query1 = "INSERT INTO Authors(UID,profile_pic,First_Name,Last_Name,Email,Optional) VALUES('".$id."','".$profile_pic."','".$fname."','".$lname."','".$email."','".$optional."')";
            $result1 = mysqli_query($connection, $query1);
            if($result1)
            {
              $callback= new Callback();
              $this->dbcloseslave($connection);
              return $callback->SendSuccessToast("Account successfully updated!");
            }
            else
            {
              $this->dbcloseslave($connection);
              throw new \Exception("Something went wrong ! #1");
            }
          }
          else
          {
            // Update
            $query2 = "UPDATE Authors SET profile_pic='".$profile_pic."',First_Name='".$fname."',Last_Name='".$lname."',Email='".$email."',Optional='".$optional."' WHERE UID='".$id."'";
            $result2 = mysqli_query($connection, $query2);
            if($result2)
            {
              $callback = new Callback();
              $this->dbcloseslave($connection);
              return $callback->SendSuccessToast("Account successfully updated!");
            }
            else
            {
              $this->dbcloseslave($connection);
              throw new \Exception("Something went wrong ! #2");
            }
          }
        }
        else
        {
          $this->dbcloseslave($connection);
          throw new \Exception("Something went wrong! #0");
        }
      }
      catch(\Exception $ex)
      {
        {
          $callback = new Callback();
          return $callback->SendErrorToast($ex->getMessage());
        }
      }
    }
  }

  class Post
  {
    public $id;
    public $title;
    public $thumbnail;
    public $author;
    public $categories;
    public $contentID;
    public $content;
    public $date;
    public $comments;
    public $featured;
    public $seo_slug;
    public $seo_title;
    public $seo_description;

    function __construct($user,$project ,$id,$title,$thumbnail,$author,$categories,$contentid,$date,$comments,$featured,$seo_slug,$seo_title,$seo_description)
    {
      $this->id = $id;
      $this->title = $title;
      $this->thumbnail = $thumbnail;
      $this->author = $author;
      $this->categories = $categories;
      $this->date = $date;
      $this->comments = $comments;
      $this->featured = $featured;
      $this->seo_slug = $seo_slug;
      $this->seo_title = $seo_title;
      $this->seo_description = $seo_description;
      $this->contentID = $contentid;
      $this->content = $this->GetContents($user,$project , $contentid);
    }

    private function GetContents($user,$project , $contentid)
    {
      $path = "../../clients/".$user."/".$project."/blog/";
      $contents = file_get_contents($path.$contentid.".txt");
      return $contents;
    }
  }
}
?>
