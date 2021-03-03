
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
        <nav class="navbar navbar-expand-lg navbar-light bg-white _6007f7ff9848c py-4 _603f9b928e29d" editable="editable" data-panel="header" data-panelID="_603f9b928e29d">
            <div class="container">
                <div class="_6d07f7ff9843c _603f9b928e3ed" editable="editable" data-panel="text" data-panelID="_603f9b928e3ed"><a class="navbar-brand" href="home"><b>Start Bootstrap</b></a></div>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <?php   
                    echo $builder->buildMenu();
                     ?>
                </div>
            </div>
        </nav>

	<section editable="editable" data-panel="section" style="background-image:url(images/1.jpg); background-size:cover; height:350px;" class="_603f9b928e504" data-panelID="_603f9b928e504">
		<div class="container _603f9b928e631" editable="editable" data-panel="container" data-panelID="_603f9b928e631">
			<div class="row h-100 _603f9b928e744" editable="editable" data-panel="row" data-panelID="_603f9b928e744">
				<div class="col-md-12 text-center _603f9b928e85b" editable="editable" data-panel="column" data-panelID="_603f9b928e85b">
					<div editable="editable" data-panel="text" class="_603f9b928e97f" data-panelID="_603f9b928e97f">
						<font style="color:#ffffff"><h3><span style="font-size: 64px;"><b>Fashion Blog</b></span></h3>
<h3><span style="font-size: 64px;"><b><br></b></span></h3>
<p><span style="font-size: 28px;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's dummy.</span> </p></font>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section editable="editable" data-panel="section" class="_603f9b928ea9b" data-panelID="_603f9b928ea9b">
		<div class="container _603f9b928ebb7" editable="editable" data-panel="container" data-panelID="_603f9b928ebb7">
			<div class="row _603f9b928ecca" editable="editable" data-panel="row" data-panelID="_603f9b928ecca">
				<div class="col-md-12 _603f9b928edf0" editable="editable" data-panel="section" data-panelID="_603f9b928edf0">
					<div class="text-center p-5 _603f9b928ef02" editable="editable" data-panel="text" data-panelID="_603f9b928ef02">
						<h1> Contact </h1>
						<h4><font style="color:gray;">Nam tempus loboritis sem non ornare in aliquet egestas, nisi mi vestibulum.</font></h4>
					</div>
				</div>
			</div>

			<div class="row _603f9b928f016" editable="editable" data-panel="row" data-panelID="_603f9b928f016">
				<div class="col-md-12 _603f9b928f137" editable="editable" data-panel="column" data-panelID="_603f9b928f137">
					<iframe editable="editable" data-panel="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3152.070907689272!2d144.96551661539212!3d-37.81180807975275!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642c99e207041%3A0xc358d059bfe29278!2sRussell+St%2C+Melbourne+VIC%2C+Australia!5e0!3m2!1sen!2sin!4v1486986489826" frameborder="0" style="border:none; width:100%; height:100%; min-height:500px;" allowfullscreen class="_603f9b928f253" data-panelID="_603f9b928f253"></iframe>
				</div>
			</div>

			<div class="row _603f9b928f375" editable="editable" data-panel="row" data-panelID="_603f9b928f375">
				<div class="col-md-8 text-center _603f9b928f49d" editable="editable" data-panel="column" data-panelID="_603f9b928f49d">
					<div class="container _603f9b928f5c5" editable="editable" data-panel="container" data-panelID="_603f9b928f5c5">
						<form id="contact_form" editable="editable" data-panel="form" class="_603f9b928f6e8" data-panelID="_603f9b928f6e8">
							<div class="form-group">
								<span class="input input--ichiro">
									<input editable="editable" data-panel="input" class="input__field input__field--ichiro _603f9b928f807" name="name" type="text" id="input-25" placeholder="Your name" required="" data-panelID="_603f9b928f807">
									<label class="input__label input__label--ichiro" for="input-25">
										<span class="input__label-content input__label-content--ichiro">Your Name</span>
									</label>
								</span>
							</div>
							<div class="form-group">
								<span class="input input--ichiro">
									<input class="input__field input__field--ichiro" name="email" type="email" id="input-26" placeholder="Your email" required="">
									<label editable="editable" data-panel="input" class="input__label input__label--ichiro _603f9b928fa4c" for="input-26" data-panelID="_603f9b928fa4c">
										<span class="input__label-content input__label-content--ichiro">Your Email</span>
									</label>
								</span>
							</div>
							<div class="form-group">
								<span class="input input--ichiro">
									<input editable="editable" data-panel="input" class="input__field input__field--ichiro _603f9b928fbbf" name="subject" type="text" id="input-27" placeholder="Subject" required="" data-panelID="_603f9b928fbbf">
									<label class="input__label input__label--ichiro" for="input-27">
										<span class="input__label-content input__label-content--ichiro">Subject</span>
									</label>
								</span>
							</div>
							<div class="form-group">
								<textarea editable="editable" data-panel="input" name="message" class="form-control _603f9b928fd02" placeholder="Your message here..." required="" rows="10" data-panelID="_603f9b928fd02"></textarea>
							</div>
							<div class="text-right">
								<button editable="editable" data-panel="button" type="submit" class="btn btn-primary _603f9b928fe23" data-panelID="_603f9b928fe23">Send message</button>
							</div>
						</form>
					</div>
				</div>
				<div class="col-md-4 mt-2 _603f9b928ff42" editable="editable" data-panel="column" data-panelID="_603f9b928ff42">
					<div class="container p-4 _603f9b9290061" editable="editable" data-panel="container" data-panelID="_603f9b9290061">
						<div class="div _603f9b929018e" editable="editable" data-panel="text" data-panelID="_603f9b929018e">
							<h3>Contact Info</h3>
							<p><br></p>
							<span>Lorem ipsum dolor sit amet, consectetur adipiscing elit velit justo.</span>
							<p><br></p>
							<address>
									<p> <i class="fas fa-envelope mr-2"></i> : <a href="mailto:info@display.com">info@display.com</a></p>
<br>
									<p> <?php?>text/javascript">
jQuery(document).ready(function($) {
	$(".scroll").click(function(event){		
		event.preventDefault();
		$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
	});
});
$(document).ready(function() { $().UItoTop({ easingType: 'easeOutQuart' }); });
	
	

</p>
</address>
</div>
</div>
</div>
</div>
</div></section>
