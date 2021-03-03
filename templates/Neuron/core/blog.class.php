<?php
require_once("main.class.php");
require_once("database.class.php");
class Blog extends Main
{

  public function getAuthor($id)
  {
    if(empty($id))
      return false;
    $credentials = $this->getDatabaseCredentials();
    $database = new Database($credentials);
    $connection = $database->dbconnectmaster();
    $query = "SELECT * FROM Authors WHERE UID='".$id."'";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    $data = [
      "First_Name" => $row['First_Name'],
      "Last_Name" => $row['Last_Name'],
      "Email" => $row['Email'],
      "profile_pic" => $row['profile_pic'],
      "Optional" => $row['Optional']
    ];
    $database->dbclosemaster($connection);
    return $data;
  }

  private function getContents($contentid, $short)
  {
    if($contentid == null)
      return false;
    $path = "blog/".$contentid.".txt";
    if(!$short)
      return html_entity_decode(file_get_contents($path));
    return substr(strip_tags(html_entity_decode(file_get_contents($path))),0,300).' ... ';
  }

  public function getPosts($featuredOnly)
  {
    $credentials = $this->getDatabaseCredentials();
    $database = new Database($credentials);
    $connection = $database->dbconnectmaster();
    //get an array of posts
    $query = "SELECT * FROM Posts ORDER BY Date DESC";
    $result = mysqli_query($connection,$query);
    $database->dbclosemaster($connection);
    
    while($posts = mysqli_fetch_assoc($result)){
      if(!$featuredOnly)
      {
        $contents = self::getContents($posts['Content'], true);
        echo '<div class="col-md-offset-1 col-md-10 col-sm-12">
      <div class="blog-post-thumb">';
      if(!empty($posts['Thumbnail']))
        echo '
        <div class="blog-post-image" align="center">
            <img src="'.$posts['Thumbnail'].'" class="img-responsive" alt="Blog Image">
        </div>
        ';
        echo '
        <div class="blog-post-title">
          <h3><a href="post/'.$posts['seo_slug'].'">'.$posts['Title'].'</a></h3>
        </div>
        <div class="blog-post-format">
          <span><a href="#">';
          $author = $this->GetAuthor($posts['Author']);
          echo '<img src="'.$author['profile_pic'].'" class="img-responsive img-circle">';
          echo $author['First_Name']." ".$author['Last_Name'];
        echo '</a></span>
          <span><i class="fas fa-date"></i>'.$posts['Date'].'</span>
          <span><a href="#"><i class="fas fa-comment-o"></i>';
      
        $comments = self::getComments([$posts['ID']]);
        echo $comments['TotalComments'].' Comments</a></span>
        </div>
        <div class="blog-post-des">
          <p>'.$contents.'</p>
          <a href="post/'.$posts['seo_slug'].'" class="btn btn-default">Continue Reading</a>
        </div>
      </div>
    </div>
    ';
      }
      else
      {
        if($posts['allow_featured'] == "on")
        {
          $contents = self::getContents($posts['Content'], true);
      echo '<div class="col-md-offset-1 col-md-10 col-sm-12">
    <div class="blog-post-thumb">';
    if(!empty($posts['Thumbnail']))
      echo '
      <div class="blog-post-image" align="center">
          <img src="'.$posts['Thumbnail'].'" class="img-responsive" alt="Blog Image">
      </div>
      ';
      echo '
      <div class="blog-post-title">
        <h3><a href="post/'.$posts['seo_slug'].'">'.$posts['Title'].'</a></h3>
      </div>
      <div class="blog-post-format">
      <span><a href="#">';
      $author = $this->GetAuthor($posts['Author']);
      echo '<img src="'.$author['profile_pic'].'" class="img-responsive img-circle">';
      echo $author['First_Name']." ".$author['Last_Name'];
      echo '</a></span>
        <span><i class="fas fa-date"></i>'.$posts['Date'].'</span>
        <span><a href="#"><i class="fas fa-comment-o"></i>';
    
      $comments = self::getCommentsNumber([$posts['ID']]);
      echo $comments.' Comments</a></span>
      </div>
      <div class="blog-post-des">
        <p>'.$contents.'</p>
        <a href="post/'.$posts['seo_slug'].'" class="btn btn-default">Continue Reading</a>
      </div>
    </div>
  </div>
  ';
        }
      }
    }
    if($result->num_rows == 0)
    {
      echo '<div align="center" class="col-md-12 col-sm-12"><h2>Awesome content is yet to come</h2></div>';
    }
  }
  
