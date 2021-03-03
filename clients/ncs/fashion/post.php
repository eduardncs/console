
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
        <nav class="navbar navbar-expand-lg navbar-light bg-white _6007f7ff9848c py-4 _603f5bbb0b925" editable="editable" data-panel="header" data-panelID="_603f5bbb0b925">
            <div class="container">
                <div class="_6d07f7ff9843c _603f5bbb0baa9" editable="editable" data-panel="text" data-panelID="_603f5bbb0baa9"><a class="navbar-brand" href="home"><b>Start Bootstrap</b></a></div>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <?php   
                    echo $builder->buildMenu();
                     ?>
                </div>
            </div>
        </nav>
<section editable="editable" data-panel="section" style="background-image:url(images/1.jpg); background-size:cover; height:350px;" class="_603f5bbb0bbe3" data-panelID="_603f5bbb0bbe3">
		<div class="container _603f5bbb0bd13" editable="editable" data-panel="container" data-panelID="_603f5bbb0bd13">
			<div class="row h-100 _603f5bbb0be45" editable="editable" data-panel="row" data-panelID="_603f5bbb0be45">
				<div class="col-md-12 text-center _603f5bbb0bf76" editable="editable" data-panel="column" data-panelID="_603f5bbb0bf76">
					<div editable="editable" data-panel="text" class="_603f5bbb0c0a7" data-panelID="_603f5bbb0c0a7">
						<font style="color:#ffffff"><h3><span style="font-size: 64px;"><b>Fashion Blog</b></span></h3>
<h3><span style="font-size: 64px;"><b><br></b></span></h3>
<p><span style="font-size: 28px;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's dummy.</span> </p></font>
					</div>
				</div>
			</div>
		</div>
</section>
	<!-- //banner -->
<section class="pt-4 mb-2 _603f5bbb0c1dd" editable="editable" data-panel="section" data-panelID="_603f5bbb0c1dd">
	<div class="container _603f5bbb0c313" editable="editable" data-panel="container" data-panelID="_603f5bbb0c313">
		<div class="row _603f5bbb0c442" editable="editable" data-panel="row" data-panelID="_603f5bbb0c442">
			<div class="col-md-9 _603f5bbb0c573" editable="editable" data-panel="column" data-panelID="_603f5bbb0c573">
				<div class="single-left">
					<div class="single-left1">
						<?php   echo $post['Content'];   ?>
					</div>
					
				<?php   if(!isset($_GET['post'])){   ?>
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
				<?php   } else {   ?>
				<div class="bg-dark container py-3 _603f5bbb0c6a2" editable="editable" data-panel="container" data-panelID="_603f5bbb0c6a2">
					<div class="row _603f5bbb0c7d4" editable="editable" data-panel="row" data-panelID="_603f5bbb0c7d4">
						<div class="col-md-2 _603f5bbb0c904" editable="editable" data-panel="column" data-panelID="_603f5bbb0c904">
						<?php  $author = $blog->getAuthor($post['Array']['Author']);  ?>
							<?php?> alt="" class="img-responsive">
						</div>
						<div class="col-md-10 text-light _603f5bbb0ca33" editable="editable" data-panel="column" data-panelID="_603f5bbb0ca33">
							<?php  
							echo "<p>".$author['Optional']."</p>";
							 ?>
							<a style="float:right; color:yellow; padding-right:20px; padding-bottom:5px;" href="#"><i><?php   echo $author['First_Name']." ".$author['Last_Name'];   ?></i></a>
						</div>
					</div>
				</div>
				<?php   }   ?>

				<br>
				<div class="comments">
					<h3>Our Recent Comments</h3>
					<div class="container pt-3">
						<?php   
						$comments = $blog->getComments($post['Array']['ID']);
						if($comments['TotalComments'] == 0){
						  ?>
						<h4 class="text-center">No comments added yet ! <br>
						 Be the first one to comment on this post</h4>
						<?php   } else { for ($i=0; $i < count($comments['Comments']) ; $i++) {  ?>
						<div class="row mt-2">
							<div class="col-md-2">
								<img src="images/default.png" alt=" " class="img-responsive img-thumbnail" style="max-width:100px; max-height:100px;">
							</div>
							<div class="col-md-10">
								<h4><a href="#"><?php  echo $comments['Comments'][$i]['Name'];  ?></a></h4>
								<p><?php  echo $comments['Comments'][$i]['Date'];  ?></p>
								<p class="mt-1"><?php  echo $comments['Comments'][$i]['Content'];  ?></p>
							</div>
						</div>
						<?php  } }  ?>
					</div>
				</div>

					<div editable="editable" data-panel="text" class="pt-4 _603bf7b208b7e _603f5bbb0cb66" data-panelID="_603f5bbb0cb66">
						<h3>Leave Your Comment</h3>
					</div>
					<form id="comment_form" editable="editable" data-panel="form" class="_603f5bbb0cc99" data-panelID="_603f5bbb0cc99">
						<div class="row pt-3 _603f5bbb0cdc7" editable="editable" data-panel="row" data-panelID="_603f5bbb0cdc7">
							<div class="col-md-6 _603f5bbb0cefb" editable="editable" data-panel="column" data-panelID="_603f5bbb0cefb">
								<div class="form-group">
									<input editable="editable" data-panel="input" class="form-control _603f5bbb0d02a" type="text" name="name" placeholder="Name" required="" data-panelID="_603f5bbb0d02a">
								</div>
							</div>
							<div class="col-md-6 _603f5bbb0d161" editable="editable" data-panel="column" data-panelID="_603f5bbb0d161">
								<div class="form-group">
									<input editable="editable" data-panel="input" class="form-control _603f5bbb0d293" type="email" name="email" placeholder="Email" required="" data-panelID="_603f5bbb0d293">
								</div>
							</div>
						</div>
						<div class="row _603f5bbb0d3cb" editable="editable" data-panel="row" data-panelID="_603f5bbb0d3cb">
							<div class="col-md-12 _603f5bbb0d501" editable="editable" data-panel="column" data-panelID="_603f5bbb0d501">
								<div class="form-group">
									<textarea editable="editable" data-panel="textarea" class="form-control _603f5bbb0d62e" name="message" placeholder="Your comment here..." required="" rows="10" data-panelID="_603f5bbb0d62e"></textarea>
								</div>
							</div>
						</div>
						<div class="row _603f5bbb0d766" editable="editable" data-panel="row" data-panelID="_603f5bbb0d766">
							<div class="col-md-12 _603f5bbb0d897" editable="editable" data-panel="column" data-panelID="_603f5bbb0d897">
								<div class="container-fluid text-right _603f5bbb0d9c5" editable="editable" data-panel="container" data-panelID="_603f5bbb0d9c5">
									<?php?>>
									<button editable="editable" data-panel="button" type="submit" class="btn btn-primary btn-lg _603f5bbb0dafb" data-panelID="_603f5bbb0dafb">Submit comment </button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="col-md-3">
				<div class="w3ls_popular_posts">
					<h3>Recent Posts</h3>
					<?php   echo $blog->GetRecentPosts(3);   ?>
				</div>
				
				<div class="w3l_categories">
					<h3>Categories</h3>
					<ul>
						<?php   echo $blog->getCategories();   ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>

	<!-- footer -->
