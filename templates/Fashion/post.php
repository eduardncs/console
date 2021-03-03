<?php 
require_once("core/ssk.req.class.php");
require_once("core/main.class.php");
require_once("core/builder.class.php");
require_once("core/database.class.php");
require_once("core/blog.class.php");
$main = new Main();
$builder = new Builder();
$blog = new Blog();
$info = $main->getInfo();
if(!isset($_GET['post']))
{
     $post = ["Array" =>[
          "ID"=> "",
          "Title"=>"This is the title of the post",
          "Tumbnail" => "images/about-image.jpg",
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
     <h4 align="center">Stay tuned</h4></br></br>
     '];
}
else
     $post = $blog->GetPost($_GET['post']);

echo $builder->buildHead();
echo $builder->buildBody();
 ?>
        <nav class="navbar navbar-expand-lg navbar-light bg-white _6007f7ff9848c py-4" editable="editable" data-panel="header" ="_6007f7ff9848c">
            <div class="container">
                <div class="_6d07f7ff9843c" editable="editable" data-panel="text" ="_6d07f7ff9843c"><a class="navbar-brand" href="home"><b>Start Bootstrap</b></a></div>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <?php  
                    echo $builder->buildMenu();
                    ?>
                </div>
            </div>
        </nav>
<section editable="editable" data-panel="section" style="background-image:url(images/1.jpg); background-size:cover; height:350px;">
		<div class="container" editable="editable" data-panel="container">
			<div class="row h-100" editable="editable" data-panel="row">
				<div class="col-md-12 text-center" editable="editable" data-panel="column">
					<div editable="editable" data-panel="text">
						<font style="color:#ffffff"><h3><span style="font-size: 64px;"><b>Fashion Blog</b></span></h3><h3><span style="font-size: 64px;"><b><br></b></span></h3><p><span style="font-size: 28px;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's dummy.</span> </p></font>
					</div>
				</div>
			</div>
		</div>
</section>
	<!-- //banner -->
<section class="pt-4 mb-2" editable="editable" data-panel="section">
	<div class="container" editable="editable" data-panel="container">
		<div class="row" editable="editable" data-panel="row">
			<div class="col-md-9" editable="editable" data-panel="column">
				<div class="single-left">
					<div class="single-left1">
						<?php  echo $post['Content'];  ?>
					</div>
					
				<?php  if(!isset($_GET['post'])){  ?>
				<div style="background-color:#212121">
				<div class="row">
					<div class="col-md-2">
						<img style="margin:10px;" src="https://rosance.com/console/images/placeholder.jpg" alt="" class="img-responsive img-fluid">
					</div>
					<div class="col-md-10">
						<?php 
						echo "<p style='padding:10px;color:white;'>Hello, This is the author of the post, this part will be changed when you review your author settings on the console</p>";
						 ?>
						<a style="float:right; color:yellow; padding-right:20px; padding-bottom:5px;" href="#"><i>John Doe</i></a>
					</div>
				</div>
				</div>
				<?php  } else {  ?>
				<div class="bg-dark container py-3" editable="editable" data-panel="container">
					<div class="row" editable="editable" data-panel="row">
						<div class="col-md-2" editable="editable" data-panel="column">
						<?php $author = $blog->getAuthor($post['Array']['Author']); ?>
							<img src="<?php echo $author['profile_pic']; ?>" alt="" class="img-responsive">
						</div>
						<div class="col-md-10 text-light" editable="editable" data-panel="column">
							<?php 
							echo "<p>".$author['Optional']."</p>";
							?>
							<a style="float:right; color:yellow; padding-right:20px; padding-bottom:5px;" href="#"><i><?php  echo $author['First_Name']." ".$author['Last_Name'];  ?></i></a>
						</div>
					</div>
				</div>
				<?php  }  ?>

				<br>
				<div class="comments">
					<h3>Our Recent Comments</h3>
					<div class="container pt-3">
						<?php  
						$comments = $blog->getComments($post['Array']['ID']);
						if($comments['TotalComments'] == 0){
						 ?>
						<h4 class="text-center">No comments added yet ! <br/>
						 Be the first one to comment on this post</h4>
						<?php  } else { for ($i=0; $i < count($comments['Comments']) ; $i++) { ?>
						<div class="row mt-2">
							<div class="col-md-2">
								<img src="images/default.png" alt=" " class="img-responsive img-thumbnail" style="max-width:100px; max-height:100px;" />
							</div>
							<div class="col-md-10">
								<h4><a href="#"><?php echo $comments['Comments'][$i]['Name']; ?></a></h4>
								<p><?php echo $comments['Comments'][$i]['Date']; ?></p>
								<p class="mt-1"><?php echo $comments['Comments'][$i]['Content']; ?></p>
							</div>
						</div>
						<?php } } ?>
					</div>
				</div>

					<div editable="editable" data-panel="text" class="pt-4 _603bf7b208b7e">
						<h3>Leave Your Comment</h3>
					</div>
					<form id="comment_form" editable="editable" data-panel="form">
						<div class="row pt-3" editable="editable" data-panel="row">
							<div class="col-md-6" editable="editable" data-panel="column">
								<div class="form-group">
									<input editable="editable" data-panel="input" class="form-control" type="text" name="name" placeholder="Name" required="">
								</div>
							</div>
							<div class="col-md-6" editable="editable" data-panel="column">
								<div class="form-group">
									<input editable="editable" data-panel="input" class="form-control" type="email" name="email" placeholder="Email" required="">
								</div>
							</div>
						</div>
						<div class="row" editable="editable" data-panel="row">
							<div class="col-md-12" editable="editable" data-panel="column">
								<div class="form-group">
									<textarea editable="editable" data-panel="textarea" class="form-control" name="message" placeholder="Your comment here..." required="" rows="10"></textarea>
								</div>
							</div>
						</div>
						<div class="row" editable="editable" data-panel="row">
							<div class="col-md-12" editable="editable" data-panel="column">
								<div class="container-fluid text-right" editable="editable" data-panel="container">
									<input type="hidden" name="postid" id="postid" value="<?php echo $post['Array']['ID']; ?>">
									<button editable="editable" data-panel="button" type="submit" class="btn btn-primary btn-lg">Submit comment </button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="col-md-3">
				<div class="w3ls_popular_posts">
					<h3>Recent Posts</h3>
					<?php  echo $blog->GetRecentPosts(3);  ?>
				</div>
				
				<div class="w3l_categories">
					<h3>Categories</h3>
					<ul>
						<?php  echo $blog->getCategories();  ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>

	<!-- footer -->
<footer class="mt-5 bg-dark text-white" editable="editable" data-panel="footer">
	<div class="container p-5" editable="editable" data-panel="container">
		<div class="row pt-4" editable="editable" data-panel="row">
			<div class="col-md-4 text-left" editable="editable" data-panel="column">
				<div editable="editable" data-panel="text">
					<h3>Contact Information</h3>
					<p></p><p></p>
					<p>22 Russell Street, Victoria ,Melbourne AUSTRALIA </p><p><a href="#">E: info [at] domain.com</a> </p><p>P: +254 2564584 / +542 824565</p><p><a href="#">W: www.w3layouts.com</a></p>
				</div>
			</div>
			<div class="col-md-4 text-center" editable="editable" data-panel="column">
				<div editable="editable" data-panel="text">
					<h2>Fashion Blog</h2><p>Lorem ipsum dolor sit amet consectetur adipisicing elit sedc dnmo eiusmod tempor incididunt ut labore et dolore magna aliqua uta enim ad minim ven iam quis nostrud exercitation ullamco labor nisi ut aliquip exea commodo consequat duis aute&nbsp;</p>
				</div>
			</div>
			<div class="col-md-4 text-right" editable="editable" data-panel="column">
				<div editable="editable" data-panel="text">
					<h3>Usefull links</h3><p><a href="home">Home</a></p><p><a href="contact">Contact</a><br></p><p><a href="legal">Legal documents</a></p>
				</div>
			</div>
		</div>
	</div>
	<div class="container p-3" editable="editable" data-panel="container">
		<div class="row" editable="editable" data-panel="row">
			<div class="col-md-12" editable="editable" data-panel="column">
				<div class="container p-3 text-center" editable="editable" data-panel="container">
					<div class="text-white" editable="editable" data-panel="text">
						© 2020 Fashion Blog . All Rights Reserved . Design by&nbsp;<a href="http://w3layouts.com/">W3layouts</a>&nbsp;. Powered by&nbsp;<a href="https://rosance.com/" target="_blank">Rosance</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>
<!-- SCRIPTS -->
<?php  echo $builder->buildJS();  ?>
<!-- start-smoth-scrolling -->
<script type="text/javascript">
jQuery(document).ready(function($) {
	$(".scroll").click(function(event){		
		event.preventDefault();
		$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
	});
});
</script>
<!-- start-smoth-scrolling -->
	<script type="text/javascript">
		$(document).ready(function() {		
			$().UItoTop({ easingType: 'easeOutQuart' });
			});
	</script>
	

