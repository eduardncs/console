
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
        <nav class="navbar navbar-expand-lg navbar-light bg-white _6007f7ff9848c py-4 _603f9b9251c88" editable="editable" data-panel="header" data-panelID="_603f9b9251c88">
            <div class="container">
                <div class="_6d07f7ff9843c _603f9b9251dff" editable="editable" data-panel="text" data-panelID="_603f9b9251dff"><a class="navbar-brand" href="home"><b>Start Bootstrap</b></a></div>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <?php   
                    echo $builder->buildMenu();
                     ?>
                </div>
            </div>
        </nav>
<section editable="editable" data-panel="section" style="background-image:url(images/1.jpg); background-size:cover; height:350px;" class="_603f9b9251f1f" data-panelID="_603f9b9251f1f">
		<div class="container _603f9b925203c" editable="editable" data-panel="container" data-panelID="_603f9b925203c">
			<div class="row h-100 _603f9b9252169" editable="editable" data-panel="row" data-panelID="_603f9b9252169">
				<div class="col-md-12 text-center _603f9b9252285" editable="editable" data-panel="column" data-panelID="_603f9b9252285">
					<div editable="editable" data-panel="text" class="_603f9b92523a2" data-panelID="_603f9b92523a2">
						<font style="color:#ffffff"><h3><span style="font-size: 64px;"><b>Fashion Blog</b></span></h3>
<h3><span style="font-size: 64px;"><b><br></b></span></h3>
<p><span style="font-size: 28px;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's dummy.</span> </p></font>
					</div>
				</div>
			</div>
		</div>
</section>
	<!-- //banner -->
<section class="pt-4 mb-2 _603f9b92524c4" editable="editable" data-panel="section" data-panelID="_603f9b92524c4">
	<div class="container _603f9b92525df" editable="editable" data-panel="container" data-panelID="_603f9b92525df">
		<div class="row _603f9b92526ee" editable="editable" data-panel="row" data-panelID="_603f9b92526ee">
			<div class="col-md-9 _603f9b92527fc" editable="editable" data-panel="column" data-panelID="_603f9b92527fc">
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
				<div class="bg-dark container py-3 _603f9b9252912" editable="editable" data-panel="container" data-panelID="_603f9b9252912">
					<div class="row _603f9b9252a22" editable="editable" data-panel="row" data-panelID="_603f9b9252a22">
						<div class="col-md-2 _603f9b9252b2f" editable="editable" data-panel="column" data-panelID="_603f9b9252b2f">
						<?php  $author = $blog->getAuthor($post['Array']['Author']);  ?>
							<?php?> alt="" class="img-responsive">
						</div>
						<div class="col-md-10 text-light _603f9b9252c45" editable="editable" data-panel="column" data-panelID="_603f9b9252c45">
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

					<div editable="editable" data-panel="text" class="pt-4 _603bf7b208b7e _603f9b9252d53" data-panelID="_603f9b9252d53">
						<h3>Leave Your Comment</h3>
					</div>
					<form id="comment_form" editable="editable" data-panel="form" class="_603f9b9252e63" data-panelID="_603f9b9252e63">
						<div class="row pt-3 _603f9b9252f6d" editable="editable" data-panel="row" data-panelID="_603f9b9252f6d">
							<div class="col-md-6 _603f9b9253086" editable="editable" data-panel="column" data-panelID="_603f9b9253086">
								<div class="form-group">
									<input editable="editable" data-panel="input" class="form-control _603f9b9253193" type="text" name="name" placeholder="Name" required="" data-panelID="_603f9b9253193">
								</div>
							</div>
							<div class="col-md-6 _603f9b92532a9" editable="editable" data-panel="column" data-panelID="_603f9b92532a9">
								<div class="form-group">
									<input editable="editable" data-panel="input" class="form-control _603f9b92533b8" type="email" name="email" placeholder="Email" required="" data-panelID="_603f9b92533b8">
								</div>
							</div>
						</div>
						<div class="row _603f9b92534d5" editable="editable" data-panel="row" data-panelID="_603f9b92534d5">
							<div class="col-md-12 _603f9b92535e3" editable="editable" data-panel="column" data-panelID="_603f9b92535e3">
								<div class="form-group">
									<textarea editable="editable" data-panel="textarea" class="form-control _603f9b92536f0" name="message" placeholder="Your comment here..." required="" rows="10" data-panelID="_603f9b92536f0"></textarea>
								</div>
							</div>
						</div>
						<div class="row _603f9b9253805" editable="editable" data-panel="row" data-panelID="_603f9b9253805">
							<div class="col-md-12 _603f9b9253913" editable="editable" data-panel="column" data-panelID="_603f9b9253913">
								<div class="container-fluid text-right _603f9b9253a1f" editable="editable" data-panel="container" data-panelID="_603f9b9253a1f">
									<?php?>>
									<button editable="editable" data-panel="button" type="submit" class="btn btn-primary btn-lg _603f9b9253d5e" data-panelID="_603f9b9253d5e">Submit comment </button>
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
<footer class="mt-5 bg-dark text-white _603f9b925418e" editable="editable" data-panel="footer" data-panelID="_603f9b925418e">
	<div class="container p-5 _603f9b925433b" editable="editable" data-panel="container" data-panelID="_603f9b925433b">
		<div class="row pt-4 _603f9b9254476" editable="editable" data-panel="row" data-panelID="_603f9b9254476">
			<div class="col-md-4 text-left _603f9b925459d" editable="editable" data-panel="column" data-panelID="_603f9b925459d">
				<div editable="editable" data-panel="text" class="_603f9b92546e8" data-panelID="_603f9b92546e8">
					<h3>Contact Information</h3>
					<p></p>