<footer class="mt-5 bg-dark text-white _603f5bbb0dc47" editable="editable" data-panel="footer" data-panelID="_603f5bbb0dc47">
	<div class="container p-5 _603f5bbb0dd92" editable="editable" data-panel="container" data-panelID="_603f5bbb0dd92">
		<div class="row pt-4 _603f5bbb0dee0" editable="editable" data-panel="row" data-panelID="_603f5bbb0dee0">
			<div class="col-md-4 text-left _603f5bbb0e01d" editable="editable" data-panel="column" data-panelID="_603f5bbb0e01d">
				<div editable="editable" data-panel="text" class="_603f5bbb0e14a" data-panelID="_603f5bbb0e14a">
					<h3>Contact Information</h3>
					<p></p>
<p></p>
					<p>22 Russell Street, Victoria ,Melbourne AUSTRALIA </p>
<p><a href="#">E: info [at] domain.com</a> </p>
<p>P: +254 2564584 / +542 824565</p>
<p><a href="#">W: www.w3layouts.com</a></p>
				</div>
			</div>
			<div class="col-md-4 text-center _603f5bbb0e27b" editable="editable" data-panel="column" data-panelID="_603f5bbb0e27b">
				<div editable="editable" data-panel="text" class="_603f5bbb0e3df" data-panelID="_603f5bbb0e3df">
					<h2>Fashion Blog</h2>
<p>Lorem ipsum dolor sit amet consectetur adipisicing elit sedc dnmo eiusmod tempor incididunt ut labore et dolore magna aliqua uta enim ad minim ven iam quis nostrud exercitation ullamco labor nisi ut aliquip exea commodo consequat duis aute </p>
				</div>
			</div>
			<div class="col-md-4 text-right _603f5bbb0e692" editable="editable" data-panel="column" data-panelID="_603f5bbb0e692">
				<div editable="editable" data-panel="text" class="_603f5bbb0eaf2" data-panelID="_603f5bbb0eaf2">
					<h3>Usefull links</h3>
<p><a href="home">Home</a></p>
<p><a href="contact">Contact</a><br></p>
<p><a href="legal">Legal documents</a></p>
				</div>
			</div>
		</div>
	</div>
	<div class="container p-3 _603f5bbb0ecbe" editable="editable" data-panel="container" data-panelID="_603f5bbb0ecbe">
		<div class="row _603f5bbb0ee5f" editable="editable" data-panel="row" data-panelID="_603f5bbb0ee5f">
			<div class="col-md-12 _603f5bbb0efd1" editable="editable" data-panel="column" data-panelID="_603f5bbb0efd1">
				<div class="container p-3 text-center _603f5bbb0f124" editable="editable" data-panel="container" data-panelID="_603f5bbb0f124">
					<div class="text-white _603f5bbb0f26d" editable="editable" data-panel="text" data-panelID="_603f5bbb0f26d">
						© 2020 Fashion Blog . All Rights Reserved . Design by <a href="http://w3layouts.com/">W3layouts</a> . Powered by <a href="https://rosance.com/" target="_blank">Rosance</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>
<!-- SCRIPTS -->
<?php   echo $builder->buildJS();   ?>
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
	

