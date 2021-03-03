<?php
require_once("core/ssk.req.class.php");
require_once("core/main.class.php");
require_once("core/builder.class.php");
require_once("core/database.class.php");
require_once("core/blog.class.php");
$main = new Main();
$builder = new Builder();
$blog = new Blog();
echo $builder->buildHead();
?>
<body>

<div class="preloader">
     <div class="sk-spinner sk-spinner-wordpress">
          <span class="sk-inner-circle"></span>
     </div>
</div>

<!-- Navigation section  -->
<nav class="navbar navbar-default" role="navigation" editable="editable" datapanel="header">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <div class="navbar-brand">
          <a editable="editable" datapanel="text" href="home">Rosance</a>
     </div>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
           <?php echo $builder->buildMenu(); ?>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container -->
</nav>
<!-- Home Section -->

<section id="home" style="background: url(images/ffd6b064263ff2b955f2e2f06356474f.jpg) no-repeat; height: 100vh;" class="parallax-section" editable="editable" datapanel="section">
     <div class="overlay"></div>
     <div id="particles-js"></div>
     <div class="container">
          <div class="row">
               <div class="col-md-12 col-sm-12">
                    <div style="display:inline-block;" editable="editable" datapanel="text"><h1>Welcome to Rosance</h1><br><h4>And i can be edited easily. Just click on me!</h4></div>
               </div>
          </div>
     </div>
</section>

<!-- Blog Section -->
<section id="blog">
     <div class="container">
          <div class="row">
          <?php $blog->getPosts(true); ?>
          </div>
     </div>
</section>

<!-- Footer Section -->

<footer editable="editable" datapanel="footer">
     <div class="container">
          <div class="row">

          <div class="col-md-12" align='center'><div style="display:inline-block;"  editable="editable" datapanel="text"><h2>Follow us</h2></div></div>
          <div class="col-md-12" align="center"><?php echo $builder->buildSocial();?></div>
               <div class="clearfix col-md-12 col-sm-12">
                    <hr>
               </div>

               <div class="col-md-12 col-sm-12 text-center" align="center">
                    <div  style="display:inline-block;" editable="editable" datapanel="text"><p>Copyright © 2020 by Yoursite , Template created by <a href="https://tooplate.com" target="_blank">TooPlate</a> , powered by <a href="https://rosance.com" target="_blank">Rosance</a></p></div>
               </div>
          </div>
     </div>
</footer>

<!-- Back top -->
<a href="#back-top" class="go-top"><i class="fa fa-angle-up"></i></a>

<!-- SCRIPTS -->
<?php echo $builder->buildJS(); ?>

</body>
</html>