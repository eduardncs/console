<script type="text/javascript">
    $("#NCS_LOGIN").submit(function(event) {
        event.preventDefault();
        var values = $("#NCS_LOGIN").serialize();
        $.ajax({
            url: "processors/registration.req.php",
            type: "post",
            data: values,
            beforeSend: () => {
                $("#overlay").show();
                $("#content").hide();
            },
            success: (data) => {
                $("#overlay").hide();
                $("#content").show();
                $('#ajax').html(data);
            },
            error: (error) => {
                $("#content").show();
            }
        });
    });
</script>
<div id="ajax"></div>

<div class="card border-0" style="box-shadow:none;">
    <div class="card-body login-card-body">
        <form id="NCS_LOGIN">
            <div class="form-group input-group no-border input-lg">
                <input type="email" class="form-control" placeholder="Email adress" name="NCS_EMAIL" required>
                <div class="input-group-append bg-white">
                    <span class="input-group-text bg-white">
						<i class="fas fa-envelope" style="color:#777;"></i>
					</span>
                </div>
            </div>
            <div class="form-group input-group no-border input-lg">
                <input type="password" placeholder="******" class="form-control" name="NCS_PASS" required/>
                <div class="input-group-append">
                    <span class="input-group-text bg-white">
						<i class="fas fa-lock" style="color:#777;"></i>
					</span>

                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <h6>
                        <a href="javascript:void(0)" onclick="loadRegister()" class="link">I don't have an account</a>
                    </h6>
                    <h6>
                        <a href="javascript:void(0)" onclick="getPage('pages/registration/resend.php')" class="link">Resend confirmation email</a>
                    </h6>
                </div>
                <div class="col-4">
                    <input type="hidden" name="NCS_ACTION" value="LOGIN">
                    <button type="submit" class="btn btn-dark btn-block">Sign In</button>
                </div>
            </div>
        </form>
        <div class="social-auth-links text-center mb-3">
            <p>- OR -</p>
            <a href="javascript:void(0)" class="btn btn-block btn-outline-dark" id="signInWithFacebook">
                <img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Facebook sign-in" src="https://static.xx.fbcdn.net/rsrc.php/yo/r/iRmz9lCMBD2.ico" /> Sign in using Facebook
            </a>
            <a href="javascript:void(0)" class="btn btn-block btn-outline-dark" id="signInWithGoogle">
                <img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Google sign-in" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png" /> Sign in using Google
            </a>
        </div>

        <h6>
            * By signing in I hereby declare that I agree to the <a href="https://rosance.com/tos">terms and conditons</a> and I acknowledge that I have read and understood the <a href="https://rosance.com/gdpr">privacy policy</a> of Rosance
        </h6>

    </div>
</div>
<script type="text/javascript">
    $("#page-header").html("<h2 style='color:white;'>Welcome ,</br> <span class='text-gray' id='welcome_msg'> Sign in to start your session</span></h2>");
    $(function() {
        var t = $("#welcome_msg"),
            e = $("#welcome_msg").text().split("");
        $("#welcome_msg").text(""), $.each(e, function(e, a) {
            var o = $("<span/>").text(a).css({
                opacity: 0
            });
            o.appendTo(t), o.delay(70 * e), o.animate({
                opacity: 1
            }, 1100)
        })
    });
</script>