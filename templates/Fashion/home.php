<?php require_once("core/ssk.req.class.php");
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

	<section class="vh-100" editable="editable" data-panel="section" style="background-image:url(images/1.jpg); background-size:cover;">
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

	<!-- //main-slider -->
	<!-- //top-header and slider -->
	<section class="mt-2" editable="editable" data-panel="section">
		<div class="container" editable="editable" data-panel="container">
			<div class="row" editable="editable" data-panel="row">
				<div class="col-md-9 btm-wthree-left" editable="editable" data-panel="column">
					<div class="container pt-3" editable="editable" data-panel="container">
						<?php echo $blog->GetPosts(false); ?>
					</div>
				</div>
				<div class="col-md-3 w3agile_blog_left" editable="editable" data-panel="column">
					<div class="w3ls_popular_posts">
						<h3>Recent Posts</h3>
						<?php echo $blog->GetRecentPosts(3); ?>
					</div>
					
					<div class="w3l_categories pb-4 mb-2">
						<h3>Categories</h3>
							<?php echo $blog->getCategories(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- footer -->
	<footer class="bg-dark text-white" editable="editable" data-panel="footer">
		<div class="container p-5" editable="editable" data-panel="container">
			<div class="row pt-4" editable="editable" data-panel="row">
				<div class="col-md-4" editable="editable" data-panel="column">
					<div editable="editable" data-panel="text">
						<h3>Contact Information</h3>
						<p></p><p></p>
						<p>22 Russell Street, Victoria ,Melbourne AUSTRALIA </p><p><a href="#">E: info [at] domain.com</a> </p><p>P: +254 2564584 / +542 824565</p><p><a href="#">W: www.w3layouts.com</a></p>
					</div>
				</div>
				<div class="col-md-4" editable="editable" data-panel="column">
					<div editable="editable" data-panel="text">
						<h2>Fashion Blog</h2><p>Lorem ipsum dolor sit amet consectetur adipisicing elit sedc dnmo eiusmod tempor incididunt ut labore et dolore magna aliqua uta enim ad minim ven iam quis nostrud exercitation ullamco labor nisi ut aliquip exea commodo consequat duis aute&nbsp;</p>
					</div>
				</div>
				<div class="col-md-4" editable="editable" data-panel="column">
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

<?php echo $builder->buildJS(); ?>	

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
	
</body>
</html>