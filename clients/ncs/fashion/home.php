
<?php  require_once("core/ssk.req.class.php");
require_once("core/main.class.php");
require_once("core/builder.class.php");
require_once("core/database.class.php");
require_once("core/blog.class.php");
$main = new Main();
$builder = new Builder();
$blog = new Blog();
$info = $main->getInfo();
echo $builder->buildHead();
echo $builder->buildBody();
 ?>
        <nav class="navbar navbar-expand-lg navbar-light bg-white _6007f7ff9848c py-4 _603f5bbb45236" editable="editable" data-panel="header" data-panelID="_603f5bbb45236">
            <div class="container">
                <div class="_6d07f7ff9843c _603f5bbb4547a" editable="editable" data-panel="text" data-panelID="_603f5bbb4547a"><a class="navbar-brand" href="home"><b>Start Bootstrap</b></a></div>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <?php   
                    echo $builder->buildMenu();
                     ?>
                </div>
            </div>
        </nav>

	<section class="vh-100 _603f5bbb455da" editable="editable" data-panel="section" style="background-image:url(images/1.jpg); background-size:cover;" data-panelID="_603f5bbb455da">
		<div class="container _603f5bbb4572e" editable="editable" data-panel="container" data-panelID="_603f5bbb4572e">
			<div class="row h-100 _603f5bbb45873" editable="editable" data-panel="row" data-panelID="_603f5bbb45873">
				<div class="col-md-12 text-center _603f5bbb459ab" editable="editable" data-panel="column" data-panelID="_603f5bbb459ab">
					<div editable="editable" data-panel="text" class="_603f5bbb45ade" data-panelID="_603f5bbb45ade">
						<font style="color:#ffffff"><h3><span style="font-size: 64px;"><b>Fashion Blog</b></span></h3>
<h3><span style="font-size: 64px;"><b><br></b></span></h3>
<p><span style="font-size: 28px;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's dummy.</span> </p></font>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- //main-slider -->
	<!-- //top-header and slider -->
	<section class="mt-2 _603f5bbb45c0f" editable="editable" data-panel="section" data-panelID="_603f5bbb45c0f">
		<div class="container _603f5bbb45d47" editable="editable" data-panel="container" data-panelID="_603f5bbb45d47">
			<div class="row _603f5bbb45e7a" editable="editable" data-panel="row" data-panelID="_603f5bbb45e7a">
				<div class="col-md-9 btm-wthree-left _603f5bbb45faa" editable="editable" data-panel="column" data-panelID="_603f5bbb45faa">
					<div class="container pt-3 _603f5bbb460de" editable="editable" data-panel="container" data-panelID="_603f5bbb460de">
						<?php  echo $blog->GetPosts(false);  ?>
					</div>
				</div>
				<div class="col-md-3 w3agile_blog_left _603f5bbb46211" editable="editable" data-panel="column" data-panelID="_603f5bbb46211">
					<div class="w3ls_popular_posts">
						<h3>Recent Posts</h3>
						<?php  echo $blog->GetRecentPosts(3);  ?>
					</div>
					
					<div class="w3l_categories pb-4 mb-2">
						<h3>Categories</h3>
							<?php  echo $blog->getCategories();  ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- footer -->
	<footer class="bg-dark text-white _603f5bbb4634c" editable="editable" data-panel="footer" data-panelID="_603f5bbb4634c">
		<div class="container p-5 _603f5bbb464a7" editable="editable" data-panel="container" data-panelID="_603f5bbb464a7">
			<div class="row pt-4 _603f5bbb465fe" editable="editable" data-panel="row" data-panelID="_603f5bbb465fe">
				<div class="col-md-4 _603f5bbb4674f" editable="editable" data-panel="column" data-panelID="_603f5bbb4674f">
					<div editable="editable" data-panel="text" class="_603f5bbb46884" data-panelID="_603f5bbb46884">
						<h3>Contact Information</h3>
						<p></p>
<p></p>
						<p>22 Russell Street, Victoria ,Melbourne AUSTRALIA </p>
<p><a href="#">E: info [at] domain.com</a> </p>
<p>P: +254 2564584 / +542 824565</p>
<p><a href="#">W: www.w3layouts.com</a></p>
					</div>
				</div>
				<div class="col-md-4 _603f5bbb469b4" editable="editable" data-panel="column" data-panelID="_603f5bbb469b4">
					<div editable="editable" data-panel="text" class="_603f5bbb46ae9" data-panelID="_603f5bbb46ae9">
						<h2>Fashion Blog</h2>
<p>Lorem ipsum dolor sit amet consectetur adipisicing elit sedc dnmo eiusmod tempor incididunt ut labore et dolore magna aliqua uta enim ad minim ven iam quis nostrud exercitation ullamco labor nisi ut aliquip exea commodo consequat duis aute </p>
					</div>
				</div>
				<div class="col-md-4 _603f5bbb46c34" editable="editable" data-panel="column" data-panelID="_603f5bbb46c34">
					<div editable="editable" data-panel="text" class="_603f5bbb46d82" data-panelID="_603f5bbb46d82">
						<h3>Usefull links</h3>
<p><a href="home">Home</a></p>
<p><a href="contact">Contact</a><br></p>
<p><a href="legal">Legal documents</a></p>
					</div>
				</div>
			</div>
		</div>
		<div class="container p-3 _603f5bbb46ed0" editable="editable" data-panel="container" data-panelID="_603f5bbb46ed0">
			<div class="row _603f5bbb47020" editable="editable" data-panel="row" data-panelID="_603f5bbb47020">
				<div class="col-md-12 _603f5bbb4716f" editable="editable" data-panel="column" data-panelID="_603f5bbb4716f">
					<div class="container p-3 text-center _603f5bbb472ba" editable="editable" data-panel="container" data-panelID="_603f5bbb472ba">
						<div class="text-white _603f5bbb47410" editable="editable" data-panel="text" data-panelID="_603f5bbb47410">
							© 2020 Fashion Blog . All Rights Reserved . Design by <a href="http://w3layouts.com/">W3layouts</a> . Powered by <a href="https://rosance.com/" target="_blank">Rosance</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</footer>

<?php  echo $builder->buildJS();  ?>	

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
	