  private function getCommentsNumber($postID)
  {
    $credentials = $this->getDatabaseCredentials();
    $database = new Database($credentials);
    $connection = $database->dbconnectmaster();
    //sanitize inputs
    $postID = mysqli_real_escape_string($connection, $postID[0]);
    //Query the table Comments and filter using $postID
    $query = "SELECT * FROM Comments WHERE PostID='".$postID."'";
    $result = mysqli_query($connection,$query);
    $database->dbclosemaster($connection);
    return $result->num_rows;
  }

  public function getComments($postID)
  {
    $credentials = $this->getDatabaseCredentials();
    $database = new Database($credentials);
    $connection = $database->dbconnectmaster();
    //sanitize inputs
    $postID = mysqli_real_escape_string($connection, $postID);
    //Query the table Comments and filter using $postID
    $query = "SELECT * FROM Comments WHERE PostID='".$postID."'";
    $result = mysqli_query($connection,$query);
    $database->dbclosemaster($connection);
    //Check if query is successfull
    if($result)
    {
      //if query is successfull put all into one array
      $array = [
        "TotalComments" => "",
        "Comments" => []
      ];
      //populate the array with what we need
      //Creating an array this way will give us the posibility to expand the code in the future in an easy way
      $array["TotalComments"] = $result->num_rows;
      //Loop trought all comments and add the data into the subarray Comments;
      while($row = mysqli_fetch_assoc($result))
      {
        $array['Comments'][] = $row;
      }
      //after we finish all this return the results,
      //if this is not successfull, the code will return false
      return $array;
    }
    return false;
  }

  public function getPost($postSlug)
  {
    if(empty($postSlug))
      return "";
    $credentials = $this->getDatabaseCredentials();
    $database = new Database($credentials);
    $connection = $database->dbconnectmaster();
    $postSlug = mysqli_real_escape_string($connection , $postSlug);

    $query = "SELECT * FROM Posts WHERE seo_slug='".$postSlug."'";
    $result = mysqli_query($connection,$query);
    $database->dbclosemaster($connection);
    if($result)
    {
      while($row = mysqli_fetch_assoc($result))
      {
        $categories;
        foreach(explode(",",$row['Categories']) as $cat)
        {
          if(empty($categories))
            $categories = $cat;
          else
            $categories .= " ".$cat;
        }
        $arr = ["Array"=>"", "Content"=>""];
        $arr["Array"] = $row;
        $arr['Content'] = '<div class="blog-post-title">
        <h2>'.$row['Title'].'</a></h2>
      </div>

      <div class="blog-post-format">
        <span><a href="#">';
        $author = $this->getAuthor($row['Author']);
        $arr['Content'] .= '<img src="'.$author['profile_pic'].'" class="img-responsive img-circle"> '.$author['First_Name']." ".$author['Last_Name'].'';
        $arr['Content'] .= '</a></span>
        <span><i class="fas fa-date"></i> '.$row['Date'].'</span>
        <span><a href="#"><i class="fas fa-comment-o"></i>'.$this->getComments($row['ID'])['TotalComments'].' Comments</a></span>
      </div>
      <blockquote class="blockquote">
      '.$categories.' &nbsp; &nbsp;
      </blockquote>

      <div class="blog-post-des">
        '.$this->getContents($row['Content'],false).'
      </div>';
      $author = $this->GetAuthor($row['Author']);
    }
      return $arr;
    }
    return "Nothing to display";
  }

  public function postComment($name,$email, $message, $postid)
  {
    try{
    if(empty($name) or empty($email) or empty($message))
      throw new Exception("Fields cannot be empty!");
    $credentials = $this->getDatabaseCredentials("D_C.json");
    $database = new Database($credentials);
    $connection = $database->dbconnectmaster();
    $main = new Main();
    $name = mysqli_real_escape_string($connection,$name);
    $email = mysqli_real_escape_string($connection,$email);
    $message = mysqli_real_escape_string($connection,strip_tags($message));
    $message = substr($message,0,300);
    $postid = mysqli_real_escape_string($connection,$postid);
    $date = date("Y-m-d H:i");
    $query = "INSERT INTO Comments(PostID,Name,Username,Content,Date) VALUES ('".$postid."','".$name."','".$email."','".$message."','".$date."')";
    $result = mysqli_query($connection, $query);
    $database->dbclosemaster($connection);
    if($result)
    {
      return $main->ShowSuccess("Success!");
    }else
      throw new Exception("Something went wrong!");
  }
    catch(Exception $ex)
    {
      return $main->ShowError($ex->getMessage());
    }
  }
}
if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message']) && isset($_POST['postid']))
{
    $blog = new Blog();
     echo $blog->postComment($_POST['name'],$_POST['email'],$_POST['message'],$_POST['postid']);
}
 ?>
