<?php
require_once("../../autoload.php");
use Rosance\Database;
use Rosance\Data;
use Rosance\User;
if(!isset($_COOKIE['NCS_USER']))
    die('<div class="overlay text-center" id="overlay" style="position:inherit; width:100%; height:auto;"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div><h2 class="text-center">Sorry, the page was unreachable :( </h2>');
if(isset($_COOKIE['NCS_USER']))
{
    $data = new Data();
    $cookie = json_decode($_COOKIE['NCS_USER'],true);
    $user = $data->GetUser($cookie["UID"]);
}
if(isset($_POST["NCS_PASS"]) && isset($_POST["NCS_USER"]))
{
	$database = new Database();
	$database->restoreUser($_POST["NCS_PASS"], $_POST["NCS_USER"]);
}
?>
<script type="text/javascript">
    $("#NCS_LOCKED").submit(function(event) {
    event.preventDefault();
    var values = $("#NCS_LOCKED").serialize();
    $.ajax({
		url: "processors/registration.req.php",
	    type: "post",
	    data: values,
	    beforeSend: function(){$("#overlay").show(); $("#content").hide(); },
	    success: function(data){$("#overlay").hide(); $("#content").show(); $('#content').html(data);},
        error: function(){$("#content").show();}
        });
    });
</script>
<div id="ajax"></div>
<form id="NCS_LOCKED">
    <div class="widget-user-image text-center pb-4">
        <img src="<?php echo $user->Profile_Pic; ?>" onerror="this.error=null; this.src='images/default.png'" class="img-circle elevation-2" alt="Profile image" style="width:168px;height:168;">
    </div>
    <input type="hidden" name="NCS_USER" value='<?php echo $_COOKIE['NCS_USER']; ?>'>
    <?php
        $provider = $cookie['Provider'];
        if($provider == "google")
        {
            echo '
            <a href="javascript:void(0)" class="btn btn-block btn-outline-dark" id="signInWithGoogle">
                <img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Google sign-in" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png" /> 
            Sign back in using Google
            </a>';
        }elseif($provider == "facebook")
        {
            echo '
            <a href="javascript:void(0)" class="btn btn-block btn-outline-dark" id="signInWithFacebook">
                <img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Facebook sign-in" src="https://static.xx.fbcdn.net/rsrc.php/yo/r/iRmz9lCMBD2.ico" /> 
            Sign back in using Facebook
          </a>';
        }
        else
        {
    echo '
        <div class="input-group border">
            <input type="password" class="form-control border-0" name="NCS_PASS" placeholder="Please insert your password" required>
            <div class="input-group-append" style="background-color:#fff; color:#fff;">
                <button type="submit" class="btn mr-1" style="background-color:transparent;"><i class="fas fa-arrow-right"></i></button>
            </div>
        </div>';
        }
    ?>
        <p class="pt-2">
            <small>Your session has expired , please insert your credentials again</small><br>
            <small><a href="registration?action=clearCookies">Not you ? Click here to login on another account</a></small>
        </p>
</form>
<script type="text/javascript">
$("#page-header").html("<h2 style='color:white;'>Welcome back, <br> <span class='text-gray' id='welcome_msg'> <?php $user = json_decode($_COOKIE['NCS_USER'],true); echo $user['FirstName']." ".$user['LastName']; ?></span> </h2>");
$(function(){var t=$("#welcome_msg"),e=$("#welcome_msg").text().split("");$("#welcome_msg").text(""),$.each(e,function(e,a){var o=$("<span/>").text(a).css({opacity:0});o.appendTo(t),o.delay(70*e),o.animate({opacity:1},1100)})});
$("#signInWithGoogle").on("click",function(){signInWithGoogle();});
$("#signInWithFacebook").on("click",function(){signInWithFacebook();});
</script>
