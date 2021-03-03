<script type="text/javascript">
    $("#NCS_REGISTER").submit(function(event) {
    event.preventDefault();
    var values = $("#NCS_REGISTER").serialize();
    $.ajax({
    url: "processors/registration.req.php",
		type: "post",
		data: values,
		beforeSend: function(){$("#overlay").show(); $("#content").hide(); },
	    success: function(data){$("#overlay").hide(); $("#content").show(); $('#ajax').html(data);},
        error: function(){$("#content").show();}
        });
	});
</script>
<div id="ajax"></div>

<div class="card border-0" style="box-shadow:none;">
    <div class="card-body register-card-body">
      <form id="NCS_REGISTER">
        <div class="input-group mb-3">
		      <input type="text" placeholder="First name" class="form-control" name="NCS_FNAME" required maxlength="50"/>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
		      <input type="text" placeholder="Last name" class="form-control" name="NCS_LNAME" required maxlength="50"/>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
		</div>
        <div class="input-group mb-3">
		      <input type="email" class="form-control" placeholder="Email adress" name="NCS_EMAIL" required maxlength="100">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
		</div>
        <div class="input-group mb-3">
		      <input type="password" placeholder="Password" class="form-control" name="NCS_PASS" required />
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
		      <input type="password" placeholder="Password again" class="form-control" name="NCS_PASS2" required />
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
		</div>
        <div class="input-group mb-3">
		      <input type="text" placeholder="Business name" class="form-control" name="NCS_BUSINESS" required />
          <div class="input-group-append">
            <div class="input-group-text">
				<span class="fas fa-question-circle" data-toggle="tooltip" data-html="true" data-placement="bottom" title="This field refer to the actual free domain of your website, Don't worry you can always change it later.<br/>Your free domain will be like: https://{Business-Name}.rosance.com<br/>Maximum 50 characters only low-case alpha-numerical characters <small>(a-z AND 0-9)</small>"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
		  <h6><a href="javascript:void(0)" onclick="loadLogin()" class="link">I allready have an account</a></h6>
          </div>
          <div class="col-4">
			      <input type="hidden" name="NCS_ACTION" value="REGISTER">
            <button type="submit" class="btn btn-dark btn-block">Sign up</button>
          </div>
        </div>
      </form>

      <div class="social-auth-links text-center">
        <p>- OR -</p>
        <a href="javascript:void(0)" class="btn btn-block btn-outline-dark" id="signInWithFacebook">
          <img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Facebook sign-in" src="https://static.xx.fbcdn.net/rsrc.php/yo/r/iRmz9lCMBD2.ico" /> Sign up using Facebook
        </a>
        <a href="javascript:void(0)" class="btn btn-block btn-outline-dark" id="signInWithGoogle">
          <img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Google sign-in" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png" /> Sign up using Google
        </a>
      </div>

    <h6> 
		* By signing up I hereby declare that I agree to the <a href="https://rosance.com/tos">terms and conditons</a> and I acknowledge that I have read and understood the <a href="https://rosance.com/gdpr">privacy policy</a> of Rosance
	</h6>
    </div>
</div>
<script type="text/javascript">
$("#page-header").html("<h2 style='color:white;'>Welcome ,</br> <span class='text-gray' id='welcome_msg'>Fill up the form to join us</span></h2>");
$('[data-toggle="tooltip"]').tooltip(),$(function(){var t=$("#welcome_msg"),e=$("#welcome_msg").text().split("");$("#welcome_msg").text(""),$.each(e,function(e,a){var o=$("<span/>").text(a).css({opacity:0});o.appendTo(t),o.delay(70*e),o.animate({opacity:1},1100)})});
</script>