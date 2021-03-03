<script type="text/javascript">
	var ncs_id = "<?php if(isset($_POST['NCS_ID'])) echo $_POST['NCS_ID']; ?>";
    $("#NCS_FINISH_BUSINESS").submit(function(event) {

    event.preventDefault();
	var values = {"NCS_ACTION":"validateBusinessName","NCS_ID":ncs_id, "NCS_BUSINESS":$("#NCS_BUSINESS").val()};
	
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
    <div class="card-body login-card-body">
	<h6 class="pb-2 mb-2">
		This field refer to the actual free domain of your website<br/>
		Your free domain will be like: 
		<pre class="pb-1 mb-1">https://{Business-Name}.rosance.com</pre>
		Maximum 50 characters only low-case alpha-numerical characters <small>(a-z AND 0-9)</small>
	</h6>
    	<form id="NCS_FINISH_BUSINESS">
	  		<div class="form-group input-group no-border input-lg mt-2">
				<input type="text" class="form-control" placeholder="Business name" id="NCS_BUSINESS" name="NCS_BUSINESS" required>
				<div class="input-group-append bg-white">
					<div class="input-group-text bg-white">
                        <i class="fas fa-question-circle" data-toggle="tooltip" data-html="true" data-placement="bottom" title="This field refer to the actual free domain of your website, Don't worry you can always change it later.<br/>Your free domain will be like: https://{Business-Name}.rosance.com<br/>Maximum 50 characters only low-case alpha-numerical characters <small>(a-z AND 0-9)</small>"></i>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-8">

				</div>
				<div class="col-4">
					<input type="hidden" name="NCS_ACTION" value="LOGIN">
					<button type="submit" class="btn btn-dark btn-block">Finish</button>
				</div>
			</div>
      </form>
	<h6> 
		* By finishing registration I hereby declare that I agree to the <a href="https://rosance.com/tos">terms and conditons</a> and I acknowledge that I have read and understood the <a href="https://rosance.com/gdpr">privacy policy</a> of Rosance
	</h6>
    </div>
  </div>

<script type="text/javascript">
$("*[data-toggle*=tooltip]").tooltip();
$("#page-header").html("<h2 style='color:white;'>One more step ,</br> <span class='text-gray' id='welcome_msg'> Please tell us your business name</span></h2>");
$(function(){var t=$("#welcome_msg"),e=$("#welcome_msg").text().split("");$("#welcome_msg").text(""),$.each(e,function(e,a){var o=$("<span/>").text(a).css({opacity:0});o.appendTo(t),o.delay(70*e),o.animate({opacity:1},1100)})});
</script>