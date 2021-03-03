<?php
require_once("core/ssk.req.class.php");
require_once("core/main.class.php");
require_once("core/builder.class.php");
require_once("core/database.class.php");
$main = new Main();
$builder = new Builder();
$info = $main->getInfo();
echo $builder->buildHead();
echo $builder->buildBody(true);
?>
<section class="w3l-bootstrap-header">
  <nav class="navbar navbar-expand-lg navbar-light py-lg-3 py-2" editable="editable" datapanel="header">
    <div class="container">
      <a class="navbar-brand" editable="editable" datapanel="text" href="home">My Website</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon fa fa-bars"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
         <?php echo $builder->buildMenu(); ?>        
      </div>
    </div>
  </nav>
</section>
 <section class="w3l-main-banner" style="background: url(images/fa3ad0aace28ae2fa65045dbcc28cd5c.jpg);" id="home" editable="editable" datapanel="section">
  <div class="companies20-content">
    <div class="companies-wrapper">
        <div class="item">
            <div class="slider-info banner-view text-center">
              <div class="banner-info container">
                  <div style="display:inline-block;" editable="editable" datapanel="text">
                    <h3 class="banner-text mt-5">Hello, I’m Rosance</h3>
                      <p class="my-4 mb-5">Photographer</p><br>
                  </div>
              </div>
            </div>
          
        </div>
    </div>
  </div>
</section>
<section class="w3l-about ">
<div class="skills-bars py-5">
 <div class="container py-md-3">
  <div class="heading text-center mx-auto" editable="editable" datapanel="text">
    <h3 class="head">Welcome To My Site</h3>
    <p class="my-3 head"> Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;
      Nulla mollis dapibus nunc, ut rhoncus
      turpis sodales quis. Integer sit amet mattis quam.Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla mollis dapibus nunc</p>
      
    </div>
 </div>
</div>
 </section>
<section class="w3l-feature-3" style="background: url(images/62ecf3e8328cd47bc43de9470eb992b9.jpg) no-repeat center;" id="features" editable="editable" datapanel="section">
	<div class="grid top-bottom">
		<div class="container">
			<div class="heading text-center mx-auto" editable="editable" datapanel="text">
                <h3 class="head text-white">I'm Available For Hire</h3>
                <p class="my-3 head text-white"> Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;
                  Nulla mollis dapibus nunc, ut rhoncus
                  turpis sodales quis. Integer sit amet mattis quam.</p>
              </div>
			<div class="middle-section grid-column text-center mt-5 pt-3">
				<div class="three-grids-columns" editable="editable" datapanel="text"><h4>Design</h4><p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla mollis dapibus nunc</p>
				</div>
				<div class="three-grids-columns" editable="editable" datapanel="text"><h4>Marketing</h4><p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla mollis dapibus nunc</p>
				</div>
				<div class="three-grids-columns" editable="editable" datapanel="text"><h4>Photography</h4><p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla mollis dapibus nunc</p>
				</div>
			</div>
		</div>
	</div>
</section>
<div class="products-4" id="portfolio" editable="editable" datapanel="portofolio">
    <div id="products4-block" class="text-center">
        <div class="container">
            <div class="heading text-center mx-auto mb-5" editable="editable" datapanel="text">
                <h3 class="head">I Love What I Do</h3>
                <p class="my-3 head"> Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;
                Nulla mollis dapibus nunc, ut rhoncus
                turpis sodales quis. Integer sit amet mattis quam.</p>
              </div>
              <div class="d-grid grid-col-3">
            <?php echo $builder->buildPortofolio(); ?>


            </div>
        </div>
    </div>
</div>
 <section class="w3l-footer-29-main" id="footer" editable="editable" datapanel="footer">
  <div class="footer-29 text-center">
      <div class="container">
        
      <div class="main-social-footer-29" editable="editable" datapanel="socialmenu">
      <?php echo $builder->buildSocial(); ?>  
      </div>
          <div class="bottom-copies text-center" editable="editable" datapanel="text">
              <p class="copy-footer-29">© 2020 My Website. All rights reserved | Designed by <a href="https://w3layouts.com">W3layouts</a>&nbsp;. Powered by&nbsp;<a href="https://rosance.com" target="_blank">Rosance</a></p>
               
          </div>
      </div>
  </div>
  <button onclick="topFunction()" id="movetop" title="Go to top">
              <span class="fa fa-angle-up"></span>
                 </button>

<?php echo $builder->buildJS(); ?>

                 <script>
                     // When the user scrolls down 20px from the top of the document, show the button
                     window.onscroll = function () {
                         scrollFunction()
                     };
              
                     function scrollFunction() {
                         if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                             document.getElementById("movetop").style.display = "block";
                         } else {
                             document.getElementById("movetop").style.display = "none";
                         }
                     }
              
                     // When the user clicks on the button, scroll to the top of the document
                     function topFunction() {
                         document.body.scrollTop = 0;
                         document.documentElement.scrollTop = 0;
                     }
                 </script>
</section>

<script>
    $(function () {
      $('.navbar-toggler').click(function () {
        $('body').toggleClass('noscroll');
      })
    });
  </script>
  <?php echo $builder->buildBody(false); ?>