<p></p>
					<p>22 Russell Street, Victoria ,Melbourne AUSTRALIA </p>
<p><a href="#">E: info [at] domain.com</a> </p>
<p>P: +254 2564584 / +542 824565</p>
<p><a href="#">W: www.w3layouts.com</a></p>
				</div>
			</div>
			<div class="col-md-4 text-center _603f9b9254811" editable="editable" data-panel="column" data-panelID="_603f9b9254811">
				<div editable="editable" data-panel="text" class="_603f9b925492f" data-panelID="_603f9b925492f">
					<h2>Fashion Blog</h2>
<p>Lorem ipsum dolor sit amet consectetur adipisicing elit sedc dnmo eiusmod tempor incididunt ut labore et dolore magna aliqua uta enim ad minim ven iam quis nostrud exercitation ullamco labor nisi ut aliquip exea commodo consequat duis aute </p>
				</div>
			</div>
			<div class="col-md-4 text-right _603f9b9254a47" editable="editable" data-panel="column" data-panelID="_603f9b9254a47">
				<div editable="editable" data-panel="text" class="_603f9b9254b60" data-panelID="_603f9b9254b60">
					<h3>Usefull links</h3>
<p><a href="home">Home</a></p>
<p><a href="contact">Contact</a><br></p>
<p><a href="legal">Legal documents</a></p>
				</div>
			</div>
		</div>
	</div>
	<div class="container p-3 _603f9b9254c7c" editable="editable" data-panel="container" data-panelID="_603f9b9254c7c">
		<div class="row _603f9b9254d95" editable="editable" data-panel="row" data-panelID="_603f9b9254d95">
			<div class="col-md-12 _603f9b9254eb2" editable="editable" data-panel="column" data-panelID="_603f9b9254eb2">
				<div class="container p-3 text-center _603f9b9254fcf" editable="editable" data-panel="container" data-panelID="_603f9b9254fcf">
					<div class="text-white _603f9b92550ef" editable="editable" data-panel="text" data-panelID="_603f9b92550ef">
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
	

