<script type="text/javascript">
    $("#NCS_RESET_CONFIRM").submit(function(event) {
        event.preventDefault();
        var values = $("#NCS_RESET_CONFIRM").serialize();
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
        <form id="NCS_RESET_CONFIRM">
            <div class="form-group input-group no-border input-lg">
                <input type="email" class="form-control" placeholder="Email adress" name="NCS_EMAIL" required>
                <div class="input-group-append bg-white">
                    <span class="input-group-text bg-white">
						<i class="fas fa-envelope" style="color:#777;"></i>
					</span>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <h6>
                        <a href="javascript:void(0)" onclick="loadLogin()" class="link">Back to login page</a>
                    </h6>
                </div>
                <div class="col-4">
                    <input type="hidden" name="NCS_ACTION" value="RESENDCONFIRMATIONEMAIL">
                    <button type="submit" class="btn btn-dark btn-block">Confirm</button>
                </div>
            </div>
        </form>
        <h6>
            * Be sure to also check your spam folder. In case you still did not receive confirmation email use the <a href="https://rosance.com/contact">contact</a> form and submit a ticked, we will get back to you as soon as possible
        </h6>
    </div>
</div>
<script type="text/javascript">
    $("#page-header").html("<h2 style='color:white;'>Welcome ,</br> <span class='text-gray' id='welcome_msg'> We're trying our best </span></h2>");
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