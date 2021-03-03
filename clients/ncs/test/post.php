
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
        <nav class="navbar navbar-expand-lg navbar-light bg-white _6007f7ff9848c py-4 _603f912121a12" editable="editable" data-panel="header" data-panelID="_603f912121a12">
            <div class="container">
                <div class="_6d07f7ff9843c _603f912121ced" editable="editable" data-panel="text" data-panelID="_603f912121ced"><a class="navbar-brand" href="home"><b>Start Bootstrap</b></a></div>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <?php   
                    echo $builder->buildMenu();
                     ?>
                </div>
            </div>
        </nav>
<section editable="editable" data-panel="section" style="background-image:url(images/1.jpg); background-size:cover; height:350px;" class="_603f912122383" data-panelID="_603f912122383">
		<div class="container _603f912122530" editable="editable" data-panel="container" data-panelID="_603f912122530">
			<div class="row h-100 _603f912122669" editable="editable" data-panel="row" data-panelID="_603f912122669">
				<div class="col-md-12 text-center _603f9121227c3" editable="editable" data-panel="column" data-panelID="_603f9121227c3">
					<div editable="editable" data-panel="text" class="_603f9121228ee" data-panelID="_603f9121228ee">
						<font style="color:#ffffff"><h3><span style="font-size: 64px;"><b>Fashion Blog</b></span></h3>
<h3><span style="font-size: 64px;"><b><br></b></span></h3>
<p><span style="font-size: 28px;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's dummy.</span> </p></font>
					</div>
				</div>
			</div>
		</div>
</section>
	<!-- //banner -->
<section class="pt-4 mb-2 _603f912122a14" editable="editable" data-panel="section" data-panelID="_603f912122a14">
	<div class="container _603f912122b31" editable="editable" data-panel="container" data-panelID="_603f912122b31">
		<div class="row _603f912122c4a" editable="editable" data-panel="row" data-panelID="_603f912122c4a">
			<div class="col-md-9 _603f912122d6b" editable="editable" data-panel="column" data-panelID="_603f912122d6b">
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
				<div class="bg-dark container py-3 _603f912122e88" editable="editable" data-panel="container" data-panelID="_603f912122e88">
					<div class="row _603f912122f96" editable="editable" data-panel="row" data-panelID="_603f912122f96">
						<div class="col-md-2 _603f9121230aa" editable="editable" data-panel="column" data-panelID="_603f9121230aa">
						<?php  $author = $blog->getAuthor($post['Array']['Author']);  ?>
							<?php?> alt="" class="img-responsive">
						</div>
						<div class="col-md-10 text-light _603f9121231c1" editable="editable" data-panel="column" data-panelID="_603f9121231c1">
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

					<div editable="editable" data-panel="text" class="pt-4 _603bf7b208b7e _603f9121232d6" data-panelID="_603f9121232d6">
						<h3>Leave Your Comment</h3>
					</div>
					<form id="comment_form" editable="editable" data-panel="form" class="_603f9121233ed" data-panelID="_603f9121233ed">
						<div class="row pt-3 _603f912123501" editable="editable" data-panel="row" data-panelID="_603f912123501">
							<div class="col-md-6 _603f912123618" editable="editable" data-panel="column" data-panelID="_603f912123618">
								<div class="form-group">
									<input editable="editable" data-panel="input" class="form-control _603f91212372d" type="text" name="name" placeholder="Name" required="" data-panelID="_603f91212372d">
								</div>
							</div>
							<div class="col-md-6 _603f9121239d1" editable="editable" data-panel="column" data-panelID="_603f9121239d1">
								<div class="form-group">
									<input editable="editable" data-panel="input" class="form-control _603f912123b08" type="email" name="email" placeholder="Email" required="" data-panelID="_603f912123b08">
								</div>
							</div>
						</div>
						<div class="row _603f912123c2c" editable="editable" data-panel="row" data-panelID="_603f912123c2c">
							<div class="col-md-12 _603f912123d49" editable="editable" data-panel="column" data-panelID="_603f912123d49">
								<div class="form-group">
									<textarea editable="editable" data-panel="textarea" class="form-control _603f912123e5e" name="message" placeholder="Your comment here..." required="" rows="10" data-panelID="_603f912123e5e"></textarea>
								</div>
							</div>
						</div>
						<div class="row _603f912123f78" editable="editable" data-panel="row" data-panelID="_603f912123f78">
							<div class="col-md-12 _603f9121240ba" editable="editable" data-panel="column" data-panelID="_603f9121240ba">
								<div class="container-fluid text-right _603f9121241f3" editable="editable" data-panel="container" data-panelID="_603f9121241f3">
									<?php?>>
									<button editable="editable" data-panel="button" type="submit" class="btn btn-primary btn-lg _603f91212432c" data-panelID="_603f91212432c">Submit comment </button>
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
<footer class="mt-5 bg-dark text-white _603f912124468" editable="editable" data-panel="footer" data-panelID="_603f912124468">
	<div class="container p-5 _603f9121245a2" editable="editable" data-panel="container" data-panelID="_603f9121245a2">
		<div class="row pt-4 _603f9121246d9" editable="editable" data-panel="row" data-panelID="_603f9121246d9">
			<div class="col-md-4 text-left _603f9121247e6" editable="editable" data-panel="column" data-panelID="_603f9121247e6">
				<div editable="editable" data-panel="text" class="_603f9121248f7" data-panelID="_603f9121248f7">
					<h3>Contact Information</h3>
					<p></p>
<p></p>
					<p>22 Russell Street, Victoria ,Melbourne AUSTRALIA </p>
<p><a href="#">E: info [at] domain.com</a> </p>
<p>P: +254 2564584 / +542 824565</p>
<p><a href="#">W: www.w3layouts.com</a></p>
				</div>
			</div>
			<div class="col-md-4 text-center _603f912124a11" editable="editable" data-panel="column" data-panelID="_603f912124a11">
				<div editable="editable" data-panel="text" class="_603f912124b28" data-panelID="_603f912124b28">
					<h2>Fashion Blog</h2>
<p>Lorem ipsum dolor sit amet consectetur adipisicing elit sedc dnmo eiusmod tempor incididunt ut labore et dolore magna aliqua uta enim ad minim ven iam quis nostrud exercitation ullamco labor nisi ut aliquip exea commodo consequat duis aute </p>
				</div>
			</div>
			<div class="col-md-4 text-right _603f912124c3e" editable="editable" data-panel="column" data-panelID="_603f912124c3e">
				<div editable="editable" data-panel="text" class="_603f912124d59" data-panelID="_603f912124d59">
					<h3>Usefull links</h3>
<p><a href="home">Home</a></p>
<p><a href="contact">Contact</a><br></p>
<p><a href="legal">Legal documents</a></p>
				</div>
			</div>
		</div>
	</div>
	<div class="container p-3 _603f912124e6c" editable="editable" data-panel="container" data-panelID="_603f912124e6c">
		<div class="row _603f912124f78" editable="editable" data-panel="row" data-panelID="_603f912124f78">
			<div class="col-md-12 _603f912125086" editable="editable" data-panel="column" data-panelID="_603f912125086">
				<div class="container p-3 text-center _603f912125190" editable="editable" data-panel="container" data-panelID="_603f912125190">
					<div class="text-white _603f91212529c" editable="editable" data-panel="text" data-panelID="_603f91212529c">
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
	

