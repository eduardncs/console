<?php
require_once("core/ssk.req.class.php");
require_once("core/main.class.php");
require_once("core/builder.class.php");
require_once("core/database.class.php");
require_once("core/blog.class.php");
$main = new Main();
$builder = new Builder();
$blog = new Blog();
if(!isset($_GET['post']))
{
     $post = ["Array" =>[
          "ID"=> "",
          "Title"=>"This is the title of the post",
          "Thumbnail" => "images/about-image.jpg",
          "Author"=> "John doe",
          "Categories"=> "Category1,Category2",
          "Date"=>"2020-09-11 15:12:00",
          "allow_comments"=>"on",
          "allow_featured"=>"on",
          "seo_slug"=>"",
          "seo_title"=>"",
          "seo_description"=> ""
     ],
     "Content"=>'
     <div class="blog-post-image" align="center">
     <img src="images/about-image.jpg" class="img-responsive" alt="Blog Image">
      </div>
     <div class="blog-post-title">
        <h2>This is the title of the post</a></h2>
      </div>
      <div class="blog-post-format">
      <span><a href="#"><img src="images/author-image1.jpg" class="img-responsive img-circle">Rosance</a></span>
      <span><i class="fa fa-date"></i>2020-09-11 15:12:00</span>
      <span><a href="#"><i class="fa fa-comment-o"></i>0 Comments</a></span>
    </div>
    <blockquote class="blockquote">Category , Category 2<br/>
    </blockquote>

     <div class="blog-post-des">
     <h2 align="center">Awesome content is yet to come</h2><br/>
     <h4 align="center">Stay tuned</h4>
     </div>
     '];
}
else
     $post = $blog->GetPost($_GET['post']);
echo $builder->buildHead($post["Array"]["Title"]);
?>
<body>

<div class="preloader">
     <div class="sk-spinner sk-spinner-wordpress">
          <span class="sk-inner-circle"></span>
     </div>
</div>

<!-- Navigation section  -->
<nav class="navbar navbar-default" role="navigation" editable="editable" datapanel="header">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <div class="navbar-brand">
          <a editable="editable" datapanel="text" href="home">Rosance</a>
     </div>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
           <?php echo $builder->buildMenu(); ?>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container -->
</nav>
<!-- Home Section -->
<?php if($post['Array']['Thumbnail'] != "")
{?>
<section id="home" class="parallax-section" style="background : url(<?php echo $post['Array']['Thumbnail']; ?>) no-repeat; height: 65vh;">
     <div class="overlay"></div>
     <div class="container">
          <div class="row">

               <div class="col-md-12 col-sm-12">
                    <h1><?php echo $post['Array']['Title']; ?></h1>
               </div>

          </div>
     </div>
</section>
<?php
}
?>
<!-- Blog Single Post Section -->

<section id="blog-single-post">
     <div class="container">
          <div class="row">

               <div class="col-md-offset-1 col-md-9 col-sm-9">
               <?php
                echo $post["Content"];
               ?>
               <div class="blog-single-post-thumb">
                    <?php
                         if($post['Array']['Author'] != "John doe")
                              $author = $blog->GetAuthor($post['Array']['Author']);
                         else
                              $author = null;
                         if($author != null)
                         {
                    ?>
                    <div class="blog-author">
                         <div class="media">
                              <div class="media-object pull-left">
                                   <img src="<?php echo $author['profile_pic'] ?>" class="img-circle img-responsive" alt="blog">
                              </div>
                              <div class="media-body">
                                   <h3 class="media-heading"><a href="#"><?php echo $author['First_Name']." ".$author['Last_Name']; ?></a></h3>
                                   <p><?php echo $author['Optional']; ?></p>
                              </div>
                         </div>
                    </div>
                         <?php }?>
     <?php
     if($post['Array']['allow_comments'] == "on")
     {
          $comments = $blog->getComments($post['Array']['ID']);
          if($comments['TotalComments'] == 0)
          {
               echo '<h4 align="center">No comments added yet</h4>';
          }
          else
          {
               echo '<div class="blog-comment">
               <h3>Comments</h3>';
               foreach($comments['Comments'] as $comment)
               {
     ?>
               <div class="media">
               <div class="media-body p-0">
                    <h3 class="media-heading"><?php echo $comment['Name']; ?></h3>
                    <span><small>(<?php echo $comment['Date']; ?>)</small></span>
                    <p><?php echo $comment['Content']; ?></p>
               </div>
          </div>
     <?php }
     echo '</div>';     
} } 
     if($post['Array']['allow_comments'] == "on")
     {
     ?>
     <div>
          <div class="blog-comment-form">
          <div style="display:inline-block;" editable="editable" datapanel="text"><h3>Tell me what is on your mind</h3></div>
                    <form id="comment_form">
                         <input type="hidden" name="postid" value="<?php echo $post['Array']['ID']; ?>">
                         <input type="text" class="form-control" placeholder="Name" name="name" required maxlength="100">
                         <input type="email" class="form-control" placeholder="Email" name="email" required maxlength="100">
                         <textarea name="message" rows="5" class="form-control" id="message" placeholder="Message" message="message" required="required" maxlength="300"></textarea>
                         <div class="col-md-3 col-sm-4">
                              <input name="submit" type="submit" class="form-control" id="submit" value="Post Your Comment">
                         </div>
                    </form>
          </div>
     </div>
     <?php } ?>
                    </div>
          </div>
          <div class="col-md-2">
          </div>
     </div>
</section>

<!-- Footer Section -->

<footer editable="editable" datapanel="footer">
     <div class="container">
          <div class="row">

          <div class="col-md-12" align='center'><div style="display:inline-block;" editable="editable" datapanel="text"><h2>Follow us</h2></div></div>
          <div class="col-md-12" align="center"><?php echo $builder->buildSocial();?></div>
               <div class="clearfix col-md-12 col-sm-12">
                    <hr>
               </div>

               <div class="col-md-12 col-sm-12" align="center">
                    <div style="display:inline-block;" editable="editable" datapanel="text"><p>Copyright © 2020 by Yoursite , Template created by <a href="https://tooplate.com" target="_blank">TooPlate</a> , powered by <a href="https://rosance.com" target="_blank">Rosance</a></p></div>
               </div>
          </div>
     </div>
</footer>

<!-- Back top -->
<a href="#back-top" class="go-top"><i class="fa fa-angle-up"></i></a>

<!-- SCRIPTS -->
<?php echo $builder->buildJS(); ?>
</body>
</html>