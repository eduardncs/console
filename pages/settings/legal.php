<?php
if(!isset($_SESSION))
  session_start();
require_once("../../autoload.php");
use Rosance\Data;
use Rosance\Project;
use Rosance\User;
$data = new Data();
$user = $data->GetUser($_SESSION['loggedIN']);
$project = new Project($_COOKIE['NCS_PROJECT']);
$documents = $data->GetLegalDocuments($user,$project);
?>
<div id="requests"></div>
<form id="legal-documents">
<div class="row">
    <div class="col-md-12">
    <div class="card card-solid" style="border: 0px;">
				  <div class="card-header"><h4>Privacy policy</h4><small>Data privacy, which is also known as data protection or information privacy is a very prominent topic in today’s world. </small></div>
				  <div class="card-body">
				  <div class="row">
					<div class="col-md-12">
					  <div class="form-group">
                    <label for="GDPR">Privacy policy</label>
						  <textarea id="GDPR" name="GDPR" class="form-control" placeholder="Your privacy policy here"><?php echo $documents['GDPR']; ?></textarea>
                	</div>
					<p>
						<b>Privacy Policy definition:</b>
						<div class="attachment-block clearfix">
                  <div class="attachment-pushed">
                    <div class="attachment-text">
                     Privacy Policy is the document that outlines which information about the users you collect, where this information is stored, and how it will be treated. 
                    </div>
                    <!-- /.attachment-text -->
                  </div>
                  <!-- /.attachment-pushed -->
                </div>
						<b>Acording to Wikipedia</b>
				<div class="attachment-block clearfix">
                  <div class="attachment-pushed">
                    <div class="attachment-text">
                     Data Privacy is the relationship between the collection and dissemination of data, technology, the public expectation of privacy, legal and political issues surrounding them.
                    </div>
                    <!-- /.attachment-text -->
                  </div>
                  <!-- /.attachment-pushed -->
                </div>
					</p>
					<div class="text-left"><small>Need help ? Visit <a href="https://policymaker.io/privacy-policy/" target="_blank">Policymaker.io</a> and make it for free!</small></div>
					  </div>
				  </div>
				  </div>
				  </div>
    </div>
    <div class="col-md-12">
    <div class="card card-solid" style="border: 0px;">
				  <div class="card-header"><h4>Terms and Conditions</h4><small>It is an agreement between a service provider and a user of the service. Terms and conditions are usually determined by the service provider and outline rules and regulations associated with the usage of the website or app. </small></div>
				  <div class="card-body">
				  <div class="row">
					  <div class="col-md-12">
					  <div class="form-group">
                    <label for="TOS">Terms and Conditions</label>
						  <textarea id="TOS" name="TOS" class="form-control" placeholder="Your terms and conditions here"><?php echo $documents['TOS']; ?></textarea>
                	</div>
					<p>
						<b>You should know</b>
					<div class="attachment-block clearfix">
                  <div class="attachment-pushed">
                    <div class="attachment-text">
                     If you have a website or software application, you will likely need to have Terms of Service or as some people call it – Terms of Use for it. Terms of Service or Terms and Conditions (T&C) will, in legal terms, limit your business or personal liability. 

It is highly recommended to have robust and comprehensive Terms and Conditions for any website, online business, or application. It will provide you with proper protection in case some of your customers or users will decide to take legal action against your business. 

The best practice is only to allow using your service to people that agree with your Terms & Conditions, Disclaimer and Privacy Policy. This way, your website, business, or app will be protected from potential legal dangers. 
                    </div>
                    <!-- /.attachment-text -->
                  </div>
                  <!-- /.attachment-pushed -->
                </div>
					</p>
					<div class="text-left"><small>Need help ? Visit <a href="https://policymaker.io/terms-and-conditions/" target="_blank">Policymaker.io</a> and make it for free!</small></div>
					  </div>
				  </div>
				  </div>
				  </div>
    </div>
    <div class="col-md-12">
    <div class="card card-solid" style="border: 0px;">
				  <div class="card-header"><h4>Disclaimer</h4><small>If your website publishes any sort of medical, financial, or legal information, your visitors might use it to make crucial decisions that might have profound effects on their life.

As a matter of fact, you will likely want to <b>shield yourself</b> or your company from any responsibilities that might arise from those actions. </small></div>
				  <div class="card-body">
				  <div class="row">
					  <div class="col-md-12">
					  <div class="form-group">
                    <label for="DSCL">Disclaimer</label>
						  <textarea id="DSCL" name="DSCL" class="form-control" placeholder="Your disclaimer here"><?php echo $documents['DSCL']; ?></textarea>
                	</div>
					<p>
						<b>Disclaimer Definition</b>
						<div class="attachment-block clearfix">
                  <div class="attachment-pushed">
                    <div class="attachment-text">
                      A disclaimer is a statement that is made to limit the scope of your potential liability. 
                    </div>
                    <!-- /.attachment-text -->
                  </div>
                  <!-- /.attachment-pushed -->
                </div>
					</p>
					<div class="text-left"><small>Need help ? Visit <a href="https://policymaker.io/disclaimer/" target="_blank">Policymaker.io</a> and make it for free!</small></div>
					  </div>
				  </div>
				  </div>
				  </div>
    </div>
</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
  $("#legal").addClass("active");
    $("textarea").summernote({
            callbacks:{
                onChange: function(contents){
                    var btn = $("#savechanges");
                    if(contents != "")
                        {
                            btn.removeClass("disabled");
                            btn.removeAttr("disabled");
                        }
                }
            },
            height: 250
        });
    let a = $("#GDPR").val();
    let b = $("#TOS").val();
    let c = $("#DSCL").val();
    if((a == "") && (b == "") && (c==""))
    {   
        $("#savechanges").addClass("disabled");
        $("#savechanges").attr("disabled","true");
    }
    $("#savechanges").on("click", function(){
        $("#legal-documents").submit();
    });
});
</script>