<?php
require_once("main.class.php");
require_once("database.class.php");
define("POSTS",[
              '<div class="wthree-top">
              <div class="w3agile-top">
              <div class="w3agile_special_deals_grid_left_grid">
              ',

              '
              <a href="post/%s"><img src="%s" class="img-fluid" alt=""></a>
              ',

              ' </div>
              %s
              <div class="w3agile-middle">
              <ul>
                <li><a href="javascript:void(0)"><i class="fas fa-calendar" aria-hidden="true"></i>%s</a></li>
                <li><a href="javascript:void(0)"><i class="fas fa-comment" aria-hidden="true"></i>%s Comments</a></li>
              </ul>
            </div>
            </div>
            <div class="row pt-4 pb-5">
              <div class="col-md-3 p-4 w3agile-left">
                <h5>By : <br/> %s</h5>
              </div>
              <div class="col-md-9 w3agile-right">
                <h3><a href="post/%s">%s</a>
                <p>%s</p>
                <a class="agileits w3layouts" href="post/%s">Read More <span class="glyphicon agileits w3layouts glyphicon-arrow-right" aria-hidden="true"></span></a>
              </div>',
              '<div class="clearfix"></div>
					</div>
				</div>'
]);
define("POST",[
              '<div class="single-left1">',
              '<img src="images/2.jpg" alt=" " class="img-fluid">',
              '<h3>%s</h3>
              <ul>
                <li><i class="fas fa-user mr-2"></i><a href="#">%s</a></li>
                <li><i class="fas fa-comments mr-2" aria-hidden="true"></i><a href="#">%s Comments</a></li>
              </ul>
              <p>%s</p>',
              '</div>']);
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
    $skeleton_header = POSTS[0];
    $skeleton_cover = POSTS[1];
    $skeleton_body = POSTS[2];
    $skeleton_footer = POSTS[3];
    $credentials = $this->getDatabaseCredentials();
    $database = new Database($credentials);
    $connection = $database->dbconnectmaster();
    //get an array of posts
    $query = "SELECT * FROM Posts ORDER BY Date DESC";
    $result = mysqli_query($connection,$query);
    $database->dbclosemaster($connection);
    while($posts = mysqli_fetch_assoc($result)){
      $formated_body = "";
      $formated_cover = "";
      if(!$featuredOnly)
      {
        if($posts['allow_featured'] != "true")
          continue;
        $contents = self::getContents($posts['Content'], true);
        $author = self::getAuthor($posts['Author']);
        $comments = self::getCommentsNumber($posts['ID']);
        if($posts['Thumbnail'] != "")
        {
          $formated_cover = sprintf($skeleton_cover,$posts['seo_slug'],$posts['Thumbnail']);
        }
        else
          $formated_cover = "<div style='padding-top:35px;'></div>";

        $formated_body .= sprintf($skeleton_body, $formated_cover, $posts['Date'], $comments, $author['First_Name']." ".$author['Last_Name'],$posts['seo_slug'],$posts['Title'],$contents ,$posts['seo_slug']);
      echo $skeleton_header.$formated_body.$skeleton_footer;
      }
    }
    if($result->num_rows == 0)
    {
      echo '<div class="col-md-12 col-sm-12 mx-auto text-center"><h2 class="text-center" style="color:black;">Awesome content is yet to come</h2></div>';
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
    $skeleton_header = POST[0];
    $skeleton_cover = POST[1];
    $skeleton_body = POST[2];
    $skeleton_footer = POST[3];
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
        
        $author = $this->getAuthor($row['Author']);
        $contents = $this->getContents($row['Content'], false);
        $comments = $this->getCommentsNumber($row['ID']);
        $arr['Content'] = $skeleton_header;

        if($row['Thumbnail'] != "")
          $arr['Content'] .= sprintf($skeleton_cover,$row['Thumbnail']);

        $arr['Content'] .= sprintf($skeleton_body,$row['Title'],$author['First_Name']." ".$author['Last_Name'], $comments,$contents);
        $arr['Content'] .= $skeleton_footer;
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
      $name = mysqli_real_escape_string($connection,strip_tags($name));
      $email = mysqli_real_escape_string($connection,strip_tags($email));
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

  public function getRecentPosts($howmany)
  {
    $credentials = $this->getDatabaseCredentials();
    $database = new Database($credentials);
    $connection = $database->dbconnectmaster();
    $query = "SELECT * FROM Posts ORDER BY Date DESC LIMIT ".$howmany;
    $result = mysqli_query($connection,$query);
    $database->dbclosemaster($connection);
    $val = "";
    while($row = mysqli_fetch_assoc($result)){
      $val .= '<div class="agileits_popular_posts_grid">
      <div class="w3agile_special_deals_grid_left_grid">';
      if($row['Thumbnail'] != "")
        $val .= '<a href="'.$row['seo_slug'].'"><img src="'.$row['Thumbnail'].'" class="img-fluid" alt=""></a>';
      $val .= '
      </div>
      <h4><a href="'.$row['seo_slug'].'">'.$row['Title'].'</a></h4>
      <h5><i class="fas fa-calendar" aria-hidden="true"></i>'.$row['Date'].'</h5>
    </div>';
    }
    return $val;
  }

  public function getCategories()
  {
    $credentials = $this->getDatabaseCredentials();
    $database = new Database($credentials);
    $connection = $database->dbconnectmaster();
    $query = "SELECT * FROM Categories";
    $result = mysqli_query($connection,$query);
    $database->dbclosemaster($connection);
    $categories = "";
    if($result->num_rows != 0)
    {
      echo "<ul>";
      while($row = mysqli_fetch_assoc($result))
      {
        echo "<li><i style='color:#FFAC3A; padding-right:20px;' class='fas fa-arrow-right' aria-hidden='true'></i>".$row['Category_Name']."</li>";
      }
      echo "</ul>";
    }
  }
}
 ?>
