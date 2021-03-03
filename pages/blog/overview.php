<?php
session_start();
require_once("../../autoload.php");
use Rosance\Data;
use Rosance\Project;
use Rosance\User;
use Rosance\Blog;
$data = new Data();
$user = $data->GetUser($_SESSION['loggedIN']);
$project = new Project($_COOKIE['NCS_PROJECT']);

if($project->project_type != "Blog"){
?>
<div class="row"><div class="col-12 col-sm-12">
<div class="alert alert-danger alert-dismissible">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
<h5><i class="icon fas fa-ban"></i> Something is not right ...</h5>
It looks like your project does not have the permisions to access this page.<br>
Make sure your project type is set to Blog, and you have permisions to access this page<br>
That's all we know.
</div></div></div>
<?php } elseif((!isset($_COOKIE['NCS_PROJECT'])) or !isset($_SESSION['loggedIN'])) {?>
  <div class="row"><div class="col-12 col-sm-12">
  <div class="alert alert-danger alert-dismissible">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <h5><i class="icon fas fa-ban"></i> Something is not right ...</h5>
There is a problem with your credentials , please go ahead and try to sign in again  <a href="registration">here</a>.<br>
If the problem persists you can try to clear your cookies.
  </div></div></div>
<?php }else {
$blog = new Blog();
$posts = $blog->GetPosts($project);
if(isset($_POST['RemovePostsNo']))
{
  $blog->RemovePost($user,$project,$_POST['RemovePostsNo']);
}
  ?>
<div class="row">
  <div class="col-md-12">
    <div class="card card-outline card-primary">
      <div class="card-header">
      <div class="card-tools">
				<div class="input-group border pl-3 pr-3" style="border-radius:25px;">
					<div class="input-group-prepend">
						<div class="input-group-text bg-white border-0">
							<i class="fas fa-search"></i>
						</div>
					</div>
					<input type="text" id="search" name="search" class="form-control border-0" placeholder="Search for a post">
				</div>
			</div>
      </div>
      <div class="card-body p-0 pt-2">
      <ul id="posts-list" class="products-list product-list-in-card">
<?php
if($posts->num_rows == 0)
{
  ?>
                  <li class="item pl-2 pr-2">
                    <div class="product-info text-center">
                      <a href="blog/creative" class="product-title">No posts to show click here to start creating amazing content for your blog</a>
                    </div>
                  </li>
  <?php 
}else{
    while($post = mysqli_fetch_assoc($posts))
    {
      $author = $blog->GetAuthor($project , $post['Author']);
  ?>
                  <li class="item pl-2 pr-2">
                    <div class="product-img">
                      <img src="<?php echo $post['Thumbnail']; ?>" onError="this.onError=null;this.src='images/placeholder.jpg';" alt="Product Image" class="img-size-64">
                    </div>
                    <div class="product-info">
                      <a href="javascript:void(0)" class="product-title"><?php echo $post['Title'] ?></a>
                      <div class="float-right mt-2 mr-4 align-items-md-start">
                      <button type="button" class="btn btn-default btn-pill mr-3 dropdown-toggle" style="display:none;" data-toggle="dropdown" aria-expanded="false" >
                      Actions
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="blog/creative/<?php echo $post['ID']; ?>"> <i class="fas fa-edit"></i> Edit</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" style="color:red;" href="javascript:void(0)" onClick="removePost('<?php echo $post['ID']; ?>')"> <i class="fas fa-trash"></i> Remove</a>
                    </div>
                      </div>
                      <span class="product-description text-sm">
                        Posted on <?php echo $post['Date']; ?> by <?php echo $author['First_Name']." ".$author['Last_Name']; ?>
                      </span>
                    </div>
                  </li>
          <?php } }?>
          </ul>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
  $("#search").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#posts-list li").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
})
$(".item").hover(function(){
    $(this).find(".btn-pill").stop(false,false).fadeIn();
   },function(){
    $(this).find(".btn-pill").stop(false,false).fadeOut();
     })
</script>
<?php } ?>