
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
        <nav class="navbar navbar-expand-lg navbar-light bg-white _6007f7ff9848c py-4 _603f91215e9ed" editable="editable" data-panel="header" data-panelID="_603f91215e9ed">
            <div class="container">
                <div class="_6d07f7ff9843c _603f91215eb4b" editable="editable" data-panel="text" data-panelID="_603f91215eb4b"><a class="navbar-brand" href="home"><b>Start Bootstrap</b></a></div>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <?php   
                    echo $builder->buildMenu();
                     ?>
                </div>
            </div>
        </nav>

	<section editable="editable" data-panel="section" style="background-image:url(images/1.jpg); background-size:cover; height:350px;" class="_603f91215ec6e" data-panelID="_603f91215ec6e">
		<div class="container _603f91215f5cd" editable="editable" data-panel="container" data-panelID="_603f91215f5cd">
			<div class="row h-100 _603f91215f776" editable="editable" data-panel="row" data-panelID="_603f91215f776">
				<div class="col-md-12 text-center _603f91215f8e8" editable="editable" data-panel="column" data-panelID="_603f91215f8e8">
					<div editable="editable" data-panel="text" class="_603f91215fa2c" data-panelID="_603f91215fa2c">
						<font style="color:#ffffff"><h3><span style="font-size: 64px;"><b>Fashion Blog</b></span></h3>
<h3><span style="font-size: 64px;"><b><br></b></span></h3>
<p><span style="font-size: 28px;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's dummy.</span> </p></font>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section editable="editable" data-panel="section" class="_603f91215fb50" data-panelID="_603f91215fb50">
		<div class="container _603f91215fc69" editable="editable" data-panel="container" data-panelID="_603f91215fc69">
			<div class="row _603f91215fd84" editable="editable" data-panel="row" data-panelID="_603f91215fd84">
				<div class="col-md-12 _603f91215fead" editable="editable" data-panel="section" data-panelID="_603f91215fead">
					<div class="text-center p-5 _603f91215ffcb" editable="editable" data-panel="text" data-panelID="_603f91215ffcb">
						<h1> Contact </h1>
						<h4><font style="color:gray;">Nam tempus loboritis sem non ornare in aliquet egestas, nisi mi vestibulum.</font></h4>
					</div>
				</div>
			</div>

			<div class="row _603f9121600f6" editable="editable" data-panel="row" data-panelID="_603f9121600f6">
				<div class="col-md-12 _603f912160313" editable="editable" data-panel="column" data-panelID="_603f912160313">
					<iframe editable="editable" data-panel="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3152.070907689272!2d144.96551661539212!3d-37.81180807975275!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642c99e207041%3A0xc358d059bfe29278!2sRussell+St%2C+Melbourne+VIC%2C+Australia!5e0!3m2!1sen!2sin!4v1486986489826" frameborder="0" style="border:none; width:100%; height:100%; min-height:500px;" allowfullscreen class="_603f91216048d" data-panelID="_603f91216048d"></iframe>
				</div>
			</div>

			<div class="row _603f9121605bf" editable="editable" data-panel="row" data-panelID="_603f9121605bf">
				<div class="col-md-8 text-center _603f9121606e2" editable="editable" data-panel="column" data-panelID="_603f9121606e2">
					<div class="container _603f912160803" editable="editable" data-panel="container" data-panelID="_603f912160803">
						<form id="contact_form" editable="editable" data-panel="form" class="_603f912160920" data-panelID="_603f912160920">
							<div class="form-group">
								<span class="input input--ichiro">
									<input editable="editable" data-panel="input" class="input__field input__field--ichiro _603f912160a44" name="name" type="text" id="input-25" placeholder="Your name" required="" data-panelID="_603f912160a44">
									<label class="input__label input__label--ichiro" for="input-25">
										<span class="input__label-content input__label-content--ichiro">Your Name</span>
									</label>
								</span>
							</div>
							<div class="form-group">
								<span class="input input--ichiro">
									<input class="input__field input__field--ichiro" name="email" type="email" id="input-26" placeholder="Your email" required="">
									<label editable="editable" data-panel="input" class="input__label input__label--ichiro _603f912160b6a" for="input-26" data-panelID="_603f912160b6a">
										<span class="input__label-content input__label-content--ichiro">Your Email</span>
									</label>
								</span>
							</div>
							<div class="form-group">
								<span class="input input--ichiro">
									<input editable="editable" data-panel="input" class="input__field input__field--ichiro _603f912160c90" name="subject" type="text" id="input-27" placeholder="Subject" required="" data-panelID="_603f912160c90">
									<label class="input__label input__label--ichiro" for="input-27">
										<span class="input__label-content input__label-content--ichiro">Subject</span>
									</label>
								</span>
							</div>
							<div class="form-group">
								<textarea editable="editable" data-panel="input" name="message" class="form-control _603f912160dc9" placeholder="Your message here..." required="" rows="10" data-panelID="_603f912160dc9"></textarea>
							</div>
							<div class="text-right">
								<button editable="editable" data-panel="button" type="submit" class="btn btn-primary _603f912160ee7" data-panelID="_603f912160ee7">Send message</button>
							</div>
						</form>
					</div>
				</div>
				<div class="col-md-4 mt-2 _603f912161002" editable="editable" data-panel="column" data-panelID="_603f912161002">
					<div class="container p-4 _603f912161115" editable="editable" data-panel="container" data-panelID="_603f912161115">
						<div class="div _603f91216123a" editable="editable" data-panel="text" data-panelID="_603f91216123a">
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
