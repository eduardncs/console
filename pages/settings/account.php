<?php 
if(!isset($_SESSION))
    session_start();
require_once("../../autoload.php");
use Rosance\Data;
use Rosance\Project;
$data = new Data();
$user = $data->GetUser($_SESSION['loggedIN']);
?>
<div id="requests"></div>
<form id="account-settings">
<div class="row">
    <div class="col-md-12">
        <div class="card border-0">
            <div class="card-header">
            <h4>Basic info</h4>
            <small>Review and update your basic details</small>
            </div>
            <div class="card-body">
                <div class="row">
                <div class="col-md-2">
                    <input type="hidden" name="profile_pic" id="profile_pic" value="<?php echo $user->Profile_Pic; ?>" name="profile_pic">
                        <img src="<?php echo $user->Profile_Pic; ?>" alt="Profile Picture" onError="this.onError=null;this.src='images/placeholder.jpg';" id="profile_pic_preview" class="image img-circle img-fluid elevation-3" data-toggle="tooltip" data-placement="bottom" title="Change profile picture" alt="" style="cursor:pointer;min-width:170px; min-height:170px;">
                    </div>
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="">First Name</label>
                            <input type="text" name="first_name" value="<?php echo $user->First_Name; ?>" class="form-control elevation-2" placeholder="First name">
                        </div>
                        <div class="form-group">
                            <label for="">Last Name</label>
                            <input type="text" name="last_name" value="<?php echo $user->Last_Name; ?>" class="form-control elevation-2" placeholder="Last name">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card border-0">
            <div class="card-header">
            <h4>Sensitive info</h4>
            <small>Review and update your login and other sensitive informations</small>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="">Business Name</label>
                    <input type="text" name="business_name" class="form-control elevation-2 disabled" disabled value="<?php echo $user->Business_Name; ?>" placeholder="Business name">
                </div>
                <?php if($user->Provider == "rosance"): ?>
                <div class="form-group">
                    <label for="">Email adress</label>
                    <input type="text" name="email" class="form-control elevation-2" value="<?php echo $user->Email; ?>" placeholder="Business name">
                </div>
                <div class="form-group">
                    <label for="">Reset password</label>
                    <input type="password" style="cursor:pointer" class="form-control elevation-2" data-toggle="tooltip" data-placement="bottom" title="Click here to reset your password" id="resetPwd" value="*******">
                </div>
                <?php else: ?>
                <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Notice </h5>
                    Users logged in using Google or Facebook should access their social account settings in order to change their credentials
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card card-outline card-danger card-warning">
            <div class="card-header"><h4 class="card-title">Danger area</h4><br><small>This action is definitive and ireversible!</small></div>
            <div class="card-body">
                <button type="button" class="btn btn-block btn-danger elevation-2" id="removeAccount">I want to delete my account</button>
            </div>
        </div>
    </div>
</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
$("#savechanges").on("click",function(){
    $("#account-settings").submit();
});
$("#resetPwd").on("click",function(){
    getwidget("widgets/resetPwd.html");
});
$("#account-settings").change(function(){
    $("#savechanges").removeAttr("disabled");
    $("#savechanges").removeClass("disabled");
});
function selectMedia(media)
{
    $("#profile_pic_preview").attr("src", media);
    $("#profile_pic").val(media);
    $("#savechanges").removeClass("disabled");
    $("#savechanges").removeAttr("disabled");
}
</script>