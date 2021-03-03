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
	<section editable="editable" data-panel="section">
		<div class="container" editable="editable" data-panel="container">
			<div class="row" editable="editable" data-panel="row">
				<div class="col-md-12" editable="editable" data-panel="section">
					<div class="text-center p-5" editable="editable" data-panel="text">
						<h1> Contact </h1>
						<h4><font style="color:gray;">Nam tempus loboritis sem non ornare in aliquet egestas, nisi mi vestibulum.</font></h4>
					</div>
				</div>
			</div>

			<div class="row" editable="editable" data-panel="row">
				<div class="col-md-12" editable="editable" data-panel="column">
					<iframe editable="editable" data-panel="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3152.070907689272!2d144.96551661539212!3d-37.81180807975275!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642c99e207041%3A0xc358d059bfe29278!2sRussell+St%2C+Melbourne+VIC%2C+Australia!5e0!3m2!1sen!2sin!4v1486986489826"  frameborder="0" style="border:none; width:100%; height:100%; min-height:500px;" allowfullscreen></iframe>
				</div>
			</div>

			<div class="row" editable="editable" data-panel="row">
				<div class="col-md-8 text-center" editable="editable" data-panel="column">
					<div class="container" editable="editable" data-panel="container">
						<form id="contact_form" editable="editable" data-panel="form">
							<div class="form-group">
								<span class="input input--ichiro">
									<input editable="editable" data-panel="input" class="input__field input__field--ichiro" name="name" type="text" id="input-25" placeholder="Your name" required="">
									<label class="input__label input__label--ichiro" for="input-25">
										<span class="input__label-content input__label-content--ichiro">Your Name</span>
									</label>
								</span>
							</div>
							<div class="form-group">
								<span class="input input--ichiro">
									<input class="input__field input__field--ichiro" name="email" type="email" id="input-26" placeholder="Your email" required="">
									<label editable="editable" data-panel="input" class="input__label input__label--ichiro" for="input-26">
										<span class="input__label-content input__label-content--ichiro">Your Email</span>
									</label>
								</span>
							</div>
							<div class="form-group">
								<span class="input input--ichiro">
									<input editable="editable" data-panel="input" class="input__field input__field--ichiro" name="subject" type="text" id="input-27" placeholder="Subject" required="">
									<label class="input__label input__label--ichiro" for="input-27">
										<span class="input__label-content input__label-content--ichiro">Subject</span>
									</label>
								</span>
							</div>
							<div class="form-group">
								<textarea editable="editable" data-panel="input" name="message" class="form-control" placeholder="Your message here..." required="" rows="10"></textarea>
							</div>
							<div class="text-right">
								<button editable="editable" data-panel="button" type="submit" class="btn btn-primary">Send message</button>
							</div>
						</form>
					</div>
				</div>
				<div class="col-md-4 mt-2" editable="editable" data-panel="column">
					<div class="container p-4" editable="editable" data-panel="container">
						<div class="div" editable="editable" data-panel="text">
							<h3>Contact Info</h3>
							<p><br/></p>
							<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit velit justo.</span>
							<p><br/></p>
							<address>
									<p> <i class="fas fa-envelope mr-2"></i> : <a href="mailto:info@display.com">info@display.com</a></p><br/>
									<p> <i class="fas fa-phone mr-2"></i> : 1.306.222.4545</p><br/>
								<p><i class="fas fa-home mr-2"></i> 222 2nd Ave South</p><br/>
								<p>Saskabush, SK   S7M 1T6</p>
								
							</address>
						</div>
					</div>
				</div>
			</div>
		</div>
</section>

	<footer class="bg-dark text-white mt-5" editable="editable" data-panel="footer">
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
<?php  echo $builder->buildJS();  ?>
<!-- start-smoth-scrolling -->
<script type="text/javascript">
jQuery(document).ready(function($) {
	$(".scroll").click(function(event){		
		event.preventDefault();
		$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
	});
});
$(document).ready(function() { $().UItoTop({ easingType: 'easeOutQuart' }); });
	</script>
	

