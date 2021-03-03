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
<section class="w3l-contact-breadcrum" style="background: url(images/62ecf3e8328cd47bc43de9470eb992b9.jpg);" editable="editable" datapanel="section">
  <div class="breadcrum-bg">
    <div class="container py-5">
      <p><a href="home">Home</a> &nbsp; / &nbsp; Contact</p>
     </div>
  </div>
</section>
<section class="w3l-contacts-12" id="contact">
    <div class="contact-top pt-5">
        <div class="container py-md-3">
            
            <div class="row cont-main-top">
               <!-- contact form -->
               <div class="contacts12-main col-lg-6 ">
                   
                <form id="contact_form" class="main-input">
                    <div class="top-inputs d-grid">
                        <input type="text" placeholder="Name" name="name" id="w3lName" required="">
                       
                    </div>

                    <div class="top-inputs d-grid">
                        <input type="email" name="email" placeholder="Email" id="w3lSender" required="">
                    </div>

                    <div class="top-inputs d-grid">
                        <input type="text" placeholder="Subject" name="subject" id="w3lSubject" required="">
                    </div>

                    <textarea placeholder="Message" name="message" id="w3lMessage" required=""></textarea>
                    <div class="text-right">
                        <button type="submit" class="btn btn-theme2">Submit Now</button>
                    </div>
                </form>
            </div>
            <!-- //contact form -->
                <!-- contact address -->
                <div class="contact col-lg-6 mt-lg-0 mt-5">
                    <div class="cont-subs">
                        <div class="cont-add" editable="editable" datapanel="text">
                            <div class="cont-add-lft">
                                <span class="fas fa-map-marker" aria-hidden="true"></span>
                           </div>
                           <div class="cont-add-rgt">
                            <p class="contact-text-sub">PO Box 1212, London, UK</p>
                        </div>
                      
                    </div>
                        <div class="cont-add add-2" editable="editable" datapanel="text">
                            <div class="cont-add-lft">
                                <span class="fas fa-envelope" aria-hidden="true"></span>
                           </div> 
                           <div class="cont-add-rgt" editable="editable" datapanel="text">
                          <a href="mailto:info@example.com">
                                <p class="contact-text-sub">info@example.com</p>
                            </a>
                        </div>
                       
                    </div>
                        <div class="cont-add" editable="editable" datapanel="text">
                            <div class="cont-add-lft">
                                <span class="fas fa-phone" aria-hidden="true"></span>
                           </div>
                            <div class="cont-add-rgt">
                                 <a href="tel:+7-800-999-800">
                                    <p class="contact-text-sub">+7-800-999-800</p>
                                 </a>
                            </div>
                        </div>
                        <div class="cont-add add-3" editable="editable" datapanel="text">
                            <div class="cont-add-lft">
                                <span class="fas fa-file-pdf" aria-hidden="true"></span>
                           </div>
                            <div class="cont-add-rgt">
                                <a href="#">
                                    <p class="contact-text-sub">Download Resume</p>
                                </a>
                            </div>
                           
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- map -->
        <div class="map">
            <iframe
                src="<?php echo $info['Maps']; ?>"
                frameborder="0" style="border:0;" allowfullscreen=""></iframe>
        </div>
    </div>
</section>
 
 <section class="w3l-footer-29-main" id="footer" editable="editable" datapanel="footer">
  <div class="footer-29 text-center">
      <div class="container">
        
      <div class="main-social-footer-29" editable="editable" datapanel="socialmenu">
      <?php echo $builder->buildSocial(); ?>  
      </div>
          <div class="bottom-copies text-center" editable="editable" datapanel="text">
              <p class="copy-footer-29">© 2020 My Website. All rights reserved | Designed by&nbsp;<a href="https://w3layouts.com/" style="">W3layouts</a>&nbsp;. Powered by&nbsp;<a href="https://rosance.com/" target="_blank" style="">Rosance</a></p>
               
          </div>
      </div>
  </div>
  <button onclick="topFunction()" id="movetop" title="Go to top">
              <span class="fa fa-angle-up"></span>
                 </button>

<?php echo $builder->buildJS(); ?>

                 <script>
    function showerror(message)
      {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: message,
          scrollbarPadding: false,
          allowOutsideClick: false
      })
      }
      function showsuccess(message, static=true, redirectpage)
      {
        Swal.fire({
          icon: 'success',
          title: 'Success! :)',
          html: message,
          scrollbarPadding: false,
          confirmButtonText: 'Great!',
          allowOutsideClick: false
       }).then(() =>
      {
          if(!static)
            window.location.href = redirectpage;
      });}

      $("#contact_form").submit(function(event){
        event.preventDefault();
        var values = $("#contact_form").serialize();
        $.ajax({
          url:"core/main.class.php",
          type: "post",
          data:values,
          beforeSend: function(){ $(".preloader").show(); },
          success: function(data){
            $(".preloader").hide();
            $("body").append(data);
            //location.reload();
          }
        })
      });
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


